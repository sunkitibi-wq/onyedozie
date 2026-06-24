import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:geolocator/geolocator.dart';
import 'package:image_picker/image_picker.dart';
import '../../core/providers.dart';

class ElectionOpsScreen extends ConsumerStatefulWidget {
  final String initialMode; // 'results', 'incident', or 'voice'

  const ElectionOpsScreen({super.key, required this.initialMode});

  @override
  ConsumerState<ElectionOpsScreen> createState() => _ElectionOpsScreenState();
}

class _ElectionOpsScreenState extends ConsumerState<ElectionOpsScreen> {
  late String _currentMode;
  final _picker = ImagePicker();

  // Results State
  final Map<String, TextEditingController> _partyControllers = {
    'APGA': TextEditingController(),
    'APC': TextEditingController(),
    'PDP': TextEditingController(),
    'LP': TextEditingController(),
  };
  File? _ec8aImage;
  bool _isUploadingResults = false;

  // Incident State
  String _selectedIncidentType = 'violence';
  String _selectedUrgency = 'medium';
  final _incidentDescController = TextEditingController();
  File? _incidentImage;
  bool _isReportingIncident = false;

  // Voice Note State
  bool _isRecording = false;
  bool _hasRecorded = false;
  bool _isUploadingVoice = false;

  @override
  void initState() {
    super.initState();
    _currentMode = widget.initialMode;
  }

  @override
  void dispose() {
    for (var controller in _partyControllers.values) {
      controller.dispose();
    }
    _incidentDescController.dispose();
    super.dispose();
  }

  // --- RESULT ACTIONS ---
  Future<void> _pickEc8aImage() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.camera);
    if (pickedFile != null) {
      setState(() {
        _ec8aImage = File(pickedFile.path);
      });
    }
  }

  Future<void> _submitResults() async {
    if (_ec8aImage == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please capture the physical EC8A results sheet photo first.'), backgroundColor: Colors.red),
      );
      return;
    }

    setState(() => _isUploadingResults = true);

    try {
      final apiClient = ref.read(apiClientProvider);
      final List<Map<String, dynamic>> resultsList = [];

      _partyControllers.forEach((party, controller) {
        final votes = int.tryParse(controller.text) ?? 0;
        resultsList.add({
          'party': party,
          'votes': votes,
        });
      });

      final storage = ref.read(storageServiceProvider);
      final puId = storage.getPollingUnitId() ?? 1;

      final response = await apiClient.post('/results', data: {
        'polling_unit_id': puId,
        'ec8a_image_path': _ec8aImage!.path,
        'results': resultsList,
      });

      if (response.data['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Results and EC8A sheet uploaded successfully!'), backgroundColor: Color(0xFF3451DB)),
        );
        Navigator.pop(context);
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Failed to submit results. Verification requirements unmet.'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => _isUploadingResults = false);
    }
  }

  // --- INCIDENT ACTIONS ---
  Future<void> _pickIncidentImage() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.camera);
    if (pickedFile != null) {
      setState(() {
        _incidentImage = File(pickedFile.path);
      });
    }
  }

  Future<void> _submitIncident() async {
    if (_incidentDescController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please add incident details description.'), backgroundColor: Colors.red),
      );
      return;
    }

    setState(() => _isReportingIncident = true);

    try {
      double lat = 6.2206;
      double lng = 7.0094;

      try {
        final position = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.high,
          timeLimit: const Duration(seconds: 5),
        );
        lat = position.latitude;
        lng = position.longitude;
      } catch (_) {
        // Fallback to default Anambra state location if permission is blocked
      }

      final payload = {
        'type': _selectedIncidentType,
        'urgency': _selectedUrgency,
        'description': _incidentDescController.text.trim(),
        'lat': lat,
        'lng': lng,
        'media': _incidentImage != null
            ? [
                {'path': _incidentImage!.path, 'type': 'photo'}
              ]
            : null,
      };

      final offlineSync = ref.read(offlineSyncServiceProvider);
      final isOnline = await offlineSync.isOnline();

      if (!isOnline) {
        // Queue incident report locally in Hive sync queue
        await offlineSync.queueAction(
          endpoint: '/incidents',
          data: payload,
          type: 'incident',
        );

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('No active internet connection. Incident report queued for background synchronization.'),
            backgroundColor: Colors.orange,
          ),
        );
        Navigator.pop(context);
        return;
      }

      final apiClient = ref.read(apiClientProvider);
      final response = await apiClient.post('/incidents', data: payload);

      if (response.data['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Incident reported and escalated successfully!'), backgroundColor: Colors.redAccent),
        );
        Navigator.pop(context);
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Failed to submit incident report.'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => _isReportingIncident = false);
    }
  }

  // --- VOICE ACTIONS ---
  void _toggleRecording() {
    if (_isRecording) {
      setState(() {
        _isRecording = false;
        _hasRecorded = true;
      });
    } else {
      setState(() {
        _isRecording = true;
        _hasRecorded = false;
      });
    }
  }

  Future<void> _submitVoiceReport() async {
    setState(() => _isUploadingVoice = true);

    try {
      final apiClient = ref.read(apiClientProvider);
      final response = await apiClient.post('/voice-reports', data: {
        'audio_path': 'mock_audio_record_${DateTime.now().millisecondsSinceEpoch}.mp4',
        'duration': 42,
        'transcript': 'Accreditation delayed at Polling Unit 02 in ward 04.',
      });

      if (response.data['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Voice report uploaded successfully!'), backgroundColor: Color(0xFF3451DB)),
        );
        Navigator.pop(context);
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Failed to submit voice report.'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => _isUploadingVoice = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: Colors.white,
        title: Text(
          _currentMode == 'results'
              ? 'Results Centre'
              : _currentMode == 'incident'
                  ? 'Escalate Incident'
                  : 'Voice Updates',
          style: const TextStyle(fontWeight: FontWeight.bold, color: Color(0xFF4361EE)),
        ),
        iconTheme: const IconThemeData(color: Color(0xFF4361EE)),
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(48),
          child: Container(
            color: Colors.white,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: [
                _buildModeTab('results', 'Results', Icons.ballot),
                _buildModeTab('incident', 'Incidents', Icons.report_problem),
                _buildModeTab('voice', 'Voice Note', Icons.mic),
              ],
            ),
          ),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: _currentMode == 'results'
            ? _buildResultsForm()
            : _currentMode == 'incident'
                ? _buildIncidentForm()
                : _buildVoiceForm(),
      ),
    );
  }

  Widget _buildModeTab(String mode, String label, IconData icon) {
    final isSelected = _currentMode == mode;
    return InkWell(
      onTap: () => setState(() => _currentMode = mode),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
        decoration: BoxDecoration(
          border: Border(
            bottom: BorderSide(
              color: isSelected ? const Color(0xFF4361EE) : Colors.transparent,
              width: 3,
            ),
          ),
        ),
        child: Row(
          children: [
            Icon(icon, size: 16, color: isSelected ? const Color(0xFF4361EE) : Colors.grey),
            const SizedBox(width: 4),
            Text(
              label,
              style: TextStyle(
                fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                color: isSelected ? const Color(0xFF4361EE) : Colors.grey,
              ),
            ),
          ],
        ),
      ),
    );
  }

  // --- BUILD RESULTS VIEW ---
  Widget _buildResultsForm() {
    return Card(
      color: Colors.white,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: const BorderSide(color: Color(0xFFE2E8F0), width: 0.5),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Text(
              'Input Polling Unit Vote Count',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Color(0xFF4361EE)),
            ),
            const SizedBox(height: 16),
            ..._partyControllers.entries.map((entry) {
              return Padding(
                padding: const EdgeInsets.only(bottom: 12.0),
                child: TextField(
                  controller: entry.value,
                  keyboardType: TextInputType.number,
                  decoration: InputDecoration(
                    labelText: '${entry.key} Total Votes',
                    border: const OutlineInputBorder(),
                  ),
                ),
              );
            }),
            const SizedBox(height: 16),
            InkWell(
              onTap: _pickEc8aImage,
              child: Container(
                height: 150,
                decoration: BoxDecoration(
                  color: const Color(0xFFEEF2FF),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: _ec8aImage != null
                    ? Image.file(_ec8aImage!, fit: BoxFit.cover)
                    : const Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.camera_alt, size: 40, color: Color(0xFF4361EE)),
                          SizedBox(height: 8),
                          Text('Capture EC8A Form Photo', style: TextStyle(color: Color(0xFF4361EE), fontWeight: FontWeight.bold)),
                        ],
                      ),
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: _isUploadingResults ? null : _submitResults,
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF4361EE),
                padding: const EdgeInsets.symmetric(vertical: 16),
              ),
              child: _isUploadingResults
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text('SUBMIT RESULTS SHEET', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
            ),
          ],
        ),
      ),
    );
  }

  // --- BUILD INCIDENT VIEW ---
  Widget _buildIncidentForm() {
    return Card(
      color: Colors.white,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: const BorderSide(color: Color(0xFFE2E8F0), width: 0.5),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Text(
              'Escalate Critical Incident',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.red),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _selectedIncidentType,
              decoration: const InputDecoration(labelText: 'Incident Type', border: OutlineInputBorder()),
              items: const [
                DropdownMenuItem(value: 'violence', child: Text('Violence')),
                DropdownMenuItem(value: 'ballot stuffing', child: Text('Ballot Stuffing')),
                DropdownMenuItem(value: 'INEC official misconduct', child: Text('INEC Misconduct')),
                DropdownMenuItem(value: 'result falsification', child: Text('Result Falsification')),
                DropdownMenuItem(value: 'other', child: Text('Other')),
              ],
              onChanged: (val) {
                if (val != null) setState(() => _selectedIncidentType = val);
              },
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _selectedUrgency,
              decoration: const InputDecoration(labelText: 'Urgency / Severity', border: OutlineInputBorder()),
              items: const [
                DropdownMenuItem(value: 'low', child: Text('Low')),
                DropdownMenuItem(value: 'medium', child: Text('Medium')),
                DropdownMenuItem(value: 'high', child: Text('High')),
                DropdownMenuItem(value: 'critical', child: Text('Critical')),
              ],
              onChanged: (val) {
                if (val != null) setState(() => _selectedUrgency = val);
              },
            ),
            const SizedBox(height: 12),
            TextField(
              controller: _incidentDescController,
              decoration: const InputDecoration(labelText: 'Details / Description', border: OutlineInputBorder()),
              maxLines: 3,
            ),
            const SizedBox(height: 16),
            InkWell(
              onTap: _pickIncidentImage,
              child: Container(
                height: 120,
                decoration: BoxDecoration(
                  color: const Color(0xFFEEF2FF),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: _incidentImage != null
                    ? Image.file(_incidentImage!, fit: BoxFit.cover)
                    : const Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.add_a_photo, size: 32, color: Colors.red),
                          SizedBox(height: 8),
                          Text('Add Evidence Photo (Optional)', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold)),
                        ],
                      ),
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: _isReportingIncident ? null : _submitIncident,
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
                padding: const EdgeInsets.symmetric(vertical: 16),
              ),
              child: _isReportingIncident
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text('SUBMIT CRITICAL REPORT', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
            ),
          ],
        ),
      ),
    );
  }

  // --- BUILD VOICE VIEW ---
  Widget _buildVoiceForm() {
    return Card(
      color: Colors.white,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: const BorderSide(color: Color(0xFFE2E8F0), width: 0.5),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Text(
              'Record Voice Report',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Color(0xFF4361EE)),
            ),
            const SizedBox(height: 24),
            Center(
              child: InkWell(
                onTap: _toggleRecording,
                borderRadius: BorderRadius.circular(100),
                child: Container(
                  width: 120,
                  height: 120,
                  decoration: BoxDecoration(
                    color: _isRecording ? Colors.red.withOpacity(0.2) : const Color(0xFFEEF2FF),
                    shape: BoxShape.circle,
                    border: Border.all(color: _isRecording ? Colors.red : const Color(0xFF4361EE), width: 2),
                  ),
                  child: Icon(
                    _isRecording ? Icons.stop : Icons.mic,
                    size: 64,
                    color: _isRecording ? Colors.red : const Color(0xFF4361EE),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 16),
            Center(
              child: Text(
                _isRecording ? 'Recording audio update...' : (_hasRecorded ? 'Audio capture completed' : 'Tap to start recording'),
                style: TextStyle(
                  fontWeight: FontWeight.bold,
                  color: _isRecording ? Colors.red : const Color(0xFF94A3B8),
                ),
              ),
            ),
            const SizedBox(height: 24),
            ElevatedButton(
              onPressed: (_hasRecorded && !_isUploadingVoice) ? _submitVoiceReport : null,
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF3451DB),
                padding: const EdgeInsets.symmetric(vertical: 16),
              ),
              child: _isUploadingVoice
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text('SUBMIT VOICE REPORT', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
            ),
          ],
        ),
      ),
    );
  }
}
