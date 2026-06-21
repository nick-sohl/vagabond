import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../../core/theme/app_theme.dart';
import '../../../core/utils/format.dart';
import '../../../data/models/booking.dart';
import '../../../data/repositories/booking_repository.dart';
import '../../state/booking_history_state.dart';

// Epic 3: "Meine Buchungen".
// loads the bookings of the logged in user via /api/v1/bookings
// and shows them as a list with filter + sort options
class BookingsScreen extends StatelessWidget {
  const BookingsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (ctx) =>
          BookingHistoryState(ctx.read<BookingRepository>())..load(),
      child: const _BookingsView(),
    );
  }
}

class _BookingsView extends StatelessWidget {
  const _BookingsView();

  @override
  Widget build(BuildContext context) {
    final state = context.watch<BookingHistoryState>();
    return RefreshIndicator(
      onRefresh: state.load,
      child: ListView(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        children: [
          _Filters(state: state),
          const SizedBox(height: 8),
          if (state.status == HistoryStatus.loading)
            const Padding(
              padding: EdgeInsets.symmetric(vertical: 32),
              child: Center(child: CircularProgressIndicator()),
            )
          else if (state.status == HistoryStatus.error)
            Padding(
              padding: const EdgeInsets.all(16),
              child: Text(
                state.error ?? 'Buchungen konnten nicht geladen werden.',
                style: TextStyle(color: Theme.of(context).colorScheme.error),
                textAlign: TextAlign.center,
              ),
            )
          else if (state.bookings.isEmpty)
            const Padding(
              padding: EdgeInsets.all(24),
              child: Column(
                children: [
                  Icon(Icons.luggage_outlined, size: 48),
                  SizedBox(height: 12),
                  Text('Noch keine Buchungen.', textAlign: TextAlign.center),
                  SizedBox(height: 4),
                  Text('Sobald du einen Flug buchst, erscheint er hier.',
                      textAlign: TextAlign.center),
                ],
              ),
            )
          else
            ...state.bookings.map((b) => _BookingTile(booking: b)),
        ],
      ),
    );
  }
}

class _Filters extends StatefulWidget {
  const _Filters({required this.state});
  final BookingHistoryState state;

  @override
  State<_Filters> createState() => _FiltersState();
}

class _FiltersState extends State<_Filters> {
  final _maxPrice = TextEditingController();

  @override
  void initState() {
    super.initState();
    final v = widget.state.maxPriceFilter;
    if (v != null) _maxPrice.text = v.toStringAsFixed(0);
  }

  @override
  void dispose() {
    _maxPrice.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final state = widget.state;
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: BrutalCard(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(children: [
              Expanded(
                child: DropdownButtonFormField<String?>(
                  key: const Key('history.airline_filter'),
                  initialValue: state.airlineFilter,
                  decoration: const InputDecoration(
                    labelText: 'Fluggesellschaft',
                    prefixIcon: Icon(Icons.airplanemode_active),
                  ),
                  items: [
                    const DropdownMenuItem<String?>(value: null, child: Text('Alle')),
                    ...state.airlineIatas.map((iata) =>
                        DropdownMenuItem<String?>(value: iata, child: Text(iata))),
                  ],
                  onChanged: state.setAirlineFilter,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: TextField(
                  key: const Key('history.max_price'),
                  controller: _maxPrice,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(
                    labelText: 'Max. Preis (CHF)',
                    prefixIcon: Icon(Icons.payments_outlined),
                  ),
                  onChanged: (value) {
                    final parsed = double.tryParse(value);
                    state.setMaxPriceFilter(value.isEmpty ? null : parsed);
                  },
                ),
              ),
            ]),
            const SizedBox(height: 10),
            DropdownButtonFormField<HistorySort>(
              key: const Key('history.sort'),
              initialValue: state.sort,
              decoration: const InputDecoration(
                labelText: 'Sortierung',
                prefixIcon: Icon(Icons.sort),
              ),
              items: const [
                DropdownMenuItem(value: HistorySort.dateDesc, child: Text('Datum (neueste zuerst)')),
                DropdownMenuItem(value: HistorySort.dateAsc, child: Text('Datum (älteste zuerst)')),
                DropdownMenuItem(value: HistorySort.priceDesc, child: Text('Preis (hoch → niedrig)')),
                DropdownMenuItem(value: HistorySort.priceAsc, child: Text('Preis (niedrig → hoch)')),
              ],
              onChanged: (value) {
                if (value != null) state.setSort(value);
              },
            ),
          ],
        ),
      ),
    );
  }
}

// einzelne buchung als card. layout an booking-card.php auf der webseite
// angelehnt: airline + flight nr + status pill oben, riesige IATA codes
// in der mitte, datum + preis unten
class _BookingTile extends StatelessWidget {
  const _BookingTile({required this.booking});
  final Booking booking;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: BrutalCard(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(children: [
              Expanded(
                child: Wrap(
                  crossAxisAlignment: WrapCrossAlignment.center,
                  spacing: 6,
                  children: [
                    Text(
                      booking.flight.airline.name,
                      style: const TextStyle(
                        fontWeight: FontWeight.w800,
                        fontSize: 14,
                      ),
                    ),
                    Text(
                      booking.flight.flightNumber,
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppTheme.textMuted,
                      ),
                    ),
                  ],
                ),
              ),
              _statusPill(booking.status),
            ]),
            const SizedBox(height: 16),
            Row(children: [
              _Big(text: booking.flight.departure.iata),
              const SizedBox(width: 8),
              const Expanded(
                child: Center(
                  child: SizedBox(
                    height: 1,
                    child: ColoredBox(color: Color(0xFFD1D5DB)),
                  ),
                ),
              ),
              const SizedBox(width: 8),
              _Big(text: booking.flight.arrival.iata),
            ]),
            const SizedBox(height: 4),
            Row(children: [
              Expanded(
                child: Text(
                  booking.flight.departure.city,
                  style: const TextStyle(color: AppTheme.textMuted, fontSize: 13),
                ),
              ),
              Text(
                booking.flight.arrival.city,
                style: const TextStyle(color: AppTheme.textMuted, fontSize: 13),
              ),
            ]),
            const SizedBox(height: 12),
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
                    Fmt.dateTime(booking.flight.departureTime),
                    style: const TextStyle(color: AppTheme.textMuted, fontSize: 13),
                  ),
                ),
                Text(
                  '${booking.numTickets}× ',
                  style: const TextStyle(color: AppTheme.textMuted, fontSize: 13),
                ),
                Text(
                  Fmt.price(booking.totalPrice),
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w900,
                  ),
                ),
              ]),
            ),
          ],
        ),
      ),
    );
  }

  Widget _statusPill(String status) {
    return switch (status) {
      'confirmed' => const StatusPill(
          label: 'confirmed',
          color: StatusPill.successText,
          background: StatusPill.successBg,
        ),
      'pending' => const StatusPill(
          label: 'pending',
          color: StatusPill.warnText,
          background: StatusPill.warnBg,
        ),
      'cancelled' => const StatusPill(
          label: 'cancelled',
          color: StatusPill.dangerText,
          background: StatusPill.dangerBg,
        ),
      _ => StatusPill(
          label: status,
          color: StatusPill.mutedText,
          background: StatusPill.mutedBg,
        ),
    };
  }
}

class _Big extends StatelessWidget {
  const _Big({required this.text});
  final String text;
  @override
  Widget build(BuildContext context) {
    return Text(
      text,
      style: const TextStyle(
        fontSize: 26,
        fontWeight: FontWeight.w900,
        height: 1.1,
      ),
    );
  }
}
