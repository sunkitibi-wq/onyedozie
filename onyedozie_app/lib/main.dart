import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:hive_flutter/hive_flutter.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'core/providers.dart';
import 'core/storage.dart';
import 'features/auth/auth_provider.dart';
import 'features/auth/login_screen.dart';
import 'features/auth/onboarding_screen.dart';
import 'features/auth/otp_verification_screen.dart';
import 'features/dashboard/dashboard_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Initialize Hive offline storage
  await Hive.initFlutter();
  await Hive.openBox('members_cache');
  await Hive.openBox('news_cache');
  await Hive.openBox('sync_queue');

  final prefs = await SharedPreferences.getInstance();

  // Warm the secure token cache before runApp so getToken() is synchronous
  // in the Dio interceptor (reads from Android Keystore / iOS Keychain).
  final storage = StorageService(prefs);
  await storage.init();

  runApp(
    ProviderScope(
      overrides: [
        sharedPreferencesProvider.overrideWithValue(prefs),
        storageServiceProvider.overrideWithValue(storage),
      ],
      child: const OnyendoziApp(),
    ),
  );
}

class OnyendoziApp extends ConsumerWidget {
  const OnyendoziApp({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authProvider);
    final onboardingCompleted = ref.watch(onboardingCompletedProvider);

    Widget homeWidget;
    if (!onboardingCompleted) {
      homeWidget = const OnboardingScreen();
    } else {
      homeWidget = _getHomeWidget(authState.status, ref);
    }

    return MaterialApp(
      title: 'Onyendozi Connect',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFF4361EE)),
        useMaterial3: true,
      ),
      home: homeWidget,
    );
  }

  Widget _getHomeWidget(AuthStatus status, WidgetRef ref) {
    switch (status) {
      case AuthStatus.authenticated:
        return const DashboardScreen();
      case AuthStatus.needsVerification:
        final phone = ref.read(storageServiceProvider).getUserPhone() ?? '';
        return OtpVerificationScreen(phone: phone);
      case AuthStatus.loading:
        return const Scaffold(
          body: Center(
            child: CircularProgressIndicator(color: Color(0xFF4361EE)),
          ),
        );
      case AuthStatus.unauthenticated:
      case AuthStatus.error:
        return const LoginScreen();
    }
  }
}
