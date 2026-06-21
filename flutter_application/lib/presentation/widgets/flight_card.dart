import 'package:flutter/material.dart';

import '../../core/theme/app_theme.dart';
import '../../core/utils/format.dart';
import '../../data/models/flight.dart';

// flugkarte. soll genau gleich aussehen wie auf der webseite
// (flight-card.php): grosser IATA code links/rechts, in der mitte ein
// strich mit pfeil + duration, oben status pill, unten preis + buchen button.
class FlightCard extends StatelessWidget {
  const FlightCard({super.key, required this.flight, this.onTap});

  final Flight flight;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final available = flight.availableSeats;
    return BrutalCard(
      onTap: onTap,
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // header: airline name + flight number + seats pill
          Row(children: [
            Expanded(
              child: Wrap(
                crossAxisAlignment: WrapCrossAlignment.center,
                spacing: 6,
                children: [
                  Text(
                    flight.airline.name,
                    style: theme.textTheme.titleSmall?.copyWith(
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  Text(
                    flight.flightNumber,
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppTheme.textMuted,
                    ),
                  ),
                ],
              ),
            ),
            StatusPill(
              label: available > 0 ? '$available seats' : 'Sold out',
              color: available > 0 ? StatusPill.successText : StatusPill.dangerText,
              background: available > 0 ? StatusPill.successBg : StatusPill.dangerBg,
            ),
          ]),
          const SizedBox(height: 16),

          // mitte: IATA gross links, strich + pfeil in der mitte, IATA gross rechts
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
                    Text(
                      flight.durationLabel,
                      style: const TextStyle(
                        fontSize: 11,
                        color: Color(0xFF9CA3AF), // gray-400
                      ),
                    ),
                    const SizedBox(height: 6),
                    Row(children: [
                      const Expanded(
                        child: SizedBox(
                          height: 1,
                          child: ColoredBox(color: Color(0xFFD1D5DB)),
                        ),
                      ),
                      CustomPaint(
                        size: const Size(8, 8),
                        painter: _ArrowPainter(),
                      ),
                    ]),
                    const SizedBox(height: 6),
                    if (flight.airplane?.model != null)
                      Text(
                        flight.airplane!.model!,
                        style: const TextStyle(
                          fontSize: 11,
                          color: Color(0xFF9CA3AF),
                        ),
                      ),
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

          const SizedBox(height: 16),
          // footer: datum links, preis + buchen button rechts.
          // die top-border simuliert das border-t border-gray-100 vom web
          Container(
            padding: const EdgeInsets.only(top: 12),
            decoration: const BoxDecoration(
              border: Border(
                top: BorderSide(color: Color(0xFFE5E7EB), width: 1),
              ),
            ),
            child: Row(children: [
              Expanded(
                child: Text(
                  Fmt.date(flight.departureTime),
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppTheme.textMuted,
                  ),
                ),
              ),
              Text(
                Fmt.price(flight.price),
                style: theme.textTheme.titleLarge?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: Colors.black,
                ),
              ),
            ]),
          ),
        ],
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
    return Column(
      crossAxisAlignment:
          alignEnd ? CrossAxisAlignment.end : CrossAxisAlignment.start,
      children: [
        Text(
          iata,
          style: const TextStyle(
            fontSize: 26,
            fontWeight: FontWeight.w900,
            color: Colors.black,
            height: 1.1,
          ),
        ),
        const SizedBox(height: 2),
        Text(
          city,
          style: const TextStyle(
            fontSize: 13,
            color: AppTheme.textMuted,
          ),
        ),
        Text(
          time,
          style: const TextStyle(
            fontSize: 12,
            color: Color(0xFF9CA3AF),
          ),
        ),
      ],
    );
  }
}

// kleiner pfeil nach rechts wie auf der webseite (border-l-6 trick mit divs)
class _ArrowPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()..color = const Color(0xFFD1D5DB);
    final path = Path()
      ..moveTo(0, 0)
      ..lineTo(size.width, size.height / 2)
      ..lineTo(0, size.height)
      ..close();
    canvas.drawPath(path, paint);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
