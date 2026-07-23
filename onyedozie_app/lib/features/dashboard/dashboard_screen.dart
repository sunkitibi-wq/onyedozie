import 'dart:async';
import 'dart:io';
import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:geolocator/geolocator.dart';
import '../../core/providers.dart';
import '../auth/auth_provider.dart';
import 'election_ops_screen.dart';
import '../recruitment/volunteer_recruitment_screen.dart';
import '../canvassing/canvassing_screen.dart';
import '../canvassing/canvassing_provider.dart';
import '../communications/communications_screen.dart';
import '../news/news_screen.dart';
import '../news/news_detail_screen.dart';
int? _toInt(dynamic val) {
  if (val == null) return null;
  if (val is int) return val;
  return int.tryParse(val.toString());
}

class DashboardScreen extends ConsumerStatefulWidget {
  const DashboardScreen({super.key});

  @override
  ConsumerState<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends ConsumerState<DashboardScreen> {
  int _currentIndex = 0;

  // Shared state that can be altered from tabs
  int _points = 0;
  bool _isTracking = true;
  String _selectedMemberFilter = 'Verified';

  // API-backed lists
  List<Map<String, String>> _membersList = [];
  List<Map<String, dynamic>> _newsList = [];
  bool _isLoadingMembers = false;
  bool _isLoadingNews = false;
  int _totalMembers = 0;
  int _completedTasksCount = 0;
  int _totalTasksCount = 0;

  // Profile Form Controllers & state
  final _profileNameController = TextEditingController();
  final _profilePhoneController = TextEditingController();
  final _profilePasswordController = TextEditingController();
  int? _profileSelectedLgaId;
  int? _profileSelectedWardId;
  int? _profileSelectedPuId;
  List<dynamic> _profileWards = [];
  List<dynamic> _profilePollingUnits = [];
  bool _isUpdatingProfile = false;
  File? _profileImageFile;

  Future<void> _pickProfileImage() async {
    final ImagePicker picker = ImagePicker();
    final XFile? image = await picker.pickImage(source: ImageSource.gallery);
    if (image != null) {
      setState(() {
        _profileImageFile = File(image.path);
      });
    }
  }

  List<dynamic> _lgas = [];
  bool _isLoadingGeo = false;
  bool _quickActionsEnabled = true;
  bool _quickActionRecruitVisible = true;
  bool _quickActionResultsVisible = true;
  bool _quickActionIncidentVisible = true;
  bool _quickActionVoiceVisible = true;
  bool _quickActionMembersVisible = true;
  bool _quickActionMobilizeVisible = true;
  List<dynamic> _tasks = [];
  bool _isLoadingTasks = false;

  // Notifications
  List<dynamic> _notifications = [];
  int _unreadCount = 0;
  bool _isLoadingNotifications = false;

  // Dynamic resolved data
  String? _resolvedUserName;
  String? _resolvedUserRole;
  String? _resolvedLgaName;
  String? _resolvedWardName;

  // Events State
  List<dynamic> _events = [];
  bool _isLoadingEvents = false;

  // Media State
  List<dynamic> _mediaList = [];
  bool _isLoadingMedia = false;
  String _selectedMediaCategory = 'All Media';

  @override
  void initState() {
    super.initState();
    _registerFcmToken();
    _loadUserProfile();
    _loadEvents();
    _loadMedia();
    _loadMembers();
    _loadNews();
    _loadLgas();
    _initProfileFields();
    _loadSettings();
    _loadTasks();
    _loadNotifications();
    // Trigger offline synchronization runner on startup
    WidgetsBinding.instance.addPostFrameCallback((_) {
      ref.read(offlineSyncServiceProvider).syncPendingActions();
    });
  }

  Future<void> _loadSettings() async {
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/settings');
      if (response.data['success'] == true) {
        setState(() {
          final data = response.data['data'];
          _quickActionsEnabled = data['quick_actions_enabled'] ?? true;
          _quickActionRecruitVisible = data['quick_action_recruit_visible'] ?? true;
          _quickActionResultsVisible = data['quick_action_results_visible'] ?? true;
          _quickActionIncidentVisible = data['quick_action_incident_visible'] ?? true;
          _quickActionVoiceVisible = data['quick_action_voice_visible'] ?? true;
          _quickActionMembersVisible = data['quick_action_members_visible'] ?? true;
          _quickActionMobilizeVisible = data['quick_action_mobilize_visible'] ?? true;
        });
      }
    } catch (_) {}
  }

  Future<void> _loadMembers() async {
    setState(() {
      _isLoadingMembers = true;
    });

    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/members?recruited_by_me=1');
      if (response.data['success'] == true) {
        final items = (response.data['data'] as List<dynamic>)
            .map((member) => _normalizeMember(member))
            .toList();
        final meta = response.data['meta'];

        setState(() {
          _membersList = items;
          _totalMembers = meta is Map<String, dynamic>
              ? (meta['total'] as int? ?? items.length)
              : items.length;
        });
      }
    } catch (_) {}
    finally {
      if (mounted) {
        setState(() {
          _isLoadingMembers = false;
        });
      }
    }
  }

  Future<void> _loadNews() async {
    setState(() {
      _isLoadingNews = true;
    });

    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/news');
      if (response.data['success'] == true) {
        final items = (response.data['data'] as List<dynamic>)
            .map((news) => _normalizeNews(news))
            .toList();

        setState(() {
          _newsList = items;
        });
      }
    } catch (_) {}
    finally {
      if (mounted) {
        setState(() {
          _isLoadingNews = false;
        });
      }
    }
  }

  Future<void> _loadTasks() async {
    setState(() {
      _isLoadingTasks = true;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/tasks');
      if (response.data['success'] == true) {
        final tasks = response.data['data'] as List<dynamic>;
        final completedTasks = tasks.where((task) {
          final status = task['status']?.toString().toLowerCase();
          return status == 'completed' || status == 'verified';
        }).length;

        setState(() {
          _tasks = tasks;
          _totalTasksCount = tasks.length;
          _completedTasksCount = completedTasks;
        });
      }
    } catch (_) {}
    finally {
      setState(() {
        _isLoadingTasks = false;
      });
    }
  }

  Future<void> _completeTask(int taskId, String notes) async {
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.put('/tasks/$taskId/complete', data: {'notes': notes});
      if (response.data['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Task completed successfully! 3 campaign points awarded.'), backgroundColor: Color(0xFF4361EE)),
        );
        _incrementPoints(3);
        _loadTasks();
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Error completing task.'), backgroundColor: Colors.red),
      );
    }
  }

  void _initProfileFields() {
    final storage = ref.read(storageServiceProvider);
    _profileNameController.text = storage.getUserName() ?? '';
    _profilePhoneController.text = storage.getUserPhone() ?? '';
    _profileSelectedLgaId = storage.getLgaId();
    _profileSelectedWardId = storage.getWardId();
    _profileSelectedPuId = storage.getPollingUnitId();
    if (_profileSelectedLgaId != null) {
      _loadProfileWards(_profileSelectedLgaId!);
    }
    if (_profileSelectedWardId != null) {
      _loadProfilePollingUnits(_profileSelectedWardId!);
    }
  }

  Future<void> _loadLgas() async {
    setState(() {
      _isLoadingGeo = true;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/lgas');
      debugPrint('[DashboardLGA] Response type: ${response.data.runtimeType}');
      final data = response.data is Map ? response.data : <String, dynamic>{};
      if (data['success'] == true && data['data'] is List) {
        setState(() {
          _lgas = data['data'];
        });
        debugPrint('[DashboardLGA] Loaded ${_lgas.length} LGAs');
        if (_lgas.isNotEmpty) {
          debugPrint('[DashboardLGA] First item id type: ${_lgas.first['id'].runtimeType}, value: ${_lgas.first['id']}');
        }
      }
    } catch (e) {
      debugPrint('[DashboardLGA] Error loading LGAs: $e');
    }
    finally {
      setState(() {
        _isLoadingGeo = false;
      });
    }
  }

  Future<void> _registerFcmToken() async {
    try {
      await ref.read(notificationServiceProvider).initialize();
    } catch (_) {
      // Fail silently in development/test environments
    }
  }

  Future<void> _approveMember(String memberId) async {
    try {
      final apiClient = ref.read(apiClientProvider);
      final response = await apiClient.post('/members/$memberId/approve');
      if (response.data['success'] == true) {
        await _loadMembers();
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Member registration approved successfully!'), backgroundColor: Color(0xFF3451DB)),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Error approving member registration.'), backgroundColor: Colors.red),
      );
    }
  }

  Future<void> _rejectMember(String memberId) async {
    try {
      final apiClient = ref.read(apiClientProvider);
      final response = await apiClient.post('/members/$memberId/reject');
      if (response.data['success'] == true) {
        await _loadMembers();
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Member registration rejected.'), backgroundColor: Colors.orange),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Error rejecting member registration.'), backgroundColor: Colors.red),
      );
    }
  }

  // API Call handlers
  Future<void> _syncLocation() async {
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
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('GPS Location ping successfully synchronized to Campaign HQ!'),
              backgroundColor: Color(0xFF3451DB),
            ),
          );
        }
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Location permissions are required to sync coordinates.'),
            backgroundColor: Colors.orange,
          ),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Could not sync location. Verify API server is running.'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  Future<void> _loadNotifications() async {
    setState(() => _isLoadingNotifications = true);
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/notifications');
      if (response.data['success'] == true) {
        final items = response.data['data'] as List<dynamic>;
        setState(() {
          _notifications = items;
          _unreadCount = items.where((n) => n['read_at'] == null).length;
        });
      }
    } catch (_) {}
    finally {
      setState(() => _isLoadingNotifications = false);
    }
  }

  Future<void> _loadUserProfile() async {
    final storage = ref.read(storageServiceProvider);
    final userId = storage.getUserId();
    if (userId == null) return;

    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/members/$userId');
      if (response.data['success'] == true) {
        final userData = response.data['data'];
        
        setState(() {
          _resolvedUserName = userData['name']?.toString();
          _resolvedUserRole = (userData['roles'] as List?)?.firstOrNull?['name']?.toString() ?? 'Volunteer';
          _resolvedLgaName = userData['lga']?['name']?.toString();
          _resolvedWardName = userData['ward']?['name']?.toString();
          _points = userData['points'] ?? 0;
        });

        // Sync with storage
        final token = storage.getToken() ?? '';
        await storage.saveSession(
          token: token,
          name: userData['name'] ?? '',
          phone: userData['phone'] ?? '',
          role: _resolvedUserRole ?? 'Volunteer',
          userId: _toInt(userData['id']) ?? 0,
          isPhoneVerified: true,
          lgaId: _toInt(userData['lga_id']),
          wardId: _toInt(userData['ward_id']),
          puId: _toInt(userData['polling_unit_id']),
        );
      }
    } catch (_) {}
  }

  Future<void> _loadEvents() async {
    setState(() => _isLoadingEvents = true);
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/events');
      if (response.data['success'] == true) {
        setState(() {
          _events = response.data['data'];
        });
      }
    } catch (_) {}
    finally {
      if (mounted) setState(() => _isLoadingEvents = false);
    }
  }

  Future<void> _loadMedia() async {
    setState(() => _isLoadingMedia = true);
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/media');
      if (response.data['success'] == true) {
        setState(() {
          _mediaList = response.data['data'];
        });
      }
    } catch (_) {}
    finally {
      if (mounted) setState(() => _isLoadingMedia = false);
    }
  }

  Future<void> _markNotificationRead(String id) async {
    try {
      final client = ref.read(apiClientProvider);
      await client.post('/notifications/$id/read');
      await _loadNotifications();
    } catch (_) {}
  }

  Future<void> _markAllNotificationsRead() async {
    try {
      final client = ref.read(apiClientProvider);
      await client.post('/notifications/read-all');
      await _loadNotifications();
    } catch (_) {}
  }

  void _showNotificationsSheet() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => StatefulBuilder(
        builder: (context, setSheetState) {
          return DraggableScrollableSheet(
            initialChildSize: 0.7,
            minChildSize: 0.4,
            maxChildSize: 0.95,
            builder: (context, scrollController) {
              return Container(
                decoration: const BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
                ),
                child: Column(
                  children: [
                    // Handle bar
                    Container(
                      margin: const EdgeInsets.symmetric(vertical: 10),
                      width: 40,
                      height: 4,
                      decoration: BoxDecoration(
                        color: const Color(0xFFCED4E0),
                        borderRadius: BorderRadius.circular(2),
                      ),
                    ),
                    // Header
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 4),
                      child: Row(
                        children: [
                          const Icon(Icons.notifications_rounded, color: Color(0xFF4361EE), size: 20),
                          const SizedBox(width: 8),
                          const Text(
                            'Notifications',
                            style: TextStyle(
                              fontFamily: 'Inter',
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                              color: Color(0xFF1A1A1A),
                            ),
                          ),
                          if (_unreadCount > 0) ...
                            [
                              const SizedBox(width: 8),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                decoration: BoxDecoration(
                                  color: const Color(0xFFFFEBEB),
                                  borderRadius: BorderRadius.circular(99),
                                ),
                                child: Text(
                                  '$_unreadCount unread',
                                  style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.red),
                                ),
                              ),
                            ],
                          const Spacer(),
                          if (_unreadCount > 0)
                            TextButton(
                              onPressed: () async {
                                await _markAllNotificationsRead();
                                setSheetState(() {});
                              },
                              child: const Text('Mark all read', style: TextStyle(color: Color(0xFF4361EE), fontSize: 12)),
                            ),
                        ],
                      ),
                    ),
                    const Divider(height: 1),
                    // List
                    Expanded(
                      child: _isLoadingNotifications
                          ? const Center(child: CircularProgressIndicator(color: Color(0xFF4361EE)))
                          : _notifications.isEmpty
                              ? const Center(
                                  child: Column(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      Icon(Icons.notifications_off_outlined, size: 48, color: Color(0xFFCED4E0)),
                                      SizedBox(height: 12),
                                      Text('No notifications yet.', style: TextStyle(color: Color(0xFF6B7280), fontFamily: 'Inter')),
                                    ],
                                  ),
                                )
                              : ListView.separated(
                                  controller: scrollController,
                                  itemCount: _notifications.length,
                                  separatorBuilder: (_, __) => const Divider(height: 1, indent: 64),
                                  itemBuilder: (context, index) {
                                    final notif = _notifications[index];
                                    final data = notif['data'] as Map<String, dynamic>? ?? {};
                                    final type = data['type'] ?? 'general';
                                    final isUnread = notif['read_at'] == null;

                                    // Type-aware icon and colour
                                    IconData icon;
                                    Color iconBg;
                                    Color iconColor;
                                    switch (type) {
                                      case 'task_assigned':
                                        icon = Icons.assignment_outlined;
                                        iconBg = const Color(0xFFFFF3E0);
                                        iconColor = const Color(0xFFF57C00);
                                        break;
                                      case 'task_completed':
                                        icon = Icons.check_circle_outline;
                                        iconBg = const Color(0xFFEFF6FF);
                                        iconColor = const Color(0xFF2563EB);
                                        break;
                                      case 'task_verified':
                                        icon = Icons.verified_outlined;
                                        iconBg = const Color(0xFFECFDF5);
                                        iconColor = const Color(0xFF059669);
                                        break;
                                      default:
                                        icon = Icons.info_outline;
                                        iconBg = const Color(0xFFF3F4F6);
                                        iconColor = const Color(0xFF6B7280);
                                    }

                                    return ListTile(
                                      tileColor: isUnread ? const Color(0xFFF0FDF4) : Colors.white,
                                      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
                                      leading: Container(
                                        width: 40,
                                        height: 40,
                                        decoration: BoxDecoration(color: iconBg, shape: BoxShape.circle),
                                        child: Icon(icon, size: 20, color: iconColor),
                                      ),
                                      title: Text(
                                        data['title'] ?? 'Notification',
                                        style: TextStyle(
                                          fontFamily: 'Inter',
                                          fontSize: 13,
                                          fontWeight: isUnread ? FontWeight.bold : FontWeight.normal,
                                          color: const Color(0xFF1A1A1A),
                                        ),
                                      ),
                                      subtitle: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          const SizedBox(height: 2),
                                          Text(
                                            data['body'] ?? '',
                                            style: const TextStyle(fontFamily: 'Inter', fontSize: 12, color: Color(0xFF6B7280)),
                                            maxLines: 2,
                                            overflow: TextOverflow.ellipsis,
                                          ),
                                        ],
                                      ),
                                      trailing: isUnread
                                          ? Container(
                                              width: 8,
                                              height: 8,
                                              decoration: const BoxDecoration(color: Color(0xFF4361EE), shape: BoxShape.circle),
                                            )
                                          : null,
                                      onTap: () async {
                                        if (isUnread) {
                                          await _markNotificationRead(notif['id']);
                                          setSheetState(() {});
                                        }
                                      },
                                    );
                                  },
                                ),
                    ),
                  ],
                ),
              );
            },
          );
        },
      ),
    ).then((_) => _loadNotifications());
  }

  void _showIncidentDialog() {
    final descController = TextEditingController();
    String type = 'violence';
    String urgency = 'medium';

    showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              title: const Text('Report Critical Incident', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold)),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    DropdownButtonFormField<String>(
                      value: type,
                      decoration: const InputDecoration(labelText: 'Incident Type'),
                      items: const [
                        DropdownMenuItem(value: 'violence', child: Text('Violence')),
                        DropdownMenuItem(value: 'ballot stuffing', child: Text('Ballot Stuffing')),
                        DropdownMenuItem(value: 'INEC official misconduct', child: Text('INEC Misconduct')),
                        DropdownMenuItem(value: 'result falsification', child: Text('Result Falsification')),
                        DropdownMenuItem(value: 'other', child: Text('Other')),
                      ],
                      onChanged: (val) {
                        if (val != null) {
                          setDialogState(() => type = val);
                        }
                      },
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<String>(
                      value: urgency,
                      decoration: const InputDecoration(labelText: 'Urgency Level'),
                      items: const [
                        DropdownMenuItem(value: 'low', child: Text('Low')),
                        DropdownMenuItem(value: 'medium', child: Text('Medium')),
                        DropdownMenuItem(value: 'high', child: Text('High')),
                        DropdownMenuItem(value: 'critical', child: Text('Critical')),
                      ],
                      onChanged: (val) {
                        if (val != null) {
                          setDialogState(() => urgency = val);
                        }
                      },
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: descController,
                      decoration: const InputDecoration(labelText: 'Details / Description'),
                      maxLines: 2,
                    ),
                  ],
                ),
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Cancel'),
                ),
                ElevatedButton(
                  onPressed: () async {
                    try {
                      final apiClient = ref.read(apiClientProvider);
                      final response = await apiClient.post('/incidents', data: {
                        'type': type,
                        'urgency': urgency,
                        'description': descController.text.trim(),
                        'lat': 6.22,
                        'lng': 7.01,
                      });

                      if (response.data['success'] == true) {
                        Navigator.pop(context);
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Incident reported and escalated to Admin HQ.'),
                            backgroundColor: Colors.redAccent,
                          ),
                        );
                      }
                    } catch (e) {
                      Navigator.pop(context);
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text('Connection failed. Could not upload report.'),
                          backgroundColor: Colors.red,
                        ),
                      );
                    }
                  },
                  style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                  child: const Text('Submit Report', style: TextStyle(color: Colors.white)),
                ),
              ],
            );
          },
        );
      },
    );
  }

  void _incrementPoints(int value) {
    setState(() {
      _points += value;
    });
  }



  Map<String, String> _normalizeMember(dynamic member) {
    final data = Map<String, dynamic>.from(member as Map);
    final pollingUnit = _extractNestedMap(data, ['pollingUnit', 'polling_unit']) ?? <String, dynamic>{};

    return {
      'id': data['id']?.toString() ?? '',
      'name': data['name']?.toString() ?? 'Unnamed Member',
      'status': _normalizeStatus(data['status']),
      'pu': pollingUnit['code']?.toString() ?? pollingUnit['name']?.toString() ?? data['polling_unit_id']?.toString() ?? 'N/A',
    };
  }

  Map<String, dynamic> _normalizeNews(dynamic news) {
    final data = Map<String, dynamic>.from(news as Map);
    return {
      'id': data['id']?.toString() ?? '',
      'title': data['title']?.toString() ?? 'Untitled News',
      'body': data['body']?.toString() ?? '',
      'category': data['category']?.toString() ?? 'General',
      'published_at': data['published_at']?.toString() ?? '',
    };
  }

  Map<String, dynamic>? _extractNestedMap(Map<String, dynamic> data, List<String> keys) {
    for (final key in keys) {
      final value = data[key];
      if (value is Map) {
        return Map<String, dynamic>.from(value);
      }
    }
    return null;
  }

  String _normalizeStatus(dynamic status) {
    final value = status?.toString().trim();
    if (value == null || value.isEmpty) {
      return 'Pending';
    }

    switch (value.toLowerCase()) {
      case 'active':
        return 'Active';
      case 'verified':
        return 'Verified';
      case 'pending':
        return 'Pending';
      case 'inactive':
        return 'Inactive';
      default:
        return value;
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);

    return Scaffold(
      backgroundColor: const Color(0xFFF6FBF4),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 0,
        shape: const Border(bottom: BorderSide(color: Color(0xFFCED4E0), width: 0.5)),
        title: Row(
          children: [
            const Icon(Icons.account_balance, color: Color(0xFF3451DB)),
            const SizedBox(width: 8),
            Text(
              'Onyendozi Connect',
              style: TextStyle(
                fontFamily: 'Inter',
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: const Color(0xFF3451DB),
              ),
            ),
          ],
        ),
        actions: [
          // Notification Bell with live unread badge
          IconButton(
            icon: Stack(
              children: [
                const Icon(Icons.notifications, color: Color(0xFF64748B)),
                if (_unreadCount > 0)
                  Positioned(
                    right: 0,
                    top: 0,
                    child: Container(
                      padding: const EdgeInsets.all(1),
                      decoration: BoxDecoration(
                        color: Colors.red,
                        borderRadius: BorderRadius.circular(6),
                      ),
                      constraints: const BoxConstraints(
                        minWidth: 12,
                        minHeight: 12,
                      ),
                      child: Text(
                        _unreadCount > 99 ? '99+' : '$_unreadCount',
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 8,
                          fontWeight: FontWeight.bold,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    ),
                  ),
              ],
            ),
            onPressed: () => _showNotificationsSheet(),
          ),
          IconButton(
            icon: const Icon(Icons.logout, color: Color(0xFF64748B)),
            onPressed: () => ref.read(authProvider.notifier).logout(),
          )
        ],
      ),
      body: IndexedStack(
        index: _currentIndex,
        children: [
          _buildHomeTab(authState),
          _buildMembersTab(authState),
          CanvassingScreen(onPointsIncremented: () => _incrementPoints(2)),
          _buildMediaTab(),
          _buildProfileTab(authState),
        ],
      ),
      bottomNavigationBar: Container(
        decoration: const BoxDecoration(
          border: Border(top: BorderSide(color: Color(0xFFCED4E0), width: 0.5)),
        ),
        child: NavigationBarTheme(
          data: NavigationBarThemeData(
            indicatorColor: const Color(0xFFBBD4FF),
            labelTextStyle: WidgetStateProperty.all(
              const TextStyle(fontFamily: 'Inter', fontSize: 12, fontWeight: FontWeight.bold),
            ),
          ),
          child: NavigationBar(
            selectedIndex: _currentIndex,
            onDestinationSelected: (index) {
              setState(() {
                _currentIndex = index;
              });
            },
            backgroundColor: Colors.white,
            destinations: const [
              NavigationDestination(
                icon: Icon(Icons.dashboard_outlined),
                selectedIcon: Icon(Icons.dashboard, color: Color(0xFF3451DB)),
                label: 'Home',
              ),
              NavigationDestination(
                icon: Icon(Icons.group_outlined),
                selectedIcon: Icon(Icons.group, color: Color(0xFF3451DB)),
                label: 'Members',
              ),
              NavigationDestination(
                icon: Icon(Icons.edit_location_alt_outlined),
                selectedIcon: Icon(Icons.edit_location_alt, color: Color(0xFF3451DB)),
                label: 'Canvass',
              ),
              NavigationDestination(
                icon: Icon(Icons.newspaper_outlined),
                selectedIcon: Icon(Icons.newspaper, color: Color(0xFF3451DB)),
                label: 'Media',
              ),
              NavigationDestination(
                icon: Icon(Icons.person_outline),
                selectedIcon: Icon(Icons.person, color: Color(0xFF3451DB)),
                label: 'Profile',
              ),
            ],
          ),
        ),
      ),
    );
  }

  // --- HOME TAB ---
  Widget _buildHomeTab(AuthState authState) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Welcome Card
          Container(
            padding: const EdgeInsets.all(24.0),
            decoration: BoxDecoration(
              color: const Color(0xFF4361EE),
              borderRadius: BorderRadius.circular(16),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.05),
                  blurRadius: 4,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Stack(
              children: [
                Positioned(
                  right: 0,
                  top: 0,
                  child: Opacity(
                    opacity: 0.1,
                    child: const Icon(Icons.shield, size: 80, color: Colors.white),
                  ),
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'FIELD AGENT DASHBOARD',
                      style: TextStyle(
                        fontFamily: 'Inter',
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFFBBD4FF),
                        letterSpacing: 0.05,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Welcome, ${_resolvedUserName ?? authState.userName ?? 'Agent Chinedu'}',
                      style: const TextStyle(
                        fontFamily: 'Inter',
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                    const SizedBox(height: 12),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(999),
                        border: Border.all(color: Colors.white.withOpacity(0.2)),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          const Icon(Icons.map, size: 14, color: Colors.white),
                          const SizedBox(width: 4),
                          Text(
                            (_resolvedWardName != null && _resolvedLgaName != null)
                                ? '${_resolvedWardName}, ${_resolvedLgaName}'
                                : 'No Ward/LGA assigned',
                            style: const TextStyle(
                              fontFamily: 'Inter',
                              fontSize: 14,
                              color: Colors.white,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: 12),

          // Live Tracking Switch
          Container(
            padding: const EdgeInsets.all(16.0),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
            ),
            child: Row(
              children: [
                Container(
                  width: 40,
                  height: 40,
                  decoration: const BoxDecoration(
                    color: Color(0xFFFF8C00),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(Icons.near_me, color: Color(0xFF1A237E)),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Live Tracking',
                        style: TextStyle(
                          fontFamily: 'Inter',
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      Text(
                        _isTracking ? 'Broadcasting coordinates' : 'Tracking disabled',
                        style: const TextStyle(
                          fontFamily: 'Inter',
                          fontSize: 14,
                          color: Color(0xFF64748B),
                        ),
                      ),
                    ],
                  ),
                ),
                Switch(
                  value: _isTracking,
                  onChanged: (value) {
                    setState(() {
                      _isTracking = value;
                    });
                    if (value) {
                      _syncLocation();
                    }
                  },
                  activeColor: const Color(0xFF3451DB),
                ),
              ],
            ),
          ),
          const SizedBox(height: 12),

          // Stats Bento Box
          Row(
            children: [
              Expanded(
                child: Container(
                  height: 120,
                  padding: const EdgeInsets.all(16.0),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                    border: const Border(
                      left: BorderSide(color: Color(0xFF4361EE), width: 4),
                      top: BorderSide(color: Color(0xFFCED4E0), width: 0.5),
                      right: BorderSide(color: Color(0xFFCED4E0), width: 0.5),
                      bottom: BorderSide(color: Color(0xFFCED4E0), width: 0.5),
                    ),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'TOTAL RECRUITED',
                        style: TextStyle(
                          fontFamily: 'Inter',
                          fontSize: 11,
                          fontWeight: FontWeight.bold,
                          color: Color(0xFF64748B),
                        ),
                      ),
                      Row(
                        textBaseline: TextBaseline.alphabetic,
                        crossAxisAlignment: CrossAxisAlignment.baseline,
                        children: [
                          Text(
                            '$_totalMembers',
                            style: TextStyle(
                              fontFamily: 'Inter',
                              fontSize: 32,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF3451DB),
                            ),
                          ),
                          const SizedBox(width: 4),
                          const Icon(Icons.trending_up, size: 16, color: Color(0xFF3451DB)),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Container(
                  height: 120,
                  padding: const EdgeInsets.all(16.0),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                    border: const Border(
                      left: BorderSide(color: Color(0xFFFF8C00), width: 4),
                      top: BorderSide(color: Color(0xFFCED4E0), width: 0.5),
                      right: BorderSide(color: Color(0xFFCED4E0), width: 0.5),
                      bottom: BorderSide(color: Color(0xFFCED4E0), width: 0.5),
                    ),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'TASKS DONE',
                        style: TextStyle(
                          fontFamily: 'Inter',
                          fontSize: 11,
                          fontWeight: FontWeight.bold,
                          color: Color(0xFF64748B),
                        ),
                      ),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                '$_completedTasksCount/$_totalTasksCount',
                                style: const TextStyle(
                                  fontFamily: 'Inter',
                                  fontSize: 22,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              Text(
                                _totalTasksCount == 0
                                    ? '0%'
                                    : '${((_completedTasksCount / _totalTasksCount) * 100).round()}%',
                                style: TextStyle(
                                  fontFamily: 'Inter',
                                  fontSize: 14,
                                  fontWeight: FontWeight.bold,
                                  color: const Color(0xFFE65100),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 6),
                          ClipRRect(
                            borderRadius: BorderRadius.circular(999),
                            child: LinearProgressIndicator(
                              value: _totalTasksCount == 0 ? 0 : _completedTasksCount / _totalTasksCount,
                              backgroundColor: Color(0xFFE8EDFF),
                              valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF3451DB)),
                              minHeight: 6,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),

          // Doors Knocked full width banner
          Container(
            padding: const EdgeInsets.all(16.0),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
            ),
            child: Row(
              children: [
                Container(
                  width: 48,
                  height: 48,
                  decoration: const BoxDecoration(
                    color: Color(0xFFE8EDFF),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(Icons.door_front_door, color: Color(0xFF3451DB)),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'DOORS KNOCKED',
                        style: TextStyle(
                          fontFamily: 'Inter',
                          fontSize: 11,
                          fontWeight: FontWeight.bold,
                          color: Color(0xFF64748B),
                        ),
                      ),
                      Text(
                        '${ref.watch(canvassingProvider).logs.length} Properties',
                        style: const TextStyle(
                          fontFamily: 'Inter',
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ],
                  ),
                ),
                const Icon(Icons.chevron_right, color: Color(0xFF94A3B8)),
              ],
            ),
          ),
          const SizedBox(height: 12),

          // Next Event Card
          _isLoadingEvents
              ? const Card(
                  child: Padding(
                    padding: EdgeInsets.all(16.0),
                    child: Center(child: CircularProgressIndicator()),
                  ),
                )
              : _events.isEmpty
                  ? Container(
                      padding: const EdgeInsets.all(16.0),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                      ),
                      child: const Center(
                        child: Text(
                          'No upcoming events scheduled.',
                          style: TextStyle(fontFamily: 'Inter', color: Colors.grey),
                        ),
                      ),
                    )
                  : Container(
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                      decoration: BoxDecoration(
                                        color: const Color(0xFFFBBC00).withOpacity(0.2),
                                        borderRadius: BorderRadius.circular(999),
                                      ),
                                      child: const Text(
                                        'Next Event',
                                        style: TextStyle(
                                          fontFamily: 'Inter',
                                          fontSize: 11,
                                          fontWeight: FontWeight.bold,
                                          color: Color(0xFFE65100),
                                        ),
                                      ),
                                    ),
                                    Text(
                                      _formatEventDate(_events.first['date']),
                                      style: const TextStyle(
                                        fontFamily: 'Inter',
                                        fontSize: 12,
                                        color: Color(0xFF64748B),
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 12),
                                Text(
                                  _events.first['title'] ?? 'Campaign Event',
                                  style: const TextStyle(
                                    fontFamily: 'Inter',
                                    fontSize: 18,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                const SizedBox(height: 6),
                                Row(
                                  children: [
                                    const Icon(Icons.location_on, size: 14, color: Color(0xFF64748B)),
                                    const SizedBox(width: 4),
                                    Text(
                                      _events.first['venue'] ?? 'Venue TBD',
                                      style: const TextStyle(
                                        fontFamily: 'Inter',
                                        fontSize: 14,
                                        color: Color(0xFF64748B),
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                          Container(
                            color: const Color(0xFFF1F5EE),
                            padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Row(
                                  children: [
                                    const CircleAvatar(
                                      radius: 12,
                                      backgroundColor: Color(0xFF4361EE),
                                      child: Text('C', style: TextStyle(color: Colors.white, fontSize: 10)),
                                    ),
                                    const SizedBox(width: 4),
                                    const CircleAvatar(
                                      radius: 12,
                                      backgroundColor: Colors.blue,
                                      child: Text('E', style: TextStyle(color: Colors.white, fontSize: 10)),
                                    ),
                                    const SizedBox(width: 4),
                                    const CircleAvatar(
                                      radius: 12,
                                      backgroundColor: Colors.orange,
                                      child: Text('A', style: TextStyle(color: Colors.white, fontSize: 10)),
                                    ),
                                    const SizedBox(width: 6),
                                    Text(
                                      '+${_events.first['attendances']?.length ?? 12} attendees',
                                      style: const TextStyle(fontFamily: 'Inter', fontSize: 12, fontWeight: FontWeight.w500),
                                    ),
                                  ],
                                ),
                                TextButton(
                                  onPressed: () => _showEventDetailsDialog(_events.first),
                                  child: const Text(
                                    'VIEW DETAILS',
                                    style: TextStyle(
                                      fontFamily: 'Inter',
                                      fontSize: 12,
                                      fontWeight: FontWeight.bold,
                                      color: Color(0xFF3451DB),
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
          const SizedBox(height: 16),

          // Today's Tasks Section
          const Text(
            "TODAY'S TASKS",
            style: TextStyle(
              fontFamily: 'Inter',
              fontSize: 12,
              fontWeight: FontWeight.bold,
              color: Color(0xFF64748B),
              letterSpacing: 0.05,
            ),
          ),
          const SizedBox(height: 12),
          _isLoadingTasks
              ? const Center(child: CircularProgressIndicator())
              : _tasks.isEmpty
                  ? Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                      ),
                      child: const Center(
                        child: Text(
                          'No tasks assigned for today.',
                          style: TextStyle(fontFamily: 'Inter', color: Color(0xFF94A3B8)),
                        ),
                      ),
                    )
                  : ListView.separated(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: _tasks.length,
                      separatorBuilder: (context, index) => const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        final task = _tasks[index];
                        final status = task['status'] ?? 'pending';
                        final taskId = task['id'];
                        final isOverdue = task['deadline'] != null && DateTime.parse(task['deadline']).isBefore(DateTime.now()) && status != 'verified';
                        
                        return Container(
                          padding: const EdgeInsets.all(16.0),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.stretch,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Expanded(
                                    child: Text(
                                      task['title'] ?? 'Task Title',
                                      style: const TextStyle(fontFamily: 'Inter', fontSize: 16, fontWeight: FontWeight.bold),
                                    ),
                                  ),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: status == 'verified'
                                          ? const Color(0xFF3451DB).withOpacity(0.1)
                                          : status == 'completed'
                                              ? Colors.blue.withOpacity(0.1)
                                              : Colors.amber.withOpacity(0.1),
                                      borderRadius: BorderRadius.circular(4),
                                    ),
                                    child: Text(
                                      status.toString().toUpperCase(),
                                      style: TextStyle(
                                        fontSize: 10,
                                        fontWeight: FontWeight.bold,
                                        color: status == 'verified'
                                            ? const Color(0xFF3451DB)
                                            : status == 'completed'
                                                ? Colors.blue
                                                : Colors.orange,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 8),
                              Text(
                                task['description'] ?? 'No description provided.',
                                style: const TextStyle(fontFamily: 'Inter', fontSize: 13, color: Color(0xFF64748B)),
                              ),
                              const SizedBox(height: 12),
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    'Deadline: ${task['deadline'] != null ? DateTime.parse(task['deadline']).toLocal().toString().substring(0, 10) : 'N/A'}${isOverdue ? ' (OVERDUE)' : ''}',
                                    style: TextStyle(
                                      fontFamily: 'Inter',
                                      fontSize: 12,
                                      fontWeight: FontWeight.w500,
                                      color: isOverdue ? Colors.red : const Color(0xFF94A3B8),
                                    ),
                                  ),
                                  if (status == 'pending')
                                    ElevatedButton(
                                      onPressed: () {
                                        final notesController = TextEditingController();
                                        showDialog(
                                          context: context,
                                          builder: (context) => AlertDialog(
                                            title: const Text('Complete Task'),
                                            content: TextField(
                                              controller: notesController,
                                              maxLines: 3,
                                              decoration: const InputDecoration(
                                                hintText: 'Enter completion details or notes...',
                                                border: OutlineInputBorder(),
                                              ),
                                            ),
                                            actions: [
                                              TextButton(
                                                onPressed: () => Navigator.pop(context),
                                                child: const Text('CANCEL'),
                                              ),
                                              ElevatedButton(
                                                onPressed: () {
                                                  Navigator.pop(context);
                                                  _completeTask(taskId, notesController.text.trim());
                                                },
                                                style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF3451DB)),
                                                child: const Text('SUBMIT', style: TextStyle(color: Colors.white)),
                                              ),
                                            ],
                                          ),
                                        );
                                      },
                                      style: ElevatedButton.styleFrom(
                                        backgroundColor: const Color(0xFF3451DB),
                                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                                      ),
                                      child: const Text('COMPLETE', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                                    ),
                                ],
                              ),
                            ],
                          ),
                        );
                      },
                    ),
          const SizedBox(height: 20),

          if (_quickActionsEnabled &&
              (_quickActionRecruitVisible ||
               _quickActionResultsVisible ||
               _quickActionIncidentVisible ||
               _quickActionVoiceVisible ||
               _quickActionMembersVisible ||
               _quickActionMobilizeVisible)) ...[
            // Quick Action Grid
            const Text(
              'QUICK ACTIONS',
              style: TextStyle(
                fontFamily: 'Inter',
                fontSize: 12,
                fontWeight: FontWeight.bold,
                color: Color(0xFF64748B),
                letterSpacing: 0.05,
              ),
            ),
            const SizedBox(height: 12),
            GridView.count(
              crossAxisCount: 3,
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              crossAxisSpacing: 10,
              mainAxisSpacing: 10,
              childAspectRatio: 1.1,
              children: [
                if (_quickActionRecruitVisible)
                  _buildQuickActionButton(
                    icon: Icons.person_add_alt_1_rounded,
                    label: 'Recruit',
                    color: const Color(0xFF4361EE),
                    onTap: () async {
                      final result = await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const VolunteerRecruitmentScreen(),
                        ),
                      );
                      if (result == true) {
                        _loadMembers();
                      }
                    },
                  ),
                if (_quickActionResultsVisible)
                  _buildQuickActionButton(
                    icon: Icons.ballot,
                    label: 'Results',
                    color: const Color(0xFF3451DB),
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const ElectionOpsScreen(initialMode: 'results'),
                        ),
                      );
                    },
                  ),
                if (_quickActionIncidentVisible)
                  _buildQuickActionButton(
                    icon: Icons.report_problem,
                    label: 'Incident',
                    color: Colors.red,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const ElectionOpsScreen(initialMode: 'incident'),
                        ),
                      );
                    },
                  ),
                if (_quickActionVoiceVisible)
                  _buildQuickActionButton(
                    icon: Icons.mic,
                    label: 'Voice',
                    color: const Color(0xFF3451DB),
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const ElectionOpsScreen(initialMode: 'voice'),
                        ),
                      );
                    },
                  ),
                if (_quickActionMembersVisible)
                  _buildQuickActionButton(
                    icon: Icons.group,
                    label: 'Members',
                    color: const Color(0xFF3451DB),
                    onTap: () {
                      setState(() {
                        _currentIndex = 1;
                      });
                    },
                  ),
                if (_quickActionMobilizeVisible)
                  _buildQuickActionButton(
                    icon: Icons.forum,
                    label: 'Mobilize',
                    color: const Color(0xFF4361EE),
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const CommunicationsScreen(),
                        ),
                      );
                    },
                  ),
                _buildQuickActionButton(
                  icon: Icons.newspaper,
                  label: 'News',
                  color: const Color(0xFF3451DB),
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => const NewsScreen(),
                      ),
                    );
                  },
                ),
              ],
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildQuickActionButton({
    required IconData icon,
    required String label,
    required Color color,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: color, size: 28),
            const SizedBox(height: 8),
            Text(
              label,
              style: const TextStyle(
                fontFamily: 'Inter',
                fontSize: 12,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }

  // --- MEMBERS TAB ---
  Widget _buildMembersTab(AuthState authState) {
    final filteredMembers = _membersList.where((m) {
      if (_selectedMemberFilter == 'Verified') return m['status'] == 'Verified' || m['status'] == 'Active';
      if (_selectedMemberFilter == 'Pending') return m['status'] == 'Pending';
      return true;
    }).toList();

    final isCoordinator = authState.userRole?.toLowerCase().contains('coordinator') == true ||
        authState.userRole?.toLowerCase().contains('admin') == true;

    return Stack(
      children: [
    SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Recruitment Target header
          Container(
            padding: const EdgeInsets.all(16.0),
            decoration: BoxDecoration(
              color: const Color(0xFFE8EDFF),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'WARD 04 RECRUITMENT TARGET',
                      style: TextStyle(
                        fontFamily: 'Inter',
                        fontSize: 11,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF64748B),
                      ),
                    ),
                    Text(
                      '85%',
                      style: TextStyle(
                        fontFamily: 'Inter',
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF3451DB),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                Text(
                  '$_totalMembers / 1000 Members',
                  style: TextStyle(
                    fontFamily: 'Inter',
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    color: const Color(0xFF3451DB),
                  ),
                ),
                const SizedBox(height: 8),
                ClipRRect(
                  borderRadius: BorderRadius.circular(999),
                  child: LinearProgressIndicator(
                    value: (_totalMembers / 1000).clamp(0.0, 1.0),
                    backgroundColor: Color(0xFFDFE4DD),
                    valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF3451DB)),
                    minHeight: 8,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  '${(1000 - _totalMembers).clamp(0, 1000)} more members needed to reach goal.',
                  style: const TextStyle(
                    fontFamily: 'Inter',
                    fontSize: 12,
                    color: Color(0xFF64748B),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),

          // Search Field
          TextField(
            decoration: InputDecoration(
              hintText: 'Search members in Ward 04',
              prefixIcon: const Icon(Icons.search, color: Color(0xFF94A3B8)),
              filled: true,
              fillColor: Colors.white,
              contentPadding: const EdgeInsets.symmetric(vertical: 12),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(999),
                borderSide: const BorderSide(color: Color(0xFFCED4E0), width: 0.5),
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(999),
                borderSide: const BorderSide(color: Color(0xFFCED4E0), width: 0.5),
              ),
            ),
          ),
          const SizedBox(height: 12),

          // Horizontal filters
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: ['Verified', 'Pending', 'Active', 'Inactive'].map((filter) {
                final isSelected = _selectedMemberFilter == filter;
                return Padding(
                  padding: const EdgeInsets.only(right: 8.0),
                  child: ChoiceChip(
                    label: Text(filter),
                    selected: isSelected,
                    onSelected: (selected) {
                      setState(() {
                        _selectedMemberFilter = filter;
                      });
                    },
                    selectedColor: const Color(0xFFBBD4FF),
                    backgroundColor: Colors.white,
                    labelStyle: TextStyle(
                      fontFamily: 'Inter',
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                      color: isSelected ? const Color(0xFF1A237E) : const Color(0xFF64748B),
                    ),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(999),
                      side: BorderSide(
                        color: isSelected ? const Color(0xFF3451DB) : const Color(0xFFCED4E0),
                        width: 0.5,
                      ),
                    ),
                  ),
                );
              }).toList(),
            ),
          ),
          const SizedBox(height: 16),

          // Members list
          if (_isLoadingMembers)
            const Padding(
              padding: EdgeInsets.symmetric(vertical: 32),
              child: Center(child: CircularProgressIndicator(color: Color(0xFF3451DB))),
            )
          else if (filteredMembers.isEmpty)
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
              ),
              child: const Text(
                'No members returned from the API yet.',
                style: TextStyle(fontFamily: 'Inter', color: Color(0xFF94A3B8)),
              ),
            )
          else
            ListView.separated(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: filteredMembers.length,
              separatorBuilder: (context, index) => const SizedBox(height: 12),
              itemBuilder: (context, index) {
                final member = filteredMembers[index];
                final isVerified = member['status'] == 'Verified' || member['status'] == 'Active';
                final showApprovalButtons = isCoordinator && member['status'] == 'Pending';

                return Container(
                  padding: const EdgeInsets.all(16.0),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                  ),
                  child: Row(
                    children: [
                      Container(
                        width: 4,
                        height: 40,
                        decoration: BoxDecoration(
                          color: isVerified ? const Color(0xFF3451DB) : const Color(0xFFFF8C00),
                          borderRadius: BorderRadius.circular(999),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                Text(
                                  member['name']!,
                                  style: const TextStyle(
                                    fontFamily: 'Inter',
                                    fontSize: 16,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                if (isVerified) ...[
                                  const SizedBox(width: 4),
                                  const Icon(Icons.verified, size: 16, color: Color(0xFF3451DB)),
                                ],
                              ],
                            ),
                            const SizedBox(height: 4),
                            Row(
                              children: [
                                const Text(
                                  'PU CODE: ',
                                  style: TextStyle(
                                    fontFamily: 'Inter',
                                    fontSize: 11,
                                    color: Color(0xFF64748B),
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                Text(
                                  member['pu']!,
                                  style: TextStyle(
                                    fontFamily: 'Inter',
                                    fontSize: 12,
                                    fontWeight: FontWeight.bold,
                                    color: const Color(0xFF3451DB),
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                      if (showApprovalButtons) ...[
                        IconButton(
                          icon: const Icon(Icons.check_circle, color: Color(0xFF3451DB)),
                          onPressed: () => _approveMember(member['id']!),
                        ),
                        IconButton(
                          icon: const Icon(Icons.cancel, color: Colors.red),
                          onPressed: () => _rejectMember(member['id']!),
                        ),
                      ] else ...[
                        IconButton(
                          icon: const Icon(Icons.phone, color: Color(0xFF3451DB)),
                          onPressed: () {},
                        ),
                        IconButton(
                          icon: const Icon(Icons.chat, color: Color(0xFF3451DB)),
                          onPressed: () {},
                        ),
                      ]
                    ],
                  ),
                );
              },
            ),
        ],
      ),
    ),
    // Recruit Volunteer FAB
    Positioned(
      bottom: 16,
      right: 16,
      child: FloatingActionButton.extended(
        heroTag: 'recruit_fab',
        onPressed: () async {
          final result = await Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => const VolunteerRecruitmentScreen(),
            ),
          );
          if (result == true) {
            _loadMembers();
          }
        },
        backgroundColor: const Color(0xFF4361EE),
        icon: const Icon(Icons.person_add_alt_1_rounded, color: Colors.white),
        label: const Text(
          'Recruit',
          style: TextStyle(
            fontFamily: 'Inter',
            fontWeight: FontWeight.bold,
            color: Colors.white,
          ),
        ),
      ),
    ),
      ],
    );
  }

  // --- MEDIA TAB ---
  bool _isPlayingAnthem = false;

  List<dynamic> get _filteredMedia {
    if (_selectedMediaCategory == 'All Media') {
      return _mediaList;
    }
    final catLower = _selectedMediaCategory.toLowerCase();
    return _mediaList.where((item) {
      final itemCat = item['category']?.toString().toLowerCase();
      if (catLower == 'jingles') return itemCat == 'jingles' || itemCat == 'jingle';
      if (catLower == 'flyers') return itemCat == 'flyers' || itemCat == 'flyer';
      if (catLower == 'documents') return itemCat == 'documents' || itemCat == 'document' || itemCat == 'pdf';
      if (catLower == 'videos') return itemCat == 'videos' || itemCat == 'video';
      return itemCat == catLower;
    }).toList();
  }

  Widget _buildMediaTab() {
    final filtered = _filteredMedia;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Breaking news hero slides
          GestureDetector(
            onTap: () {
              if (_newsList.isNotEmpty) {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => NewsDetailScreen(newsItem: _newsList[0]),
                  ),
                );
              }
            },
            child: Container(
              height: 180,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(16),
                color: const Color(0xFF3451DB),
              ),
              child: Stack(
                children: [
                  Positioned.fill(
                    child: Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          colors: [Colors.black.withOpacity(0.8), Colors.transparent],
                          begin: Alignment.bottomCenter,
                          end: Alignment.topCenter,
                        ),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: _isLoadingNews
                        ? const Center(child: CircularProgressIndicator(color: Colors.white))
                        : Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            mainAxisAlignment: MainAxisAlignment.end,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    decoration: BoxDecoration(
                                      color: const Color(0xFFFF8C00),
                                      borderRadius: BorderRadius.circular(999),
                                    ),
                                    child: const Text(
                                      'BREAKING',
                                      style: TextStyle(fontFamily: 'Inter', fontSize: 10, fontWeight: FontWeight.bold),
                                    ),
                                  ),
                                  GestureDetector(
                                    onTap: () {
                                      Navigator.push(
                                        context,
                                        MaterialPageRoute(
                                          builder: (context) => const NewsScreen(),
                                        ),
                                      );
                                    },
                                    child: const Text(
                                      'See All News',
                                      style: TextStyle(fontFamily: 'Inter', fontSize: 12, color: Colors.white70, decoration: TextDecoration.underline),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 8),
                              Text(
                                _newsList.isNotEmpty ? _newsList[0]['title'] : 'No news published yet.',
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontFamily: 'Inter',
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Horizontal category chips
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: ['All Media', 'Jingles', 'Flyers', 'Documents', 'Videos'].map((cat) {
                final isSelected = cat == _selectedMediaCategory;
                return Padding(
                  padding: const EdgeInsets.only(right: 8.0),
                  child: GestureDetector(
                    onTap: () {
                      setState(() {
                        _selectedMediaCategory = cat;
                      });
                    },
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      decoration: BoxDecoration(
                        color: isSelected ? const Color(0xFF3451DB) : const Color(0xFFE8EDFF),
                        borderRadius: BorderRadius.circular(999),
                      ),
                      child: Text(
                        cat,
                        style: TextStyle(
                          fontFamily: 'Inter',
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                          color: isSelected ? Colors.white : const Color(0xFF64748B),
                        ),
                      ),
                    ),
                  ),
                );
              }).toList(),
            ),
          ),
          const SizedBox(height: 20),

          // Audio Player Widget
          Container(
            padding: const EdgeInsets.all(16.0),
            decoration: BoxDecoration(
              color: const Color(0xFF4361EE),
              borderRadius: BorderRadius.circular(16),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.05),
                  blurRadius: 4,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Column(
              children: [
                Row(
                  children: [
                    Container(
                      width: 40,
                      height: 40,
                      decoration: const BoxDecoration(
                        color: Color(0xFFFF8C00),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.music_note, color: Colors.black),
                    ),
                    const SizedBox(width: 12),
                    const Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Official Campaign Anthem',
                            style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                          ),
                          Text(
                            'Jingle • 0:45s',
                            style: TextStyle(color: Colors.white70, fontSize: 12),
                          ),
                        ],
                      ),
                    ),
                    // Simulated visual sound wave
                    Row(
                      children: List.generate(4, (index) {
                        return Container(
                          margin: const EdgeInsets.symmetric(horizontal: 1.5),
                          width: 3,
                          height: _isPlayingAnthem ? (12.0 + (index * 4) % 12) : 4.0,
                          color: const Color(0xFFFF8C00),
                        );
                      }),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                const LinearProgressIndicator(
                  value: 0.33,
                  backgroundColor: Colors.white24,
                  valueColor: AlwaysStoppedAnimation<Color>(Color(0xFFFF8C00)),
                ),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        IconButton(
                          icon: const Icon(Icons.replay_10, color: Colors.white),
                          onPressed: () {},
                        ),
                        IconButton(
                          icon: Icon(
                            _isPlayingAnthem ? Icons.pause_circle_filled : Icons.play_circle_fill,
                            color: const Color(0xFFFF8C00),
                            size: 36,
                          ),
                          onPressed: () {
                            setState(() {
                              _isPlayingAnthem = !_isPlayingAnthem;
                            });
                          },
                        ),
                        IconButton(
                          icon: const Icon(Icons.forward_10, color: Colors.white),
                          onPressed: () {},
                        ),
                      ],
                    ),
                    ElevatedButton.icon(
                      onPressed: () {},
                      icon: const Icon(Icons.share, size: 16, color: Colors.white),
                      label: const Text('Share', style: TextStyle(color: Colors.white)),
                      style: ElevatedButton.styleFrom(backgroundColor: Colors.white24),
                    ),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // Campaign Flyers Grid section
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    _selectedMediaCategory == 'All Media' ? 'All Campaign Materials' : _selectedMediaCategory,
                    style: const TextStyle(fontFamily: 'Inter', fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  const Text(
                    'Dynamic campaign assets from our library',
                    style: TextStyle(fontFamily: 'Inter', fontSize: 12, color: Color(0xFF64748B)),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: 12),

          _isLoadingMedia
              ? const Center(child: Padding(padding: EdgeInsets.all(24.0), child: CircularProgressIndicator()))
              : filtered.isEmpty
                  ? Container(
                      height: 150,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
                      ),
                      child: const Center(
                        child: Text(
                          'No assets found in this category.',
                          style: TextStyle(fontFamily: 'Inter', color: Colors.grey),
                        ),
                      ),
                    )
                  : GridView.builder(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 2,
                        crossAxisSpacing: 12,
                        mainAxisSpacing: 12,
                        childAspectRatio: 0.75,
                      ),
                      itemCount: filtered.length,
                      itemBuilder: (context, index) {
                        return _buildMediaCard(filtered[index]);
                      },
                    ),
        ],
      ),
    );
  }

  Widget _buildMediaCard(dynamic mediaItem) {
    final category = mediaItem['category']?.toString().toUpperCase() ?? 'GENERAL';
    final name = mediaItem['name']?.toString() ?? 'Untitled Asset';
    final mimeType = mediaItem['mime_type']?.toString() ?? '';

    IconData icon = Icons.description;
    if (mimeType.startsWith('image/')) {
      icon = Icons.image;
    } else if (mimeType.startsWith('audio/')) {
      icon = Icons.audiotrack;
    } else if (mimeType.startsWith('video/')) {
      icon = Icons.movie;
    }

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFCED4E0), width: 0.5),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Expanded(
            child: Container(
              color: const Color(0xFFE8EDFF),
              child: Center(
                child: Icon(icon, size: 40, color: const Color(0xFFCED4E0)),
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  category,
                  style: const TextStyle(
                    fontFamily: 'Inter',
                    fontSize: 10,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF3451DB),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  name,
                  style: const TextStyle(
                    fontFamily: 'Inter',
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  String _formatEventDate(dynamic dateStr) {
    if (dateStr == null) return 'Date TBD';
    try {
      final dateTime = DateTime.parse(dateStr.toString());
      final months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      final month = months[dateTime.month - 1];
      final hour = dateTime.hour > 12 ? dateTime.hour - 12 : (dateTime.hour == 0 ? 12 : dateTime.hour);
      final ampm = dateTime.hour >= 12 ? 'PM' : 'AM';
      final minute = dateTime.minute.toString().padLeft(2, '0');
      return '$month ${dateTime.day}, $hour:$minute $ampm';
    } catch (_) {
      return dateStr.toString();
    }
  }

  // --- EVENTS CHECK-IN MODAL ---
  void _showEventDetailsDialog(dynamic event) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Event Details', style: TextStyle(fontWeight: FontWeight.bold, color: Color(0xFF3451DB))),
          content: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(event['title'] ?? 'Campaign Event', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
                const SizedBox(height: 8),
                Row(
                  children: [
                    const Icon(Icons.calendar_month, size: 16, color: Color(0xFF64748B)),
                    const SizedBox(width: 6),
                    Text(_formatEventDate(event['date']), style: const TextStyle(fontSize: 14)),
                  ],
                ),
                const SizedBox(height: 6),
                Row(
                  children: [
                    const Icon(Icons.location_on, size: 16, color: Color(0xFF64748B)),
                    const SizedBox(width: 6),
                    Text(event['venue'] ?? 'Venue TBD', style: const TextStyle(fontSize: 14)),
                  ],
                ),
                const SizedBox(height: 12),
                Text(
                  event['description'] ?? 'Join us and participate in the campaign strategy meeting.',
                  style: const TextStyle(fontSize: 14, color: Color(0xFF64748B)),
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Close'),
            ),
            ElevatedButton.icon(
              onPressed: () => _checkInToEvent(event['id']?.toString() ?? '1'),
              icon: const Icon(Icons.gps_fixed, color: Colors.white),
              label: const Text('GPS CHECK IN', style: TextStyle(color: Colors.white)),
              style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF3451DB)),
            ),
          ],
        );
      },
    );
  }

  Future<void> _checkInToEvent(String eventId) async {
    Navigator.pop(context); // Close details dialog
    try {
      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
      }

      if (permission == LocationPermission.always || permission == LocationPermission.whileInUse) {
        final position = await Geolocator.getCurrentPosition();
        final apiClient = ref.read(apiClientProvider);

        final response = await apiClient.post('/events/$eventId/check-in', data: {
          'lat': position.latitude,
          'lng': position.longitude,
          'method': 'gps',
        });

        if (response.data['success'] == true) {
          _incrementPoints(5); // +5 points for event check-in
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Successfully checked in! 5 campaign points awarded.'),
              backgroundColor: Color(0xFF3451DB),
            ),
          );
        }
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Location permissions are required to check in to events.'),
            backgroundColor: Colors.orange,
          ),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Connection failed. Could not verify check-in.'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  Future<void> _loadProfileWards(int lgaId) async {
    setState(() {
      _profileWards = [];
      _profilePollingUnits = [];
      _profileSelectedWardId = null;
      _profileSelectedPuId = null;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/wards?lga_id=$lgaId');
      if (response.data['success'] == true) {
        setState(() {
          _profileWards = response.data['data'];
        });
      }
    } catch (_) {}
  }

  Future<void> _loadProfilePollingUnits(int wardId) async {
    setState(() {
      _profilePollingUnits = [];
      _profileSelectedPuId = null;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/polling-units?ward_id=$wardId');
      if (response.data['success'] == true) {
        setState(() {
          _profilePollingUnits = response.data['data'];
        });
      }
    } catch (_) {}
  }

  Future<void> _updateProfile() async {
    setState(() {
      _isUpdatingProfile = true;
    });
    try {
      final storage = ref.read(storageServiceProvider);
      final userId = storage.getUserId();
      if (userId == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Error: User session not found. Please log in again.')),
        );
        return;
      }

      final client = ref.read(apiClientProvider);
      
      final formDataMap = <String, dynamic>{
        'name': _profileNameController.text.trim(),
        'phone': _profilePhoneController.text.trim(),
        if (_profilePasswordController.text.isNotEmpty)
          'password': _profilePasswordController.text.trim(),
      };
      if (_profileSelectedLgaId != null) formDataMap['lga_id'] = _profileSelectedLgaId;
      if (_profileSelectedWardId != null) formDataMap['ward_id'] = _profileSelectedWardId;
      if (_profileSelectedPuId != null) formDataMap['polling_unit_id'] = _profileSelectedPuId;

      final formData = FormData.fromMap(formDataMap);

      if (_profileImageFile != null) {
        formData.files.add(MapEntry(
          'passport',
          await MultipartFile.fromFile(_profileImageFile!.path),
        ));
      }

      final response = await client.post('/members/$userId', data: formData);

      if (response.data['success'] == true) {
        final updatedUser = response.data['data'];
        
        final token = storage.getToken() ?? '';
        final role = storage.getUserRole() ?? '';
        await storage.saveSession(
          token: token,
          name: updatedUser['name'],
          phone: updatedUser['phone'],
          role: role,
          userId: _toInt(updatedUser['id']) ?? 0,
          isPhoneVerified: true,
          lgaId: _toInt(updatedUser['lga_id']),
          wardId: _toInt(updatedUser['ward_id']),
          puId: _toInt(updatedUser['polling_unit_id']),
        );

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Profile updated successfully!'), backgroundColor: Color(0xFF3451DB)),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response.data['message'] ?? 'Failed to update profile.')),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Connection error. Could not update profile.')),
      );
    } finally {
      setState(() {
        _isUpdatingProfile = false;
      });
    }
  }

  Widget _buildProfileTab(AuthState authState) {
    final List<DropdownMenuItem<int>> lgaItems = [];
    final Set<int> uniqueLgaIds = {};
    for (var lga in _lgas) {
      if (lga['id'] != null) {
        final id = lga['id'] is int ? lga['id'] as int : int.tryParse(lga['id'].toString()) ?? 0;
        if (!uniqueLgaIds.contains(id)) {
          uniqueLgaIds.add(id);
          lgaItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(lga['name']?.toString() ?? ''),
          ));
        }
      }
    }

    final List<DropdownMenuItem<int>> wardItems = [];
    final Set<int> uniqueWardIds = {};
    for (var ward in _profileWards) {
      if (ward['id'] != null) {
        final id = ward['id'] is int ? ward['id'] as int : int.tryParse(ward['id'].toString()) ?? 0;
        if (!uniqueWardIds.contains(id)) {
          uniqueWardIds.add(id);
          wardItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(ward['name']?.toString() ?? ''),
          ));
        }
      }
    }

    final List<DropdownMenuItem<int>> puItems = [];
    final Set<int> uniquePuIds = {};
    for (var pu in _profilePollingUnits) {
      if (pu['id'] != null) {
        final id = pu['id'] is int ? pu['id'] as int : int.tryParse(pu['id'].toString()) ?? 0;
        if (!uniquePuIds.contains(id)) {
          uniquePuIds.add(id);
          puItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(
              '${pu['code'] ?? ''} — ${pu['name'] ?? ''}',
              overflow: TextOverflow.ellipsis,
            ),
          ));
        }
      }
    }

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Center(
            child: Stack(
              children: [
                CircleAvatar(
                  radius: 50,
                  backgroundColor: const Color(0xFF4361EE),
                  backgroundImage: _profileImageFile != null ? FileImage(_profileImageFile!) : null,
                  child: _profileImageFile == null
                      ? Text(
                          (authState.userName ?? 'U').substring(0, 1).toUpperCase(),
                          style: const TextStyle(fontSize: 40, color: Colors.white, fontWeight: FontWeight.bold),
                        )
                      : null,
                ),
                Positioned(
                  bottom: 0,
                  right: 0,
                  child: GestureDetector(
                    onTap: _pickProfileImage,
                    child: Container(
                      padding: const EdgeInsets.all(4),
                      decoration: const BoxDecoration(
                        color: Color(0xFFBBD4FF),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.edit, size: 18, color: Color(0xFF3451DB)),
                    ),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          Center(
            child: Text(
              authState.userName ?? 'Campaign Supporter',
              style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Color(0xFF3451DB)),
            ),
          ),
          Center(
            child: Text(
              authState.userRole ?? 'Volunteer',
              style: const TextStyle(fontSize: 14, color: Colors.grey, fontWeight: FontWeight.bold),
            ),
          ),
          const SizedBox(height: 32),
          TextFormField(
            controller: _profileNameController,
            decoration: const InputDecoration(
              labelText: 'Full Name',
              prefixIcon: Icon(Icons.person),
              border: OutlineInputBorder(),
            ),
          ),
          const SizedBox(height: 16),
          TextFormField(
            controller: _profilePhoneController,
            decoration: const InputDecoration(
              labelText: 'Phone Number',
              prefixIcon: Icon(Icons.phone),
              border: OutlineInputBorder(),
            ),
            keyboardType: TextInputType.phone,
          ),
          const SizedBox(height: 16),
          TextFormField(
            controller: _profilePasswordController,
            decoration: const InputDecoration(
              labelText: 'New Password (leave blank to keep current)',
              prefixIcon: Icon(Icons.lock),
              border: OutlineInputBorder(),
            ),
            obscureText: true,
          ),
          const SizedBox(height: 16),
          DropdownButtonFormField<int>(
            value: uniqueLgaIds.contains(_profileSelectedLgaId) ? _profileSelectedLgaId : null,
            decoration: const InputDecoration(
              labelText: 'Local Government Area (LGA)',
              prefixIcon: Icon(Icons.location_city),
              border: OutlineInputBorder(),
            ),
            items: lgaItems,
            onChanged: (val) {
              if (val != null) {
                setState(() {
                  _profileSelectedLgaId = val;
                });
                _loadProfileWards(val);
              }
            },
          ),
          const SizedBox(height: 16),
          DropdownButtonFormField<int>(
            value: uniqueWardIds.contains(_profileSelectedWardId) ? _profileSelectedWardId : null,
            decoration: const InputDecoration(
              labelText: 'Ward',
              prefixIcon: Icon(Icons.map),
              border: OutlineInputBorder(),
            ),
            items: wardItems,
            onChanged: (val) {
              if (val != null) {
                setState(() {
                  _profileSelectedWardId = val;
                });
                _loadProfilePollingUnits(val);
              }
            },
          ),
          const SizedBox(height: 16),
          DropdownButtonFormField<int>(
            value: uniquePuIds.contains(_profileSelectedPuId) ? _profileSelectedPuId : null,
            decoration: const InputDecoration(
              labelText: 'Polling Unit (PU) (optional)',
              prefixIcon: Icon(Icons.how_to_vote),
              border: OutlineInputBorder(),
            ),
            items: puItems,
            onChanged: (val) {
              setState(() {
                _profileSelectedPuId = val;
              });
            },
          ),
          const SizedBox(height: 32),
          ElevatedButton(
            onPressed: _isUpdatingProfile ? null : _updateProfile,
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF4361EE),
              padding: const EdgeInsets.symmetric(vertical: 16),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(8),
              ),
            ),
            child: _isUpdatingProfile
                ? const CircularProgressIndicator(color: Colors.white)
                : const Text(
                    'Update Details',
                    style: TextStyle(fontSize: 16, color: Colors.white, fontWeight: FontWeight.bold),
                  ),
          ),
        ],
      ),
    );
  }
}
