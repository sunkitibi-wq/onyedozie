import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// StorageService stores the auth token in the platform secure enclave
/// (Android Keystore / iOS Keychain) via flutter_secure_storage.
/// Non-sensitive profile data (name, role, IDs) remain in SharedPreferences.
class StorageService {
  // Secure key — stored in Android Keystore / iOS Keychain
  static const String _keyToken = 'auth_token';

  // Non-sensitive keys — stored in SharedPreferences
  static const String _keyUserName = 'user_name';
  static const String _keyUserPhone = 'user_phone';
  static const String _keyUserRole = 'user_role';
  static const String _keyUserId = 'user_id';
  static const String _keyLgaId = 'user_lga_id';
  static const String _keyWardId = 'user_ward_id';
  static const String _keyPuId = 'user_pu_id';
  static const String _keyOnboardingCompleted = 'onboarding_completed';
  static const String _keyPhoneVerified = 'user_phone_verified';
  static const String _keyPassportUrl = 'user_passport_url';

  final SharedPreferences _prefs;
  final FlutterSecureStorage _secureStorage;

  /// Cached token — loaded once at startup so `getToken()` stays synchronous
  /// for use inside the Dio interceptor.
  String? _cachedToken;

  StorageService(this._prefs)
      : _secureStorage = const FlutterSecureStorage(
          aOptions: AndroidOptions(encryptedSharedPreferences: true),
          iOptions: IOSOptions(accessibility: KeychainAccessibility.first_unlock),
        );

  /// Call once at startup (in main) to warm the token cache.
  Future<void> init() async {
    _cachedToken = await _secureStorage.read(key: _keyToken);
  }

  Future<void> saveSession({
    required String token,
    required String name,
    required String phone,
    required String role,
    required int userId,
    required bool isPhoneVerified,
    int? lgaId,
    int? wardId,
    int? puId,
    String? passportUrl,
  }) async {
    // Persist token securely
    await _secureStorage.write(key: _keyToken, value: token);
    _cachedToken = token;

    // Persist non-sensitive fields normally
    await _prefs.setString(_keyUserName, name);
    await _prefs.setString(_keyUserPhone, phone);
    await _prefs.setString(_keyUserRole, role);
    await _prefs.setInt(_keyUserId, userId);
    await _prefs.setBool(_keyPhoneVerified, isPhoneVerified);
    if (lgaId != null) {
      await _prefs.setInt(_keyLgaId, lgaId);
    } else {
      await _prefs.remove(_keyLgaId);
    }
    if (wardId != null) {
      await _prefs.setInt(_keyWardId, wardId);
    } else {
      await _prefs.remove(_keyWardId);
    }
    if (puId != null) {
      await _prefs.setInt(_keyPuId, puId);
    } else {
      await _prefs.remove(_keyPuId);
    }
    if (passportUrl != null && passportUrl.isNotEmpty) {
      await _prefs.setString(_keyPassportUrl, passportUrl);
    } else {
      await _prefs.remove(_keyPassportUrl);
    }
  }

  /// Synchronous — returns from the in-memory cache populated by [init].
  String? getToken() => _cachedToken;

  String? getUserName() => _prefs.getString(_keyUserName);
  String? getUserPhone() => _prefs.getString(_keyUserPhone);
  String? getUserRole() => _prefs.getString(_keyUserRole);
  int? getUserId() => _prefs.getInt(_keyUserId);
  int? getLgaId() => _prefs.getInt(_keyLgaId);
  int? getWardId() => _prefs.getInt(_keyWardId);
  int? getPollingUnitId() => _prefs.getInt(_keyPuId);
  String? getPassportUrl() => _prefs.getString(_keyPassportUrl);
  bool isPhoneVerified() => _prefs.getBool(_keyPhoneVerified) ?? false;

  Future<void> setPhoneVerified(bool verified) async {
    await _prefs.setBool(_keyPhoneVerified, verified);
  }

  bool isOnboardingCompleted() => _prefs.getBool(_keyOnboardingCompleted) ?? false;

  Future<void> setOnboardingCompleted(bool completed) async {
    await _prefs.setBool(_keyOnboardingCompleted, completed);
  }

  Future<void> clearSession() async {
    await _secureStorage.delete(key: _keyToken);
    _cachedToken = null;
    await _prefs.remove(_keyUserName);
    await _prefs.remove(_keyUserPhone);
    await _prefs.remove(_keyUserRole);
    await _prefs.remove(_keyUserId);
    await _prefs.remove(_keyLgaId);
    await _prefs.remove(_keyWardId);
    await _prefs.remove(_keyPuId);
    await _prefs.remove(_keyPhoneVerified);
    await _prefs.remove(_keyPassportUrl);
  }
}
