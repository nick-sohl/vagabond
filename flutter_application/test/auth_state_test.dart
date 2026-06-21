import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:vagabond/data/api/api_client.dart';
import 'package:vagabond/data/api/token_storage.dart';
import 'package:vagabond/presentation/state/auth_state.dart';

import '_fakes.dart';

void main() {
  setUp(() {
    // setMockInitialValues -> use an in-memory storage for the tests
    // (otherwise it tries to call the platform channel and fails)
    FlutterSecureStorage.setMockInitialValues({});
  });

  group('AuthState (Epic 5 – auth + session restore)', () {
    test('successful login transitions to signedIn and stores token', () async {
      final auth = FakeAuthRepository();
      final state = AuthState(
        apiClient: ApiClient(),
        repository: auth,
        storage: TokenStorage(),
      );

      final ok = await state.login('felix.huber@example.ch', 'password');

      expect(ok, isTrue);
      expect(state.status, AuthStatus.signedIn);
      expect(state.user?.email, 'felix.huber@example.ch');
    });

    test('failed login keeps user signed out and exposes error message', () async {
      final auth = FakeAuthRepository(shouldFail: true);
      final state = AuthState(
        apiClient: ApiClient(),
        repository: auth,
        storage: TokenStorage(),
      );

      final ok = await state.login('felix@example.ch', 'wrong');

      expect(ok, isFalse);
      expect(state.status, isNot(AuthStatus.signedIn));
      expect(state.lastError, contains('Invalid'));
    });

    test('logout clears the session and token', () async {
      final auth = FakeAuthRepository();
      final state = AuthState(
        apiClient: ApiClient(),
        repository: auth,
        storage: TokenStorage(),
      );
      await state.login('felix.huber@example.ch', 'password');
      expect(state.status, AuthStatus.signedIn);

      await state.logout();

      expect(state.status, AuthStatus.signedOut);
      expect(state.user, isNull);
      expect(auth.logoutCalls, 1);
    });
  });
}
