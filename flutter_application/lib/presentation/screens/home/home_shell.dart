import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../state/auth_state.dart';
import '../bookings/bookings_screen.dart';
import '../flights/flight_search_screen.dart';

// main screen after login. just a tab bar with the 2 main screens:
//   1) Suchen        -> epic 1+2
//   2) Buchungen     -> epic 3
// account/logout sits in the appbar so it's reachable from both tabs
class HomeShell extends StatefulWidget {
  const HomeShell({super.key});

  @override
  State<HomeShell> createState() => _HomeShellState();
}

class _HomeShellState extends State<HomeShell> {
  int _index = 0;

  static const _titles = ['Flüge suchen', 'Meine Buchungen'];
  static const _pages = [FlightSearchScreen(), BookingsScreen()];

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthState>();
    return Scaffold(
      appBar: AppBar(
        title: Text(_titles[_index]),
        actions: [
          PopupMenuButton<String>(
            icon: const Icon(Icons.account_circle_outlined),
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
                  style: const TextStyle(fontWeight: FontWeight.w600),
                ),
              ),
              if (auth.user != null)
                PopupMenuItem<String>(
                  enabled: false,
                  child: Text(auth.user!.email),
                ),
              const PopupMenuDivider(),
              const PopupMenuItem<String>(
                value: 'logout',
                child: Row(children: [
                  Icon(Icons.logout, size: 18),
                  SizedBox(width: 8),
                  Text('Abmelden'),
                ]),
              ),
            ],
          ),
        ],
      ),
      body: IndexedStack(index: _index, children: _pages),
      bottomNavigationBar: NavigationBar(
        selectedIndex: _index,
        onDestinationSelected: (i) => setState(() => _index = i),
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.search),
            label: 'Suchen',
          ),
          NavigationDestination(
            icon: Icon(Icons.receipt_long_outlined),
            selectedIcon: Icon(Icons.receipt_long),
            label: 'Buchungen',
          ),
        ],
      ),
    );
  }
}
