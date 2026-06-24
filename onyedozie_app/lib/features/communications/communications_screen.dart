import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'communications_provider.dart';
import '../../core/providers.dart';

class CommunicationsScreen extends ConsumerStatefulWidget {
  const CommunicationsScreen({super.key});

  @override
  ConsumerState<CommunicationsScreen> createState() => _CommunicationsScreenState();
}

class _CommunicationsScreenState extends ConsumerState<CommunicationsScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final _broadcastMessageController = TextEditingController();
  final _postContentController = TextEditingController();
  final _customPhonesController = TextEditingController();
  
  String _broadcastAudienceType = 'all';
  String _broadcastSelectedRole = 'Volunteer';
  int? _broadcastSelectedLgaId;
  
  List<String> _postSelectedPlatforms = [];
  DateTime _postScheduledAt = DateTime.now().add(const Duration(hours: 1));

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    _broadcastMessageController.dispose();
    _postContentController.dispose();
    _customPhonesController.dispose();
    super.dispose();
  }

  bool get _isAdmin {
    final role = ref.read(storageServiceProvider).getUserRole() ?? '';
    return role.toLowerCase().contains('admin');
  }

  void _copyToClipboard(String text) {
    Clipboard.setData(ClipboardData(text: text));
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Content copied to clipboard! Paste it to share.'),
        backgroundColor: Color(0xFF4361EE),
      ),
    );
  }

  void _submitBroadcast() {
    final message = _broadcastMessageController.text.trim();
    if (message.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter a message.'), backgroundColor: Colors.red),
      );
      return;
    }

    final Map<String, dynamic> filter = {};
    if (_broadcastAudienceType == 'lga' && _broadcastSelectedLgaId != null) {
      filter['lga_id'] = _broadcastSelectedLgaId;
    } else if (_broadcastAudienceType == 'role') {
      filter['role'] = _broadcastSelectedRole;
    } else if (_broadcastAudienceType == 'custom') {
      final customPhonesRaw = _customPhonesController.text.trim();
      final phonesList = customPhonesRaw.split(RegExp(r'[\s,]+')).where((p) => p.isNotEmpty).toList();
      filter['phones'] = phonesList;
    }

    ref.read(communicationsProvider.notifier).createBroadcast(
          message: message,
          audienceType: _broadcastAudienceType,
          audienceFilter: filter,
          onSuccess: () {
            _broadcastMessageController.clear();
            _customPhonesController.clear();
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('WhatsApp Broadcast queued successfully!'), backgroundColor: Colors.green),
            );
          },
          onError: (error) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(error), backgroundColor: Colors.red),
            );
          },
        );
  }

  void _submitSocialPost() {
    final content = _postContentController.text.trim();
    if (content.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter post content.'), backgroundColor: Colors.red),
      );
      return;
    }
    if (_postSelectedPlatforms.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select at least one platform.'), backgroundColor: Colors.red),
      );
      return;
    }

    ref.read(communicationsProvider.notifier).scheduleSocialPost(
          content: content,
          platforms: _postSelectedPlatforms,
          scheduledAt: _postScheduledAt.toIso8601String(),
          onSuccess: () {
            _postContentController.clear();
            setState(() {
              _postSelectedPlatforms = [];
              _postScheduledAt = DateTime.now().add(const Duration(hours: 1));
            });
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Social post scheduled successfully!'), backgroundColor: Colors.green),
            );
          },
          onError: (error) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(error), backgroundColor: Colors.red),
            );
          },
        );
  }

  Future<void> _selectScheduledTime() async {
    final pickedDate = await showDatePicker(
      context: context,
      initialDate: _postScheduledAt,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 30)),
    );

    if (pickedDate != null) {
      final pickedTime = await showTimePicker(
        context: context,
        initialTime: TimeOfDay.fromDateTime(_postScheduledAt),
      );

      if (pickedTime != null) {
        setState(() {
          _postScheduledAt = DateTime(
            pickedDate.year,
            pickedDate.month,
            pickedDate.day,
            pickedTime.hour,
            pickedTime.minute,
          );
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(communicationsProvider);
    final bool admin = _isAdmin;

    return Scaffold(
      backgroundColor: const Color(0xFFF6FBF4),
      appBar: AppBar(
        title: const Text('Communications Hub', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.black87)),
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 0,
        bottom: admin
            ? TabBar(
                controller: _tabController,
                indicatorColor: const Color(0xFF3451DB),
                labelColor: const Color(0xFF3451DB),
                unselectedLabelColor: Colors.grey,
                labelStyle: const TextStyle(fontWeight: FontWeight.bold, fontFamily: 'Inter'),
                tabs: const [
                  Tab(text: 'Campaign Feed'),
                  Tab(text: 'Admin Control'),
                ],
              )
            : null,
      ),
      body: admin
          ? TabBarView(
              controller: _tabController,
              children: [
                _buildFeedTab(state),
                _buildControlTab(state),
              ],
            )
          : _buildFeedTab(state),
    );
  }

  Widget _buildFeedTab(CommunicationsState state) {
    return RefreshIndicator(
      onRefresh: () async {
        ref.read(communicationsProvider.notifier).loadAlerts();
        ref.read(communicationsProvider.notifier).loadScheduledPosts();
      },
      child: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Mobilization header
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF3451DB), Color(0xFF4361EE)],
                ),
                borderRadius: BorderRadius.circular(16),
              ),
              child: const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'MASS MOBILIZATION',
                    style: TextStyle(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 0.1),
                  ),
                  SizedBox(height: 4),
                  Text(
                    'Copy Alerts & Share to Mobilize',
                    style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                  SizedBox(height: 6),
                  Text(
                    'Tap to copy official templates and share them across your local WhatsApp groups and social networks to boost the campaign.',
                    style: TextStyle(color: Color(0xDEFFFFFF), fontSize: 11),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Segmented Subtitle
            const Text(
              'INSTANT MOBILIZATION ALERTS',
              style: TextStyle(fontFamily: 'Inter', fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF64748B)),
            ),
            const SizedBox(height: 10),

            state.isLoadingAlerts
                ? const Center(child: Padding(padding: EdgeInsets.all(16.0), child: CircularProgressIndicator()))
                : state.alerts.isEmpty
                    ? _buildEmptyState('No mobilization alerts found.')
                    : ListView.separated(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: state.alerts.length > 5 ? 5 : state.alerts.length,
                        separatorBuilder: (context, index) => const SizedBox(height: 12),
                        itemBuilder: (context, index) {
                          final alert = state.alerts[index];
                          return _buildAlertCard(alert);
                        },
                      ),

            const SizedBox(height: 24),

            const Text(
              'CAMPAIGN SOCIAL POSTS FEED',
              style: TextStyle(fontFamily: 'Inter', fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF64748B)),
            ),
            const SizedBox(height: 10),

            state.isLoadingPosts
                ? const Center(child: Padding(padding: EdgeInsets.all(16.0), child: CircularProgressIndicator()))
                : state.posts.isEmpty
                    ? _buildEmptyState('No social posts scheduled or published.')
                    : ListView.separated(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: state.posts.length > 5 ? 5 : state.posts.length,
                        separatorBuilder: (context, index) => const SizedBox(height: 12),
                        itemBuilder: (context, index) {
                          final post = state.posts[index];
                          return _buildSocialPostCard(post);
                        },
                      ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyState(String text) {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
      ),
      child: Center(
        child: Text(text, style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 13, fontStyle: FontStyle.italic)),
      ),
    );
  }

  Widget _buildAlertCard(dynamic alert) {
    final message = alert['message']?.toString() ?? '';
    final media = alert['media_path']?.toString();
    final timeStr = alert['created_at'] != null ? alert['created_at'].toString().substring(0, 10) : '';

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(6),
                    decoration: const BoxDecoration(color: Color(0xFFE8F5E9), shape: BoxShape.circle),
                    child: const Icon(Icons.forum, color: Colors.green, size: 16),
                  ),
                  const SizedBox(width: 8),
                  const Text('Broadcast Alert', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                ],
              ),
              Text(timeStr, style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 11)),
            ],
          ),
          const SizedBox(height: 12),
          Text(message, style: const TextStyle(color: Color(0xFF334155), fontSize: 13, height: 1.4)),
          if (media != null && media.isNotEmpty) ...[
            const SizedBox(height: 12),
            ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: Image.network(
                'https://onyedozie.olgagrp.com/storage/$media',
                height: 150,
                width: double.infinity,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) => Container(
                  height: 150,
                  color: Colors.grey[200],
                  child: const Center(child: Icon(Icons.image_not_supported, color: Colors.grey)),
                ),
              ),
            ),
          ],
          const SizedBox(height: 12),
          ElevatedButton.icon(
            onPressed: () => _copyToClipboard(message),
            icon: const Icon(Icons.copy, size: 14, color: Colors.white),
            label: const Text('COPY & MOBILIZE', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.white)),
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF3451DB),
              padding: const EdgeInsets.symmetric(vertical: 10),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSocialPostCard(dynamic post) {
    final content = post['content']?.toString() ?? '';
    final platforms = List<String>.from(post['platforms'] ?? []);
    final status = post['status']?.toString() ?? 'scheduled';
    final likes = post['engagement_likes'] ?? 0;
    final shares = post['engagement_shares'] ?? 0;
    final reach = post['engagement_reach'] ?? 0;

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Wrap(
                spacing: 6,
                children: platforms.map((p) => Container(
                  padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                  decoration: BoxDecoration(
                    color: Colors.blueGrey[55],
                    borderRadius: BorderRadius.circular(4),
                  ),
                  child: Text(p.toUpperCase(), style: const TextStyle(fontSize: 8, fontWeight: FontWeight.bold, color: Colors.blueGrey)),
                )).toList(),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                decoration: BoxDecoration(
                  color: status == 'posted' ? const Color(0xFFE8F5E9) : const Color(0xFFE3F2FD),
                  borderRadius: BorderRadius.circular(999),
                ),
                child: Text(
                  status == 'posted' ? 'PUBLISHED' : 'SCHEDULED',
                  style: TextStyle(fontSize: 8, fontWeight: FontWeight.bold, color: status == 'posted' ? Colors.green : Colors.blue),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(content, style: const TextStyle(color: Color(0xFF334155), fontSize: 13, height: 1.4)),
          const SizedBox(height: 12),
          if (status == 'posted')
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildMetric(Icons.thumb_up_outlined, '$likes Likes'),
                _buildMetric(Icons.share_outlined, '$shares Shares'),
                _buildMetric(Icons.remove_red_eye_outlined, '$reach Reach'),
              ],
            )
          else
            ElevatedButton.icon(
              onPressed: () => _copyToClipboard(content),
              icon: const Icon(Icons.share_outlined, size: 14, color: Color(0xFF3451DB)),
              label: const Text('COPY TO SHARE LATER', style: TextStyle(fontSize: 11, color: Color(0xFF3451DB))),
              style: OutlinedButton.styleFrom(
                elevation: 0,
                backgroundColor: Colors.white,
                side: const BorderSide(color: Color(0xFF3451DB)),
                padding: const EdgeInsets.symmetric(vertical: 8),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildMetric(IconData icon, String label) {
    return Row(
      children: [
        Icon(icon, size: 14, color: const Color(0xFF64748B)),
        const SizedBox(width: 4),
        Text(label, style: const TextStyle(fontSize: 11, color: Color(0xFF64748B), fontWeight: FontWeight.bold)),
      ],
    );
  }

  Widget _buildControlTab(CommunicationsState state) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Broadcast Composer Card
          Container(
            padding: const EdgeInsets.all(16),
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
                    Icon(Icons.campaign, color: Color(0xFFFF8C00)),
                    SizedBox(width: 8),
                    Text('Send WhatsApp Broadcast', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                  ],
                ),
                const Divider(height: 24),

                const Text('AUDIENCE TARGET', style: TextStyle(fontFamily: 'Inter', fontSize: 10, fontWeight: FontWeight.bold, color: Color(0xFF64748B))),
                const SizedBox(height: 6),
                DropdownButtonFormField<String>(
                  value: _broadcastAudienceType,
                  decoration: const InputDecoration(border: OutlineInputBorder()),
                  items: const [
                    DropdownMenuItem(value: 'all', child: Text('All Campaign Members')),
                    DropdownMenuItem(value: 'lga', child: Text('Filter By LGA')),
                    DropdownMenuItem(value: 'role', child: Text('Filter By Role')),
                    DropdownMenuItem(value: 'custom', child: Text('Custom Phone List')),
                  ],
                  onChanged: (val) {
                    setState(() {
                      _broadcastAudienceType = val ?? 'all';
                    });
                  },
                ),
                
                if (_broadcastAudienceType == 'custom') ...[
                  const SizedBox(height: 12),
                  const Text('PHONE LIST (COMMA SEPARATED)', style: TextStyle(fontFamily: 'Inter', fontSize: 10, fontWeight: FontWeight.bold, color: Color(0xFF64748B))),
                  const SizedBox(height: 6),
                  TextField(
                    controller: _customPhonesController,
                    maxLines: 2,
                    decoration: const InputDecoration(hintText: 'e.g. 08033221100, 09055443322', border: OutlineInputBorder()),
                  ),
                ],

                const SizedBox(height: 12),
                const Text('BROADCAST MESSAGE', style: TextStyle(fontFamily: 'Inter', fontSize: 10, fontWeight: FontWeight.bold, color: Color(0xFF64748B))),
                const SizedBox(height: 6),
                TextField(
                  controller: _broadcastMessageController,
                  maxLines: 4,
                  decoration: const InputDecoration(hintText: 'Compose your mass mobilization alert message...', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 16),
                ElevatedButton(
                  onPressed: state.isSaving ? null : _submitBroadcast,
                  style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF3451DB), padding: const EdgeInsets.symmetric(vertical: 14)),
                  child: state.isSaving
                      ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(color: Colors.white))
                      : const Text('DISPATCH BROADCAST', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // Social Post Scheduler Card
          Container(
            padding: const EdgeInsets.all(16),
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
                    Icon(Icons.schedule, color: Color(0xFF3451DB)),
                    SizedBox(width: 8),
                    Text('Schedule Social Post', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                  ],
                ),
                const Divider(height: 24),

                const Text('POST CONTENT', style: TextStyle(fontFamily: 'Inter', fontSize: 10, fontWeight: FontWeight.bold, color: Color(0xFF64748B))),
                const SizedBox(height: 6),
                TextField(
                  controller: _postContentController,
                  maxLines: 4,
                  decoration: const InputDecoration(hintText: 'What would you like to schedule?', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 12),

                const Text('PLATFORMS', style: TextStyle(fontFamily: 'Inter', fontSize: 10, fontWeight: FontWeight.bold, color: Color(0xFF64748B))),
                const SizedBox(height: 6),
                Row(
                  children: [
                    _buildPlatformCheckbox('facebook', 'Facebook'),
                    _buildPlatformCheckbox('instagram', 'Instagram'),
                    _buildPlatformCheckbox('x', 'X / Twitter'),
                  ],
                ),
                
                const SizedBox(height: 12),
                const Text('SCHEDULED TIME', style: TextStyle(fontFamily: 'Inter', fontSize: 10, fontWeight: FontWeight.bold, color: Color(0xFF64748B))),
                const SizedBox(height: 6),
                InkWell(
                  onTap: _selectScheduledTime,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
                    decoration: BoxDecoration(
                      border: Border.all(color: Colors.grey),
                      borderRadius: BorderRadius.circular(4),
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(_postScheduledAt.toString().substring(0, 16)),
                        const Icon(Icons.calendar_today, size: 16, color: Color(0xFF64748B)),
                      ],
                    ),
                  ),
                ),

                const SizedBox(height: 16),
                ElevatedButton(
                  onPressed: state.isSaving ? null : _submitSocialPost,
                  style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF3451DB), padding: const EdgeInsets.symmetric(vertical: 14)),
                  child: state.isSaving
                      ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(color: Colors.white))
                      : const Text('SCHEDULE POST', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPlatformCheckbox(String id, String label) {
    final isSelected = _postSelectedPlatforms.contains(id);
    return Padding(
      padding: const EdgeInsets.only(right: 16.0),
      child: FilterChip(
        label: Text(label, style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: isSelected ? Colors.white : Colors.black87)),
        selected: isSelected,
        selectedColor: const Color(0xFF3451DB),
        checkmarkColor: Colors.white,
        backgroundColor: Colors.grey[200],
        onSelected: (selected) {
          setState(() {
            if (selected) {
              _postSelectedPlatforms.add(id);
            } else {
              _postSelectedPlatforms.remove(id);
            }
          });
        },
      ),
    );
  }
}
