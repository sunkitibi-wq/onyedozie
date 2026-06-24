import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:geolocator/geolocator.dart';
import '../../core/providers.dart';
import '../../core/api_client.dart';
import '../../core/offline_sync_service.dart';

class CanvassingState {
  final List<dynamic> logs;
  final bool isLoading;
  final bool isSaving;
  final String? errorMessage;

  CanvassingState({
    required this.logs,
    required this.isLoading,
    required this.isSaving,
    this.errorMessage,
  });

  factory CanvassingState.initial() {
    return CanvassingState(
      logs: [],
      isLoading: false,
      isSaving: false,
      errorMessage: null,
    );
  }

  CanvassingState copyWith({
    List<dynamic>? logs,
    bool? isLoading,
    bool? isSaving,
    String? errorMessage,
  }) {
    return CanvassingState(
      logs: logs ?? this.logs,
      isLoading: isLoading ?? this.isLoading,
      isSaving: isSaving ?? this.isSaving,
      errorMessage: errorMessage,
    );
  }
}

class CanvassingNotifier extends StateNotifier<CanvassingState> {
  final ApiClient _apiClient;
  final OfflineSyncService _syncService;

  CanvassingNotifier(this._apiClient, this._syncService) : super(CanvassingState.initial());

  Future<void> loadLogs() async {
    state = state.copyWith(isLoading: true);
    try {
      final response = await _apiClient.get('/door-knocks');
      if (response.data['success'] == true) {
        state = state.copyWith(
          logs: response.data['data'] ?? [],
          isLoading: false,
        );
      } else {
        state = state.copyWith(
          isLoading: false,
          errorMessage: response.data['message'] ?? 'Failed to load logs',
        );
      }
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        errorMessage: e.toString(),
      );
    }
  }

  Future<void> saveVisit({
    required String voterName,
    required String address,
    required String outcome,
    required String notes,
    required VoidCallback onSuccess,
    required VoidCallback onQueuedOffline,
    required ValueChanged<String> onError,
  }) async {
    state = state.copyWith(isSaving: true);

    // Prepare visit data
    final Map<String, dynamic> visitData = {
      'outcome': outcome,
      'voter_name': voterName.isNotEmpty ? voterName : null,
      'notes': notes.isNotEmpty ? notes : null,
      'address_description': address.isNotEmpty ? address : null,
    };

    final isOnline = await _syncService.isOnline();

    if (!isOnline) {
      // Offline mode: Queue locally
      // Fallback/stub coordinates when offline
      visitData['lat'] = 6.22;
      visitData['lng'] = 7.01;

      try {
        await _syncService.queueAction(
          endpoint: '/door-knocks',
          data: visitData,
          type: 'canvass',
        );

        // Optimistically add to local logs
        final localLog = {
          ...visitData,
          'id': DateTime.now().millisecondsSinceEpoch,
          'visited_at': DateTime.now().toIso8601String(),
        };

        state = state.copyWith(
          logs: [localLog, ...state.logs],
          isSaving: false,
        );

        onQueuedOffline();
      } catch (e) {
        state = state.copyWith(isSaving: false);
        onError('Failed to queue offline action: $e');
      }
      return;
    }

    // Online mode: Get actual location first
    double? lat;
    double? lng;

    try {
      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
      }

      if (permission == LocationPermission.always || permission == LocationPermission.whileInUse) {
        final position = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.high,
          timeLimit: const Duration(seconds: 5),
        );
        lat = position.latitude;
        lng = position.longitude;
      }
    } catch (e) {
      debugPrint('[CanvassingProvider] Location acquisition error: $e');
      // Proceed without coordinates if location fails
    }

    visitData['lat'] = lat;
    visitData['lng'] = lng;

    try {
      final response = await _apiClient.post('/door-knocks', data: visitData);

      if (response.data['success'] == true) {
        // Reload history to ensure consistency with backend
        await loadLogs();
        state = state.copyWith(isSaving: false);
        onSuccess();
      } else {
        state = state.copyWith(isSaving: false);
        onError(response.data['message'] ?? 'Failed to log canvass visit.');
      }
    } catch (e) {
      state = state.copyWith(isSaving: false);
      onError('Connection failed. Could not save canvass visit: $e');
    }
  }
}

final canvassingProvider = StateNotifierProvider<CanvassingNotifier, CanvassingState>((ref) {
  final apiClient = ref.watch(apiClientProvider);
  final syncService = ref.watch(offlineSyncServiceProvider);
  final notifier = CanvassingNotifier(apiClient, syncService);
  // Pre-load logs when the provider is initialized
  notifier.loadLogs();
  return notifier;
});
