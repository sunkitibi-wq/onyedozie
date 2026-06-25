import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:dio/dio.dart';
import 'storage.dart';

class ApiClient {
  final Dio dio;
  final StorageService _storageService;

  /// Called when the server returns HTTP 401 (token expired / revoked).
  /// The AuthNotifier wires this up to trigger a logout state transition.
  void Function()? onUnauthorized;

  static String get _defaultBaseUrl {
    const String envUrl = String.fromEnvironment('API_URL');
    if (envUrl.isNotEmpty) {
      return envUrl;
    }

    const bool useProduction = bool.fromEnvironment('USE_PRODUCTION');
    if (useProduction) {
      return 'https://onyedozie.olgagrp.com/api/v1';
    }

    if (kDebugMode) {
      // In development, default to local Laravel server.
      // - Android Emulator uses 10.0.2.2 to access host machine's localhost.
      // - iOS Simulator / Web / Desktop use local hostname directly.
      // Note: We default to '80' for Laravel Herd. Change to '8000' if you run 'php artisan serve'.
      const String localPort = '80';
      
      if (defaultTargetPlatform == TargetPlatform.android) {
        return 'http://10.0.2.2:$localPort/api/v1';
      }
      return 'http://onyedozieadmin.test:$localPort/api/v1';
    }
    return 'https://onyedozie-production-pchvwd.laravel.cloud/api/v1';
  }

  ApiClient(this._storageService, {String? baseUrl})
      : dio = Dio(BaseOptions(
          baseUrl: baseUrl ?? _defaultBaseUrl,
          connectTimeout: const Duration(seconds: 60),
          receiveTimeout: const Duration(seconds: 60),
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // If debugging on Android with local port 80/443 (Herd), we set the Host header
            // so Nginx knows which project/site to route the request to.
            if (kDebugMode &&
                defaultTargetPlatform == TargetPlatform.android &&
                (baseUrl ?? _defaultBaseUrl).contains('10.0.2.2'))
              'Host': 'onyedozieadmin.test',
          },
        )) {
    debugPrint('[ApiClient] Initialized with baseUrl: ${dio.options.baseUrl}');
    dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) {
          final token = _storageService.getToken();
          if (token != null) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          return handler.next(options);
        },
        onError: (DioException e, handler) async {
          // On 401: session token is expired or revoked on the server side.
          // Clear the local session and notify the app to redirect to login.
          if (e.response?.statusCode == 401) {
            await _storageService.clearSession();
            onUnauthorized?.call();
          }
          return handler.next(e);
        },
      ),
    );
  }

  Future<Response> get(String path, {Map<String, dynamic>? queryParameters}) async {
    return await dio.get(path, queryParameters: queryParameters);
  }

  Future<Response> post(String path, {dynamic data}) async {
    return await dio.post(path, data: data);
  }

  Future<Response> put(String path, {dynamic data}) async {
    return await dio.put(path, data: data);
  }

  Future<Response> delete(String path, {dynamic data}) async {
    return await dio.delete(path, data: data);
  }
}
