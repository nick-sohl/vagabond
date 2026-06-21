import 'package:flutter_secure_storage/flutter_secure_storage.dart';

// keeps the bearer token between app starts.
// secure_storage uses Keychain on ios and EncryptedSharedPreferences on
// android -> other apps on the phone can't read it
class TokenStorage {
  TokenStorage({FlutterSecureStorage? storage})
      : _storage = storage ??
            const FlutterSecureStorage(
              aOptions: AndroidOptions(encryptedSharedPreferences: true),
            );

  static const _tokenKey = 'vagabond.auth.token';
  static const _userKey = 'vagabond.auth.user';

  final FlutterSecureStorage _storage;

  Future<void> write({required String token, required String userJson}) async {
    await _storage.write(key: _tokenKey, value: token);
    await _storage.write(key: _userKey, value: userJson);
  }

  Future<String?> readToken() => _storage.read(key: _tokenKey);
  Future<String?> readUser() => _storage.read(key: _userKey);

  Future<void> clear() async {
    await _storage.delete(key: _tokenKey);
    await _storage.delete(key: _userKey);
  }
}
