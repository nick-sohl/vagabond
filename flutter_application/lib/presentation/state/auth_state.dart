import 'dart:convert';

import 'package:flutter/foundation.dart';

import '../../data/api/api_client.dart';
import '../../data/api/api_exception.dart';
import '../../data/api/token_storage.dart';
import '../../data/models/user.dart';
import '../../data/repositories/auth_repository.dart';

enum AuthStatus { unknown, signedOut, signedIn }

// keeps the login session for the whole UI.
//
// on app start -> restore():
//   - read token from secure storage
//   - if there is one, ask /auth/me if it's still valid
//   - if it's gone/expired -> drop it and show login screen
class AuthState extends ChangeNotifier {
  AuthState({
    required ApiClient apiClient,
    required AuthRepository repository,
    required TokenStorage storage,
  })  : _api = apiClient,
        _repo = repository,
        // ignore: prefer_initializing_formals
        _storage = storage;

  final ApiClient _api;
  final AuthRepository _repo;
  final TokenStorage _storage;

  AuthStatus _status = AuthStatus.unknown;
  AppUser? _user;
  String? _lastError;
  bool _busy = false;

  AuthStatus get status => _status;
  AppUser? get user => _user;
  String? get lastError => _lastError;
  bool get isBusy => _busy;

  Future<void> restore() async {
    final token = await _storage.readToken();
    if (token == null || token.isEmpty) {
      _status = AuthStatus.signedOut;
      notifyListeners();
      return;
    }
    _api.setToken(token);
    try {
      final user = await _repo.me();
      _user = user;
      _status = AuthStatus.signedIn;
    } on ApiException {
      await _storage.clear();
      _api.setToken(null);
      _status = AuthStatus.signedOut;
    }
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _setBusy(true);
    try {
      final result = await _repo.login(email, password);
      await _persist(result);
      return true;
    } on ApiException catch (e) {
      _lastError = e.message;
      return false;
    } finally {
      _setBusy(false);
    }
  }

  Future<bool> register({
    required String firstName,
    required String lastName,
    required String email,
    required String password,
    required String confirmPassword,
  }) async {
    _setBusy(true);
    try {
      final result = await _repo.register(
        firstName: firstName,
        lastName: lastName,
        email: email,
        password: password,
        confirmPassword: confirmPassword,
      );
      await _persist(result);
      return true;
    } on ApiException catch (e) {
      _lastError = e.message;
      return false;
    } finally {
      _setBusy(false);
    }
  }

  Future<void> logout() async {
    try {
      await _repo.logout();
    } on ApiException {
      // don't care if the backend call fails - we drop the token locally anyway
    }
    await _storage.clear();
    _api.setToken(null);
    _user = null;
    _status = AuthStatus.signedOut;
    notifyListeners();
  }

  Future<void> _persist(AuthResult result) async {
    _api.setToken(result.token);
    await _storage.write(
      token: result.token,
      userJson: jsonEncode(result.user.toJson()),
    );
    _user = result.user;
    _status = AuthStatus.signedIn;
    _lastError = null;
    notifyListeners();
  }

  void _setBusy(bool value) {
    _busy = value;
    if (value) _lastError = null;
    notifyListeners();
  }
}
