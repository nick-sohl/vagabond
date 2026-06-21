import 'package:flutter/material.dart';

import '../../../core/utils/format.dart';
import '../../../data/models/flight.dart';

class BookingSuccessScreen extends StatelessWidget {
  const BookingSuccessScreen({
    super.key,
    required this.bookingId,
    required this.flight,
    required this.numTickets,
  });

  final int bookingId;
  final Flight flight;
  final int numTickets;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final total = flight.price * numTickets;
    return Scaffold(
      appBar: AppBar(
        title: const Text('Buchung bestätigt'),
        automaticallyImplyLeading: false,
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const SizedBox(height: 16),
              Center(
                child: Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: theme.colorScheme.primary.withValues(alpha: 0.10),
                    shape: BoxShape.circle,
                  ),
                  child: Icon(Icons.check_rounded,
                      size: 64, color: theme.colorScheme.primary),
                ),
              ),
              const SizedBox(height: 16),
              Text('Vielen Dank!',
                  style: theme.textTheme.headlineSmall,
                  textAlign: TextAlign.center),
              const SizedBox(height: 4),
              Text('Deine Buchung wurde erfolgreich gespeichert.',
                  style: theme.textTheme.bodyMedium,
                  textAlign: TextAlign.center),
              const SizedBox(height: 24),
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Buchungsnummer',
                          style: theme.textTheme.labelMedium),
                      Text('#$bookingId',
                          style: theme.textTheme.headlineSmall?.copyWith(
                            fontWeight: FontWeight.w700,
                          )),
                      const Divider(),
                      Text('${flight.airline.name} ${flight.flightNumber}'),
                      Text(
                        '${flight.departure.iata} → ${flight.arrival.iata}'
                        ' • ${Fmt.dateTime(flight.departureTime)}',
                      ),
                      Text('$numTickets × ${Fmt.price(flight.price)} = ${Fmt.price(total)}'),
                    ],
                  ),
                ),
              ),
              const Spacer(),
              FilledButton(
                onPressed: () => Navigator.of(context).popUntil((r) => r.isFirst),
                child: const Text('Zurück zur Übersicht'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
