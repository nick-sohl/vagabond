import '../api/api_client.dart';
import '../models/user.dart';

class AuthResult {
  AuthResult({required this.token, required this.user});

  final String token;
  final AppUser user;
}

class AuthRepository {
  AuthRepository(this._api);

  final ApiClient _api;

  Future<AuthResult> login(String email, String password) async {
    final payload = await _api.post('/auth/login', {
      'email': email,
      'password': password,
    }) as Map<String, dynamic>;
    return AuthResult(
      token: payload['token'] as String,
      user: AppUser.fromJson(payload['user'] as Map<String, dynamic>),
    );
  }

  Future<AuthResult> register({
    required String firstName,
    required String lastName,
    required String email,
    required String password,
    required String confirmPassword,
  }) async {
    final payload = await _api.post('/auth/register', {
      'first_name': firstName,
      'last_name': lastName,
      'email': email,
      'password': password,
      'confirm_password': confirmPassword,
    }) as Map<String, dynamic>;
    return AuthResult(
      token: payload['token'] as String,
      user: AppUser.fromJson(payload['user'] as Map<String, dynamic>),
    );
  }

  Future<AppUser> me() async {
    final payload = await _api.get('/auth/me') as Map<String, dynamic>;
    return AppUser.fromJson(payload['user'] as Map<String, dynamic>);
  }

  Future<void> logout() => _api.post('/auth/logout', const {});
}
