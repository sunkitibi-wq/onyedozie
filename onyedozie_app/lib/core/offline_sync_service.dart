import 'dart:convert';
import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:hive_flutter/hive_flutter.dart';
import 'api_client.dart';

class OfflineSyncService {
  final ApiClient _apiClient;
  final Box _syncBox = Hive.box('sync_queue');

  OfflineSyncService(this._apiClient) {
    // Listen for connectivity transitions to online to trigger a background sync
    Connectivity().onConnectivityChanged.listen((event) {
      _checkAndSync();
    });
  }

  // Helper to check connectivity state safely
  Future<bool> isOnline() async {
    final result = await Connectivity().checkConnectivity();
    return result != ConnectivityResult.none;
  }

  // Queue a request locally in Hive when offline
  Future<void> queueAction({
    required String endpoint,
    required Map<String, dynamic> data,
    required String type, // 'canvass' or 'incident'
  }) async {
    final String id = DateTime.now().millisecondsSinceEpoch.toString();
    final action = {
      'id': id,
      'endpoint': endpoint,
      'data': data,
      'type': type,
      'timestamp': DateTime.now().toIso8601String(),
    };
    await _syncBox.put(id, jsonEncode(action));
  }

  // Retrieve all queued actions
  List<Map<String, dynamic>> getQueuedActions() {
    return _syncBox.values.map((val) {
      return Map<String, dynamic>.from(jsonDecode(val as String));
    }).toList();
  }

  // Trigger sync if online
  Future<void> _checkAndSync() async {
    if (await isOnline()) {
      await syncPendingActions();
    }
  }

  // Synchronize all queued items with the server
  Future<void> syncPendingActions() async {
    final actions = getQueuedActions();
    if (actions.isEmpty) return;

    for (final action in actions) {
      final String id = action['id'];
      final String endpoint = action['endpoint'];
      final Map<String, dynamic> data = Map<String, dynamic>.from(action['data']);

      try {
        final response = await _apiClient.post(endpoint, data: data);
        if (response.data['success'] == true) {
          // Successfully synchronized, remove from offline queue box
          await _syncBox.delete(id);
        }
      } catch (e) {
        // Stop synchronizing subsequent items if a server/network error is encountered
        break;
      }
    }
  }
}
