import 'package:flutter/material.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:provider/provider.dart';

import 'core/theme/app_theme.dart';
import 'data/api/api_client.dart';
import 'data/api/token_storage.dart';
import 'data/repositories/auth_repository.dart';
import 'data/repositories/booking_repository.dart';
import 'data/repositories/flight_repository.dart';
import 'presentation/screens/auth/login_screen.dart';
import 'presentation/screens/home/home_shell.dart';
import 'presentation/state/auth_state.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await initializeDateFormatting('de_CH');

  final apiClient = ApiClient();
  final tokenStorage = TokenStorage();
  final authRepository = AuthRepository(apiClient);
  final flightRepository = FlightRepository(apiClient);
  final bookingRepository = BookingRepository(apiClient);

  runApp(VagabondApp(
    apiClient: apiClient,
    tokenStorage: tokenStorage,
    authRepository: authRepository,
    flightRepository: flightRepository,
    bookingRepository: bookingRepository,
  ));
}

class VagabondApp extends StatelessWidget {
  const VagabondApp({
    super.key,
    required this.apiClient,
    required this.tokenStorage,
    required this.authRepository,
    required this.flightRepository,
    required this.bookingRepository,
  });

  final ApiClient apiClient;
  final TokenStorage tokenStorage;
  final AuthRepository authRepository;
  final FlightRepository flightRepository;
  final BookingRepository bookingRepository;

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        Provider.value(value: apiClient),
        Provider.value(value: flightRepository),
        Provider.value(value: bookingRepository),
        ChangeNotifierProvider(
          create: (_) => AuthState(
            apiClient: apiClient,
            repository: authRepository,
            storage: tokenStorage,
          )..restore(),
        ),
      ],
      child: MaterialApp(
        title: 'Vagabond',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.light(),
        home: const _RootGate(),
      ),
    );
  }
}

// shows either the login screen or the home shell, depending on AuthState.
// has to be at the top of the widget tree so a logout from anywhere
// will throw the user back to the login screen automatically
class _RootGate extends StatelessWidget {
  const _RootGate();

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthState>();
    switch (auth.status) {
      case AuthStatus.unknown:
        return const _Splash();
      case AuthStatus.signedOut:
        return const LoginScreen();
      case AuthStatus.signedIn:
        return const HomeShell();
    }
  }
}

class _Splash extends StatelessWidget {
  const _Splash();

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(child: CircularProgressIndicator()),
    );
  }
}
