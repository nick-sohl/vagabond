import 'package:flutter/material.dart';

import '../../core/utils/format.dart';
import '../../data/models/flight.dart';

// one card for a flight (used in the search results).
// tap -> onTap (used for navigation to the detail screen)
class FlightCard extends StatelessWidget {
  const FlightCard({super.key, required this.flight, this.onTap});

  final Flight flight;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final available = flight.availableSeats;
    return Card(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(children: [
                _Pill(label: flight.airline.iata, color: theme.colorScheme.primary),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    flight.airline.name,
                    style: theme.textTheme.labelLarge,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
                Text(
                  flight.flightNumber,
                  style: theme.textTheme.labelMedium?.copyWith(
                    color: theme.colorScheme.outline,
                  ),
                ),
              ]),
              const SizedBox(height: 12),
              Row(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  _Endpoint(
                    iata: flight.departure.iata,
                    city: flight.departure.city,
                    time: Fmt.time(flight.departureTime),
                  ),
                  Expanded(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 8),
                      child: Column(children: [
                        Text(flight.durationLabel,
                            style: theme.textTheme.labelSmall),
                        const SizedBox(height: 4),
                        Stack(alignment: Alignment.center, children: [
                          Divider(color: theme.colorScheme.outlineVariant),
                          Icon(Icons.flight, size: 16, color: theme.colorScheme.primary),
                        ]),
                        const SizedBox(height: 4),
                        Text(Fmt.date(flight.departureTime),
                            style: theme.textTheme.labelSmall),
                      ]),
                    ),
                  ),
                  _Endpoint(
                    iata: flight.arrival.iata,
                    city: flight.arrival.city,
                    time: Fmt.time(flight.arrivalTime),
                    alignEnd: true,
                  ),
                ],
              ),
              const SizedBox(height: 12),
              Row(children: [
                Icon(
                  available > 0 ? Icons.event_seat : Icons.block,
                  size: 16,
                  color: available > 0
                      ? theme.colorScheme.primary
                      : theme.colorScheme.error,
                ),
                const SizedBox(width: 4),
                Text(
                  available > 0
                      ? 'Noch $available Plätze'
                      : 'Ausgebucht',
                  style: theme.textTheme.labelMedium,
                ),
                const Spacer(),
                Text(
                  Fmt.price(flight.price),
                  style: theme.textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: theme.colorScheme.primary,
                  ),
                ),
              ]),
            ],
          ),
        ),
      ),
    );
  }
}

class _Endpoint extends StatelessWidget {
  const _Endpoint({
    required this.iata,
    required this.city,
    required this.time,
    this.alignEnd = false,
  });

  final String iata;
  final String city;
  final String time;
  final bool alignEnd;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Column(
      crossAxisAlignment:
          alignEnd ? CrossAxisAlignment.end : CrossAxisAlignment.start,
      children: [
        Text(time, style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700)),
        Text(iata, style: theme.textTheme.labelLarge),
        Text(city,
            style: theme.textTheme.bodySmall?.copyWith(
              color: theme.colorScheme.outline,
            )),
      ],
    );
  }
}

class _Pill extends StatelessWidget {
  const _Pill({required this.label, required this.color});

  final String label;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.10),
        borderRadius: BorderRadius.circular(6),
      ),
      child: Text(
        label,
        style: TextStyle(color: color, fontWeight: FontWeight.w700, fontSize: 12),
      ),
    );
  }
}
