import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:geolocator/geolocator.dart';
import 'canvassing_provider.dart';
import '../../core/providers.dart';

class CanvassingScreen extends ConsumerStatefulWidget {
  final VoidCallback? onPointsIncremented;

  const CanvassingScreen({
    super.key,
    this.onPointsIncremented,
  });

  @override
  ConsumerState<CanvassingScreen> createState() => _CanvassingScreenState();
}

class _CanvassingScreenState extends ConsumerState<CanvassingScreen> {
  final _voterNameController = TextEditingController();
  final _addressController = TextEditingController();
  final _notesController = TextEditingController();
  String _selectedOutcome = 'supportive';
  bool _isSyncingLocation = false;

  @override
  void dispose() {
    _voterNameController.dispose();
    _addressController.dispose();
    _notesController.dispose();
    super.dispose();
  }

  Future<void> _syncLocation() async {
    setState(() {
      _isSyncingLocation = true;
    });
    try {
      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
      }

      if (permission == LocationPermission.always || permission == LocationPermission.whileInUse) {
        final position = await Geolocator.getCurrentPosition();
        final apiClient = ref.read(apiClientProvider);

        final response = await apiClient.post('/location/ping', data: {
          'lat': position.latitude,
          'lng': position.longitude,
          'accuracy': position.accuracy,
          'battery_level': 85,
        });

        if (response.data['success'] == true) {
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text('GPS Location ping successfully synchronized to Campaign HQ!'),
                backgroundColor: Color(0xFF3451DB),
              ),
            );
          }
        }
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Location permissions are required to sync coordinates.'),
              backgroundColor: Colors.orange,
            ),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Failed to sync GPS: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isSyncingLocation = false;
        });
      }
    }
  }

  void _submitVisit() {
    final name = _voterNameController.text.trim();
    final address = _addressController.text.trim();
    final notes = _notesController.text.trim();

    ref.read(canvassingProvider.notifier).saveVisit(
          voterName: name,
          address: address,
          outcome: _selectedOutcome,
          notes: notes,
          onSuccess: () {
            _voterNameController.clear();
            _addressController.clear();
            _notesController.clear();
            widget.onPointsIncremented?.call();
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text('Visit logged! 2 campaign points awarded.'),
                backgroundColor: Color(0xFF4361EE),
              ),
            );
          },
          onQueuedOffline: () {
            _voterNameController.clear();
            _addressController.clear();
            _notesController.clear();
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text('Offline: Visit queued locally. Will sync when back online.'),
                backgroundColor: Colors.orange,
              ),
            );
          },
          onError: (errorMsg) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(errorMsg),
                backgroundColor: Colors.red,
              ),
            );
          },
        );
  }

  Widget _buildOutcomeChip(String outcome, String label, Color color) {
    final isSelected = _selectedOutcome == outcome;
    return ChoiceChip(
      label: Text(label),
      selected: isSelected,
      onSelected: (selected) {
        if (selected) {
          setState(() {
            _selectedOutcome = outcome;
          });
        }
      },
      selectedColor: color.withOpacity(0.2),
      backgroundColor: Colors.white,
      labelStyle: TextStyle(
        fontFamily: 'Inter',
        fontSize: 11,
        fontWeight: FontWeight.bold,
        color: color,
      ),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(999),
        side: BorderSide(
          color: isSelected ? color : const Color(0xFFCED4E0),
          width: isSelected ? 2.0 : 0.5,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(canvassingProvider);

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Map simulation block
          Container(
            height: 176,
            decoration: BoxDecoration(
              color: const Color(0xFFDFE4DD),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
            ),
            child: Stack(
              children: [
                Positioned.fill(
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(16),
                    child: Container(
                      color: const Color(0xFFE5E9E3),
                      child: const Center(
                        child: Icon(Icons.map_outlined, size: 48, color: Color(0xFF94A3B8)),
                      ),
                    ),
                  ),
                ),
                Positioned(
                  bottom: 12,
                  left: 12,
                  right: 12,
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.9),
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                        ),
                        child: const Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'CURRENT AREA',
                              style: TextStyle(
                                fontFamily: 'Inter',
                                fontSize: 10,
                                fontWeight: FontWeight.bold,
                                color: Color(0xFF64748B),
                              ),
                            ),
                            Text(
                              'Independence Layout',
                              style: TextStyle(
                                fontFamily: 'Inter',
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                                color: Color(0xFF3451DB),
                              ),
                            ),
                          ],
                        ),
                      ),
                      FloatingActionButton.small(
                        onPressed: _isSyncingLocation ? null : _syncLocation,
                        backgroundColor: const Color(0xFF3451DB),
                        child: _isSyncingLocation
                            ? const SizedBox(
                                height: 18,
                                width: 18,
                                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                              )
                            : const Icon(Icons.my_location, color: Colors.white),
                      )
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),

          // Log Visit card
          Container(
            padding: const EdgeInsets.all(16.0),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const Row(
                  children: [
                    Icon(Icons.edit_note, color: Color(0xFFFF8C00)),
                    SizedBox(width: 8),
                    Text(
                      'Log Visit',
                      style: TextStyle(
                        fontFamily: 'Inter',
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
                const Divider(height: 24, color: Color(0xFFCED4E0)),

                // Voter name input
                const Text(
                  'VOTER NAME',
                  style: TextStyle(fontFamily: 'Inter', fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF64748B)),
                ),
                const SizedBox(height: 6),
                TextField(
                  controller: _voterNameController,
                  decoration: const InputDecoration(
                    hintText: 'Enter Full Name',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 12),

                // Address input
                const Text(
                  'ADDRESS / DESCRIPTION',
                  style: TextStyle(fontFamily: 'Inter', fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF64748B)),
                ),
                const SizedBox(height: 6),
                TextField(
                  controller: _addressController,
                  decoration: const InputDecoration(
                    hintText: 'House 4B, 2nd Avenue...',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 12),

                // Interaction outcome
                const Text(
                  'INTERACTION OUTCOME',
                  style: TextStyle(fontFamily: 'Inter', fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF64748B)),
                ),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 8,
                  runSpacing: 8,
                  children: [
                    _buildOutcomeChip('supportive', 'SUPPORTIVE', const Color(0xFF3451DB)),
                    _buildOutcomeChip('neutral', 'NEUTRAL', const Color(0xFFE65100)),
                    _buildOutcomeChip('opposed', 'OPPOSED', Colors.red),
                    _buildOutcomeChip('not_home', 'NOT HOME', const Color(0xFF64748B)),
                  ],
                ),
                const SizedBox(height: 16),

                // Engagement Notes
                const Text(
                  'ENGAGEMENT NOTES',
                  style: TextStyle(fontFamily: 'Inter', fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF64748B)),
                ),
                const SizedBox(height: 6),
                TextField(
                  controller: _notesController,
                  maxLines: 3,
                  decoration: const InputDecoration(
                    hintText: 'Key concerns or discussion points...',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 16),

                // Action buttons
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton.icon(
                        onPressed: () {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Photo attachment feature is under development.'),
                            ),
                          );
                        },
                        icon: const Icon(Icons.photo_camera, color: Color(0xFF64748B)),
                        label: const Text('PHOTO', style: TextStyle(color: Color(0xFF64748B))),
                        style: OutlinedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 14),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      flex: 2,
                      child: ElevatedButton(
                        onPressed: state.isSaving ? null : _submitVisit,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF3451DB),
                          padding: const EdgeInsets.symmetric(vertical: 14),
                        ),
                        child: state.isSaving
                            ? const SizedBox(
                                height: 20,
                                width: 20,
                                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                              )
                            : const Text('SAVE VISIT', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // Recent Logs list
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text(
                'RECENT LOGS TODAY',
                style: TextStyle(fontFamily: 'Inter', fontSize: 12, fontWeight: FontWeight.bold, color: Color(0xFF64748B)),
              ),
              Text(
                '${state.logs.length} Visits Completed',
                style: const TextStyle(fontFamily: 'Inter', fontSize: 12, fontWeight: FontWeight.bold, color: Color(0xFF3451DB)),
              ),
            ],
          ),
          const SizedBox(height: 12),
          state.isLoading
              ? const Center(child: Padding(padding: EdgeInsets.all(16.0), child: CircularProgressIndicator()))
              : state.logs.isEmpty
                  ? Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                      ),
                      child: const Center(child: Text('No canvassing visits logged today.', style: TextStyle(color: Color(0xFF94A3B8), fontSize: 14))),
                    )
                  : ListView.separated(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: state.logs.length,
                      separatorBuilder: (context, index) => const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        final log = state.logs[index];
                        final outcome = (log['outcome'] ?? '').toString().toUpperCase();
                        final isSupportive = outcome == 'SUPPORTIVE';
                        return Container(
                          padding: const EdgeInsets.all(16.0),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                          ),
                          child: Row(
                            children: [
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(log['voter_name'] ?? 'Unknown Occupant', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                                    const SizedBox(height: 4),
                                    Text(
                                      '${log['address_description'] ?? 'No address'} • ${log['visited_at'] != null ? DateTime.parse(log['visited_at']).toLocal().toString().substring(11, 16) : 'N/A'}',
                                      style: const TextStyle(color: Color(0xFF64748B), fontSize: 12),
                                    ),
                                  ],
                                ),
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                decoration: BoxDecoration(
                                  color: isSupportive
                                      ? const Color(0xFF3451DB).withOpacity(0.1)
                                      : Colors.grey.withOpacity(0.1),
                                  borderRadius: BorderRadius.circular(4),
                                ),
                                child: Text(
                                  outcome,
                                  style: TextStyle(
                                    fontSize: 10,
                                    fontWeight: FontWeight.bold,
                                    color: isSupportive ? const Color(0xFF3451DB) : Colors.black87,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        );
                      },
                    ),
        ],
      ),
    );
  }
}
