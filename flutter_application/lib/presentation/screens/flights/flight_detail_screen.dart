import 'package:flutter/material.dart';

import '../../../core/utils/format.dart';
import '../../../data/models/flight.dart';

// platzhalter detail seite -> epic 2 (checkout) ersetzt das später durch
// den richtigen flow
class FlightDetailScreen extends StatelessWidget {
  const FlightDetailScreen({super.key, required this.flight});

  final Flight flight;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Scaffold(
      appBar: AppBar(title: Text(flight.flightNumber)),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(flight.airline.name, style: theme.textTheme.titleLarge),
            const SizedBox(height: 8),
            Text(
              '${flight.departure.iata} → ${flight.arrival.iata}',
              style: theme.textTheme.headlineSmall,
            ),
            const SizedBox(height: 16),
            Text('Abflug:  ${Fmt.dateTime(flight.departureTime)}'),
            Text('Ankunft: ${Fmt.dateTime(flight.arrivalTime)}'),
            Text('Dauer:   ${flight.durationLabel}'),
            const SizedBox(height: 16),
            Text('Preis:   ${Fmt.price(flight.price)}'),
            Text('Plätze:  ${flight.availableSeats} / ${flight.totalSeats}'),
            const Spacer(),
            FilledButton.icon(
              onPressed: flight.isAvailable ? () {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Checkout folgt im nächsten Schritt.')),
                );
              } : null,
              icon: const Icon(Icons.shopping_cart_checkout),
              label: const Text('Buchen'),
            ),
          ],
        ),
      ),
    );
  }
}
