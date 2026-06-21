import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../../core/utils/format.dart';
import '../../../data/api/api_exception.dart';
import '../../../data/models/flight.dart';
import '../../../data/repositories/booking_repository.dart';
import '../../widgets/flight_card.dart';
import 'booking_success_screen.dart';

// Epic 2: flight detail + checkout.
// 3 steps:
//   1. detail with +/- for number of tickets
//   2. payment form (just a mock - we don't send card data to the backend)
//   3. confirmation screen (BookingSuccessScreen)
class FlightDetailScreen extends StatefulWidget {
  const FlightDetailScreen({super.key, required this.flight});

  final Flight flight;

  @override
  State<FlightDetailScreen> createState() => _FlightDetailScreenState();
}

class _FlightDetailScreenState extends State<FlightDetailScreen> {
  int _tickets = 1;

  @override
  Widget build(BuildContext context) {
    final flight = widget.flight;
    final theme = Theme.of(context);
    final total = flight.price * _tickets;
    return Scaffold(
      appBar: AppBar(title: Text(flight.flightNumber)),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          FlightCard(flight: flight),
          const SizedBox(height: 20),
          Text('Details', style: theme.textTheme.titleMedium),
          const SizedBox(height: 8),
          _InfoRow('Abflug', Fmt.dateTime(flight.departureTime)),
          _InfoRow('Ankunft', Fmt.dateTime(flight.arrivalTime)),
          _InfoRow('Dauer', flight.durationLabel),
          if (flight.airplane?.model != null)
            _InfoRow(
              'Flugzeug',
              '${flight.airplane!.manufacturer ?? ''} ${flight.airplane!.model}'.trim(),
            ),
          _InfoRow('Verfügbare Plätze', '${flight.availableSeats} / ${flight.totalSeats}'),
          const Divider(height: 32),
          Text('Wie viele Tickets?', style: theme.textTheme.titleMedium),
          const SizedBox(height: 8),
          Row(children: [
            IconButton.filledTonal(
              key: const Key('detail.tickets.minus'),
              onPressed: _tickets > 1 ? () => setState(() => _tickets--) : null,
              icon: const Icon(Icons.remove),
            ),
            const SizedBox(width: 12),
            Text('$_tickets', style: theme.textTheme.headlineSmall),
            const SizedBox(width: 12),
            IconButton.filledTonal(
              key: const Key('detail.tickets.plus'),
              onPressed: _tickets < flight.availableSeats
                  ? () => setState(() => _tickets++)
                  : null,
              icon: const Icon(Icons.add),
            ),
            const Spacer(),
            Text(
              Fmt.price(total),
              style: theme.textTheme.titleLarge?.copyWith(
                color: theme.colorScheme.primary,
                fontWeight: FontWeight.w700,
              ),
            ),
          ]),
          const SizedBox(height: 20),
          FilledButton.icon(
            key: const Key('detail.continue_checkout'),
            onPressed: flight.isAvailable
                ? () => Navigator.of(context).push(MaterialPageRoute(
                      builder: (_) => CheckoutScreen(
                        flight: flight,
                        numTickets: _tickets,
                      ),
                    ))
                : null,
            icon: const Icon(Icons.shopping_cart_checkout),
            label: const Text('Zur Kasse'),
          ),
          if (!flight.isAvailable)
            Padding(
              padding: const EdgeInsets.only(top: 12),
              child: Text(
                'Dieser Flug ist leider ausgebucht.',
                style: TextStyle(color: theme.colorScheme.error),
                textAlign: TextAlign.center,
              ),
            ),
        ],
      ),
    );
  }
}

class _InfoRow extends StatelessWidget {
  const _InfoRow(this.label, this.value);
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 140,
            child: Text(label, style: theme.textTheme.labelLarge),
          ),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }
}

// fake payment form. we never send the card info to the backend
// (the API only wants flight_id + num_tickets). but the assignment asks
// for a "Formular für Zahlungsdetails", so we at least collect + validate
// it locally so the UX feels real
class CheckoutScreen extends StatefulWidget {
  const CheckoutScreen({
    super.key,
    required this.flight,
    required this.numTickets,
  });

  final Flight flight;
  final int numTickets;

  @override
  State<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends State<CheckoutScreen> {
  final _formKey = GlobalKey<FormState>();
  final _cardHolder = TextEditingController();
  final _cardNumber = TextEditingController();
  final _expiry = TextEditingController();
  final _cvv = TextEditingController();
  bool _busy = false;
  String? _error;

  @override
  void dispose() {
    _cardHolder.dispose();
    _cardNumber.dispose();
    _expiry.dispose();
    _cvv.dispose();
    super.dispose();
  }

  Future<void> _book() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() {
      _busy = true;
      _error = null;
    });
    final repo = context.read<BookingRepository>();
    try {
      final bookingId = await repo.create(
        flightId: widget.flight.id,
        numTickets: widget.numTickets,
      );
      if (!mounted) return;
      Navigator.of(context).pushAndRemoveUntil(
        MaterialPageRoute(
          builder: (_) => BookingSuccessScreen(
            bookingId: bookingId,
            flight: widget.flight,
            numTickets: widget.numTickets,
          ),
        ),
        (route) => route.isFirst,
      );
    } on ApiException catch (e) {
      setState(() {
        _error = e.message;
        _busy = false;
      });
    }
  }

  String? _required(String? value) =>
      (value == null || value.trim().isEmpty) ? 'Pflichtfeld' : null;

  @override
  Widget build(BuildContext context) {
    final total = widget.flight.price * widget.numTickets;
    final theme = Theme.of(context);
    return Scaffold(
      appBar: AppBar(title: const Text('Kasse')),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(14),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Bestellübersicht',
                            style: theme.textTheme.titleMedium),
                        const SizedBox(height: 8),
                        Text(
                          '${widget.flight.airline.name} ${widget.flight.flightNumber}',
                          style: theme.textTheme.bodyLarge,
                        ),
                        Text(
                          '${widget.flight.departure.iata} → ${widget.flight.arrival.iata}',
                        ),
                        Text(Fmt.dateTime(widget.flight.departureTime)),
                        const Divider(),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text('${widget.numTickets} × ${Fmt.price(widget.flight.price)}'),
                            Text(
                              Fmt.price(total),
                              style: theme.textTheme.titleLarge?.copyWith(
                                color: theme.colorScheme.primary,
                                fontWeight: FontWeight.w700,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                Text('Zahlungsdetails', style: theme.textTheme.titleMedium),
                const SizedBox(height: 8),
                TextFormField(
                  key: const Key('checkout.card_holder'),
                  controller: _cardHolder,
                  decoration: const InputDecoration(labelText: 'Karteninhaber'),
                  validator: _required,
                ),
                const SizedBox(height: 10),
                TextFormField(
                  key: const Key('checkout.card_number'),
                  controller: _cardNumber,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(labelText: 'Kartennummer'),
                  validator: (value) {
                    if (value == null || value.trim().isEmpty) return 'Pflichtfeld';
                    final digits = value.replaceAll(RegExp(r'\D'), '');
                    if (digits.length < 13 || digits.length > 19) {
                      return 'Ungültige Kartennummer.';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 10),
                Row(children: [
                  Expanded(
                    child: TextFormField(
                      key: const Key('checkout.expiry'),
                      controller: _expiry,
                      decoration: const InputDecoration(
                        labelText: 'Gültig bis',
                        hintText: 'MM/JJ',
                      ),
                      validator: (value) => (value == null ||
                              !RegExp(r'^(0[1-9]|1[0-2])/[0-9]{2}$').hasMatch(value))
                          ? 'MM/JJ'
                          : null,
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: TextFormField(
                      key: const Key('checkout.cvv'),
                      controller: _cvv,
                      keyboardType: TextInputType.number,
                      obscureText: true,
                      decoration: const InputDecoration(labelText: 'CVV'),
                      validator: (value) => (value == null ||
                              !RegExp(r'^[0-9]{3,4}$').hasMatch(value))
                          ? 'CVV'
                          : null,
                    ),
                  ),
                ]),
                if (_error != null) ...[
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: theme.colorScheme.errorContainer,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Row(children: [
                      Icon(Icons.error_outline,
                          color: theme.colorScheme.error),
                      const SizedBox(width: 8),
                      Expanded(child: Text(_error!)),
                    ]),
                  ),
                ],
                const SizedBox(height: 20),
                FilledButton.icon(
                  key: const Key('checkout.pay'),
                  onPressed: _busy ? null : _book,
                  icon: _busy
                      ? const SizedBox(
                          height: 16,
                          width: 16,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            color: Colors.white,
                          ),
                        )
                      : const Icon(Icons.lock_outline),
                  label: Text(_busy
                      ? 'Wird verarbeitet…'
                      : 'Kostenpflichtig buchen – ${Fmt.price(total)}'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
