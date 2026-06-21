import 'dart:async';
import 'dart:convert';

import 'package:http/http.dart' as http;

import '../../core/constants/api_config.dart';
import '../../core/utils/logger.dart';
import 'api_exception.dart';

// wraps package:http so I only have one place that
//  - adds the base url
//  - puts the bearer token in the header
//  - parses JSON and turns errors into ApiException
//
// the repositories use this, the UI never touches http directly.
// makes testing easier (just swap the repo with a fake)
class ApiClient {
  ApiClient({http.Client? httpClient, Duration? timeout})
      : _http = httpClient ?? http.Client(),
        _timeout = timeout ?? const Duration(seconds: 15);

  final http.Client _http;
  final Duration _timeout;

  String? _token;

  void setToken(String? token) => _token = token;
  String? get token => _token;

  Map<String, String> _headers({bool jsonBody = false}) {
    final headers = <String, String>{
      'Accept': 'application/json',
    };
    if (jsonBody) headers['Content-Type'] = 'application/json';
    final token = _token;
    if (token != null && token.isNotEmpty) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  Future<dynamic> get(String path, {Map<String, dynamic>? query}) async {
    final uri = ApiConfig.api(path, query);
    Log.info('api', 'GET $uri');
    try {
      final response = await _http.get(uri, headers: _headers()).timeout(_timeout);
      return _decode(response);
    } on TimeoutException {
      throw ApiException('Anfrage hat zu lange gedauert.');
    } catch (e, st) {
      Log.error('api', 'GET $uri failed', e, st);
      // already an ApiException -> don't wrap it again
      if (e is ApiException) rethrow;
      throw ApiException('Verbindung fehlgeschlagen: $e');
    }
  }

  Future<dynamic> post(String path, Map<String, dynamic> body) async {
    final uri = ApiConfig.api(path);
    Log.info('api', 'POST $uri');
    try {
      final response = await _http
          .post(uri, headers: _headers(jsonBody: true), body: jsonEncode(body))
          .timeout(_timeout);
      return _decode(response);
    } on TimeoutException {
      throw ApiException('Anfrage hat zu lange gedauert.');
    } catch (e, st) {
      Log.error('api', 'POST $uri failed', e, st);
      if (e is ApiException) rethrow;
      throw ApiException('Verbindung fehlgeschlagen: $e');
    }
  }

  dynamic _decode(http.Response response) {
    final status = response.statusCode;
    dynamic decoded;
    if (response.body.isNotEmpty) {
      try {
        decoded = jsonDecode(response.body);
      } on FormatException {
        decoded = null;
      }
    }
    if (status >= 200 && status < 300) {
      return decoded;
    }
    final message = decoded is Map<String, dynamic>
        ? (decoded['error']?.toString() ?? 'Anfrage fehlgeschlagen.')
        : 'Anfrage fehlgeschlagen (HTTP $status).';
    throw ApiException(message, statusCode: status);
  }

  void close() => _http.close();
}
