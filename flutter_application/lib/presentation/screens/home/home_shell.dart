import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../state/auth_state.dart';
import '../bookings/bookings_screen.dart';
import '../flights/flight_search_screen.dart';

// main screen nach login. tabs + appbar mit logo + account menu rechts.
// design soll an die webseite navbar.php erinnern -> weisser hintergrund,
// schwarzer border unten, fett "vagabond" als logo
class HomeShell extends StatefulWidget {
  const HomeShell({super.key});

  @override
  State<HomeShell> createState() => _HomeShellState();
}

class _HomeShellState extends State<HomeShell> {
  int _index = 0;

  static const _pages = [FlightSearchScreen(), BookingsScreen()];

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthState>();
    return Scaffold(
      appBar: AppBar(
        title: Row(
          mainAxisSize: MainAxisSize.min,
          children: const [
            Icon(Icons.flight, size: 20, color: Colors.black),
            SizedBox(width: 6),
            Text(
              'vagabond',
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.w900,
                letterSpacing: -0.5,
              ),
            ),
          ],
        ),
        actions: [
          PopupMenuButton<String>(
            icon: const Icon(Icons.account_circle_outlined, color: Colors.black),
            shape: const RoundedRectangleBorder(
              borderRadius: BorderRadius.zero,
              side: BorderSide(color: Colors.black, width: 1),
            ),
            color: Colors.white,
            elevation: 0,
            onSelected: (value) async {
              if (value == 'logout') {
                await context.read<AuthState>().logout();
              }
            },
            itemBuilder: (context) => [
              PopupMenuItem<String>(
                enabled: false,
                child: Text(
                  auth.user?.fullName ?? '',
                  style: const TextStyle(
                    fontWeight: FontWeight.w800,
                    color: Colors.black,
                  ),
                ),
              ),
              if (auth.user != null)
                PopupMenuItem<String>(
                  enabled: false,
                  child: Text(
                    auth.user!.email,
                    style: const TextStyle(fontSize: 12),
                  ),
                ),
              const PopupMenuDivider(),
              const PopupMenuItem<String>(
                value: 'logout',
                child: Row(children: [
                  Icon(Icons.logout, size: 16),
                  SizedBox(width: 8),
                  Text(
                    'LOGOUT',
                    style: TextStyle(
                      fontWeight: FontWeight.w800,
                      letterSpacing: 0.8,
                    ),
                  ),
                ]),
              ),
            ],
          ),
        ],
      ),
      body: IndexedStack(index: _index, children: _pages),
      bottomNavigationBar: DecoratedBox(
        // 1px schwarzer border oben -> matched die desktop navbar links
        decoration: const BoxDecoration(
          border: Border(top: BorderSide(color: Colors.black, width: 1)),
        ),
        child: NavigationBar(
          selectedIndex: _index,
          onDestinationSelected: (i) => setState(() => _index = i),
          destinations: const [
            NavigationDestination(
              icon: Icon(Icons.search, color: Colors.black),
              selectedIcon: Icon(Icons.search, color: Colors.white),
              label: 'FLIGHTS',
            ),
            NavigationDestination(
              icon: Icon(Icons.receipt_long_outlined, color: Colors.black),
              selectedIcon: Icon(Icons.receipt_long, color: Colors.white),
              label: 'BOOKINGS',
            ),
          ],
        ),
      ),
    );
  }
}
