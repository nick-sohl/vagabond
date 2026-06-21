import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

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
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
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

class _BookingTile extends StatelessWidget {
  const _BookingTile({required this.booking});
  final Booking booking;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: BoxDecoration(
                  color: theme.colorScheme.primary.withValues(alpha: 0.10),
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Text(
                  '#${booking.id}',
                  style: TextStyle(
                    color: theme.colorScheme.primary,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              Text(booking.flight.airline.name, style: theme.textTheme.labelLarge),
              const Spacer(),
              _StatusChip(status: booking.status),
            ]),
            const SizedBox(height: 8),
            Text(
              '${booking.flight.departure.iata} → ${booking.flight.arrival.iata}',
              style: theme.textTheme.headlineSmall,
            ),
            Text(Fmt.dateTime(booking.flight.departureTime)),
            const SizedBox(height: 8),
            Row(children: [
              Icon(Icons.calendar_month, size: 16, color: theme.colorScheme.outline),
              const SizedBox(width: 4),
              Text('Gebucht am ${Fmt.date(booking.bookingDate)}',
                  style: theme.textTheme.labelMedium),
              const Spacer(),
              Text(
                '${booking.numTickets}× ${Fmt.price(booking.totalPrice / booking.numTickets)}',
                style: theme.textTheme.labelMedium,
              ),
              const SizedBox(width: 8),
              Text(
                Fmt.price(booking.totalPrice),
                style: theme.textTheme.titleMedium?.copyWith(
                  color: theme.colorScheme.primary,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ]),
          ],
        ),
      ),
    );
  }
}

class _StatusChip extends StatelessWidget {
  const _StatusChip({required this.status});
  final String status;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final (label, color) = switch (status) {
      'confirmed' => ('bestätigt', theme.colorScheme.primary),
      'pending'   => ('ausstehend', Colors.orange.shade700),
      _           => (status, theme.colorScheme.outline),
    };
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(6),
      ),
      child: Text(label,
          style: TextStyle(color: color, fontWeight: FontWeight.w600, fontSize: 12)),
    );
  }
}
