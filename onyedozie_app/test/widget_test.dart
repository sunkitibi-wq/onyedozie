import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:dio/dio.dart';

import 'package:onyedozie_app/main.dart';
import 'package:onyedozie_app/core/providers.dart';
import 'package:onyedozie_app/core/storage.dart';
import 'package:onyedozie_app/core/api_client.dart';
import 'package:onyedozie_app/core/notification_service.dart';
import 'package:onyedozie_app/core/offline_sync_service.dart';

class FakeStorageService implements StorageService {
  final SharedPreferences _prefs;
  FakeStorageService(this._prefs);

  @override
  Future<void> init() async {}

  @override
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
  }) async {
    await _prefs.setString('user_name', name);
    await _prefs.setString('user_phone', phone);
    await _prefs.setString('user_role', role);
    await _prefs.setInt('user_id', userId);
    await _prefs.setBool('user_phone_verified', isPhoneVerified);
  }

  @override
  String? getToken() => null;
  @override
  String? getUserName() => _prefs.getString('user_name');
  @override
  String? getUserPhone() => _prefs.getString('user_phone');
  @override
  String? getUserRole() => _prefs.getString('user_role');
  @override
  int? getUserId() => _prefs.getInt('user_id');
  @override
  int? getLgaId() => _prefs.getInt('user_lga_id');
  @override
  int? getWardId() => _prefs.getInt('user_ward_id');
  @override
  int? getPollingUnitId() => _prefs.getInt('user_pu_id');
  @override
  bool isPhoneVerified() => _prefs.getBool('user_phone_verified') ?? false;

  @override
  Future<void> setPhoneVerified(bool verified) async {
    await _prefs.setBool('user_phone_verified', verified);
  }

  @override
  bool isOnboardingCompleted() => _prefs.getBool('onboarding_completed') ?? false;

  @override
  Future<void> setOnboardingCompleted(bool completed) async {
    await _prefs.setBool('onboarding_completed', completed);
  }

  @override
  Future<void> clearSession() async {
    await _prefs.remove('user_name');
    await _prefs.remove('user_phone');
    await _prefs.remove('user_role');
    await _prefs.remove('user_id');
    await _prefs.remove('user_phone_verified');
  }
}

class FakeApiClient implements ApiClient {
  @override
  Dio get dio => throw UnimplementedError();

  @override
  void Function()? get onUnauthorized => null;
  @override
  set onUnauthorized(void Function()? value) {}

  @override
  Future<Response> get(String path, {Map<String, dynamic>? queryParameters}) async {
    return Response(requestOptions: RequestOptions(path: path), data: {'success': true, 'data': []});
  }

  @override
  Future<Response> post(String path, {dynamic data}) async {
    return Response(requestOptions: RequestOptions(path: path), data: {'success': true});
  }

  @override
  Future<Response> put(String path, {dynamic data}) async {
    return Response(requestOptions: RequestOptions(path: path), data: {'success': true});
  }

  @override
  Future<Response> delete(String path, {dynamic data}) async {
    return Response(requestOptions: RequestOptions(path: path), data: {'success': true});
  }
}

class FakeOfflineSyncService implements OfflineSyncService {
  @override
  Future<bool> isOnline() async => true;

  @override
  Future<void> queueAction({required String endpoint, required Map<String, dynamic> data, required String type}) async {}

  @override
  List<Map<String, dynamic>> getQueuedActions() => [];

  @override
  Future<void> syncPendingActions() async {}
}

class FakeNotificationService implements NotificationService {
  @override
  Future<void> initialize() async {}
}

void main() {
  testWidgets('OnyendoziApp renders LoginScreen by default', (WidgetTester tester) async {
    SharedPreferences.setMockInitialValues({
      'onboarding_completed': true,
    });
    final prefs = await SharedPreferences.getInstance();
    final storage = FakeStorageService(prefs);

    await tester.pumpWidget(
      ProviderScope(
        overrides: [
          sharedPreferencesProvider.overrideWithValue(prefs),
          storageServiceProvider.overrideWithValue(storage),
          apiClientProvider.overrideWithValue(FakeApiClient()),
          offlineSyncServiceProvider.overrideWithValue(FakeOfflineSyncService()),
          notificationServiceProvider.overrideWithValue(FakeNotificationService()),
        ],
        child: const OnyendoziApp(),
      ),
    );

    await tester.pumpAndSettle();

    // Verify Onyendozi Connect title is visible on Login Screen
    expect(find.text('Onyendozi Connect'), findsOneWidget);
    expect(find.text('Sign In'), findsOneWidget);
    expect(find.text('Phone Number'), findsOneWidget);
  });
}
