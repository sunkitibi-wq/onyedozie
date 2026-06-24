import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/api_client.dart';
import '../../core/storage.dart';

int? _toInt(dynamic val) {
  if (val == null) return null;
  if (val is int) return val;
  return int.tryParse(val.toString());
}

enum AuthStatus { authenticated, unauthenticated, loading, error, needsVerification }

class AuthState {
  final AuthStatus status;
  final String? errorMessage;
  final String? userName;
  final String? userRole;

  AuthState({
    required this.status,
    this.errorMessage,
    this.userName,
    this.userRole,
  });

  factory AuthState.initial(StorageService storage) {
    final token = storage.getToken();
    if (token != null) {
      if (!storage.isPhoneVerified()) {
        return AuthState(status: AuthStatus.needsVerification);
      }
      return AuthState(
        status: AuthStatus.authenticated,
        userName: storage.getUserName(),
        userRole: storage.getUserRole(),
      );
    }
    return AuthState(status: AuthStatus.unauthenticated);
  }
}

class AuthNotifier extends StateNotifier<AuthState> {
  final ApiClient _apiClient;
  final StorageService _storageService;

  AuthNotifier(ApiClient apiClient, this._storageService)
      : _apiClient = apiClient,
        super(AuthState.initial(_storageService)) {
    // Wire up the 401 handler: if the server rejects our token, immediately
    // transition to unauthenticated so the app redirects to the login screen.
    _apiClient.onUnauthorized = () {
      state = AuthState(status: AuthStatus.unauthenticated);
    };
  }

  Future<void> login(String phone, String password) async {
    state = AuthState(status: AuthStatus.loading);
    try {
      final response = await _apiClient.post('/auth/login', data: {
        'phone': phone,
        'password': password,
      });

      if (response.data['success'] == true) {
        final data = response.data['data'];
        final token = data['token'];
        final user = data['user'];
        final name = user['name'];
        final rolesList = user['roles'] as List?;
        final role = (rolesList != null && rolesList.isNotEmpty)
            ? rolesList[0]['name']?.toString() ?? 'Volunteer'
            : 'Volunteer';
        final isVerified = user['phone_verified_at'] != null;

        await _storageService.saveSession(
          token: token,
          name: name,
          phone: phone,
          role: role,
          userId: _toInt(user['id']) ?? 0,
          isPhoneVerified: isVerified,
          lgaId: _toInt(user['lga_id']),
          wardId: _toInt(user['ward_id']),
          puId: _toInt(user['polling_unit_id']),
        );

        if (!isVerified) {
          state = AuthState(status: AuthStatus.needsVerification);
          return;
        }

        state = AuthState(
          status: AuthStatus.authenticated,
          userName: name,
          userRole: role,
        );
      } else {
        state = AuthState(
          status: AuthStatus.error,
          errorMessage: response.data['message'] ?? 'Login failed',
        );
      }
    } catch (e, stack) {
      debugPrint('[AuthNotifier] Login Exception: $e\n$stack');
      state = AuthState(
        status: AuthStatus.error,
        errorMessage: 'Connection error: $e',
      );
    }
  }

  Future<void> register({
    required String name,
    required String email,
    required String phone,
    required String password,
    required String role,
    int? lgaId,
    int? wardId,
    int? puId,
  }) async {
    state = AuthState(status: AuthStatus.loading);
    try {
      final response = await _apiClient.post('/auth/register', data: {
        'name': name,
        'email': email,
        'phone': phone,
        'password': password,
        'role': role,
        'lga_id': lgaId,
        'ward_id': wardId,
        'polling_unit_id': puId,
      });

      if (response.data['success'] == true) {
        final data = response.data['data'];
        final token = data['token'];
        final user = data['user'];
        final userName = user['name'];
        final rolesList = user['roles'] as List?;
        final userRole = (rolesList != null && rolesList.isNotEmpty)
            ? rolesList[0]['name']?.toString() ?? 'Volunteer'
            : 'Volunteer';
        final isVerified = user['phone_verified_at'] != null;

        await _storageService.saveSession(
          token: token,
          name: userName,
          phone: phone,
          role: userRole,
          userId: _toInt(user['id']) ?? 0,
          isPhoneVerified: isVerified,
          lgaId: _toInt(user['lga_id']),
          wardId: _toInt(user['ward_id']),
          puId: _toInt(user['polling_unit_id']),
        );

        if (!isVerified) {
          state = AuthState(status: AuthStatus.needsVerification);
          return;
        }

        state = AuthState(
          status: AuthStatus.authenticated,
          userName: userName,
          userRole: userRole,
        );
      } else {
        state = AuthState(
          status: AuthStatus.error,
          errorMessage: response.data['message'] ?? 'Registration failed',
        );
      }
    } catch (e, stack) {
      debugPrint('[AuthNotifier] Registration Exception: $e\n$stack');
      state = AuthState(
        status: AuthStatus.error,
        errorMessage: 'Connection error: $e',
      );
    }
  }

  Future<void> logout() async {
    try {
      await _apiClient.post('/auth/logout');
    } catch (_) {}
    await _storageService.clearSession();
    state = AuthState(status: AuthStatus.unauthenticated);
  }

  Future<bool> verifyOtpCode(String phone, String otp) async {
    try {
      final response = await _apiClient.post('/auth/verify-otp', data: {
        'phone': phone,
        'otp': otp,
        'purpose': 'phone_verification',
      });

      if (response.data['success'] == true) {
        await _storageService.setPhoneVerified(true);
        state = AuthState(
          status: AuthStatus.authenticated,
          userName: _storageService.getUserName(),
          userRole: _storageService.getUserRole(),
        );
        return true;
      }
    } catch (_) {}
    return false;
  }

  Future<bool> resendOtpCode(String phone) async {
    try {
      final response = await _apiClient.post('/auth/resend-otp', data: {
        'phone': phone,
        'purpose': 'phone_verification',
      });
      return response.data['success'] == true;
    } catch (_) {}
    return false;
  }
}
