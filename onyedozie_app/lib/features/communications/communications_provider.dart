import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/providers.dart';
import '../../core/api_client.dart';

class CommunicationsState {
  final List<dynamic> alerts; // WhatsApp Broadcasts
  final List<dynamic> posts; // Scheduled/published social posts
  final bool isLoadingAlerts;
  final bool isLoadingPosts;
  final bool isSaving;
  final String? errorMessage;

  CommunicationsState({
    required this.alerts,
    required this.posts,
    required this.isLoadingAlerts,
    required this.isLoadingPosts,
    required this.isSaving,
    this.errorMessage,
  });

  factory CommunicationsState.initial() {
    return CommunicationsState(
      alerts: [],
      posts: [],
      isLoadingAlerts: false,
      isLoadingPosts: false,
      isSaving: false,
      errorMessage: null,
    );
  }

  CommunicationsState copyWith({
    List<dynamic>? alerts,
    List<dynamic>? posts,
    bool? isLoadingAlerts,
    bool? isLoadingPosts,
    bool? isSaving,
    String? errorMessage,
  }) {
    return CommunicationsState(
      alerts: alerts ?? this.alerts,
      posts: posts ?? this.posts,
      isLoadingAlerts: isLoadingAlerts ?? this.isLoadingAlerts,
      isLoadingPosts: isLoadingPosts ?? this.isLoadingPosts,
      isSaving: isSaving ?? this.isSaving,
      errorMessage: errorMessage,
    );
  }
}

class CommunicationsNotifier extends StateNotifier<CommunicationsState> {
  final ApiClient _apiClient;

  CommunicationsNotifier(this._apiClient) : super(CommunicationsState.initial());

  Future<void> loadAlerts() async {
    state = state.copyWith(isLoadingAlerts: true);
    try {
      final response = await _apiClient.get('/broadcasts');
      if (response.data['success'] == true) {
        state = state.copyWith(
          alerts: response.data['data'] ?? [],
          isLoadingAlerts: false,
        );
      } else {
        state = state.copyWith(
          isLoadingAlerts: false,
          errorMessage: response.data['message'] ?? 'Failed to load alerts',
        );
      }
    } catch (e) {
      state = state.copyWith(
        isLoadingAlerts: false,
        errorMessage: e.toString(),
      );
    }
  }

  Future<void> loadScheduledPosts() async {
    state = state.copyWith(isLoadingPosts: true);
    try {
      final response = await _apiClient.get('/scheduled-posts');
      if (response.data['success'] == true) {
        state = state.copyWith(
          posts: response.data['data'] ?? [],
          isLoadingPosts: false,
        );
      } else {
        state = state.copyWith(
          isLoadingPosts: false,
          errorMessage: response.data['message'] ?? 'Failed to load social posts',
        );
      }
    } catch (e) {
      state = state.copyWith(
        isLoadingPosts: false,
        errorMessage: e.toString(),
      );
    }
  }

  Future<void> createBroadcast({
    required String message,
    required String audienceType,
    Map<String, dynamic>? audienceFilter,
    required VoidCallback onSuccess,
    required ValueChanged<String> onError,
  }) async {
    state = state.copyWith(isSaving: true);
    try {
      final response = await _apiClient.post('/broadcasts', data: {
        'message': message,
        'audience_type': audienceType,
        'audience_filter': audienceFilter,
      });

      if (response.data['success'] == true) {
        await loadAlerts();
        state = state.copyWith(isSaving: false);
        onSuccess();
      } else {
        state = state.copyWith(isSaving: false);
        onError(response.data['message'] ?? 'Failed to dispatch broadcast.');
      }
    } catch (e) {
      state = state.copyWith(isSaving: false);
      onError('Connection error: $e');
    }
  }

  Future<void> scheduleSocialPost({
    required String content,
    required List<String> platforms,
    required String scheduledAt,
    required VoidCallback onSuccess,
    required ValueChanged<String> onError,
  }) async {
    state = state.copyWith(isSaving: true);
    try {
      final response = await _apiClient.post('/scheduled-posts', data: {
        'content': content,
        'platforms': platforms,
        'scheduled_at': scheduledAt,
      });

      if (response.data['success'] == true) {
        await loadScheduledPosts();
        state = state.copyWith(isSaving: false);
        onSuccess();
      } else {
        state = state.copyWith(isSaving: false);
        onError(response.data['message'] ?? 'Failed to schedule post.');
      }
    } catch (e) {
      state = state.copyWith(isSaving: false);
      onError('Connection error: $e');
    }
  }
}

final communicationsProvider = StateNotifierProvider<CommunicationsNotifier, CommunicationsState>((ref) {
  final apiClient = ref.watch(apiClientProvider);
  final notifier = CommunicationsNotifier(apiClient);
  notifier.loadAlerts();
  notifier.loadScheduledPosts();
  return notifier;
});
