import 'dart:io' show Platform;

import 'package:flutter/foundation.dart';

// base url for the backend (PHP).
// localhost works on ios sim, but NOT on android emulator
// -> on android we have to use 10.0.2.2 (which is the host machine)
// release build = use the deployed url on railway
class ApiConfig {
  ApiConfig._();

  static const String _prodBaseUrl = String.fromEnvironment(
    'VAGABOND_API_BASE_URL',
    defaultValue: 'https://vagabond.up.railway.app',
  );

  static const String _devHostUrl = String.fromEnvironment(
    'VAGABOND_DEV_BASE_URL',
    defaultValue: 'http://localhost:8080',
  );

  static String get baseUrl {
    if (kReleaseMode) return _prodBaseUrl;
    if (kIsWeb) return _devHostUrl;
    try {
      if (Platform.isAndroid) {
        return _devHostUrl.replaceFirst('localhost', '10.0.2.2');
      }
    } catch (_) {
      // in tests there is no Platform, so just ignore the error
    }
    return _devHostUrl;
  }

  static Uri api(String path, [Map<String, dynamic>? query]) {
    final uri = Uri.parse('$baseUrl/api/v1$path');
    if (query == null || query.isEmpty) return uri;
    return uri.replace(
      queryParameters: {
        ...uri.queryParameters,
        for (final entry in query.entries)
          if (entry.value != null) entry.key: '${entry.value}',
      },
    );
  }
}
