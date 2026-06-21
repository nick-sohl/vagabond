import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:provider/provider.dart';
import 'package:vagabond/data/api/api_exception.dart';
import 'package:vagabond/data/repositories/booking_repository.dart';
import 'package:vagabond/presentation/screens/flights/flight_detail_screen.dart';

import '_fakes.dart';

Widget _hostFor({
  required Widget child,
  required BookingRepository repository,
}) {
  return MaterialApp(
    home: Provider<BookingRepository>.value(value: repository, child: child),
  );
}

Future<void> _useTallSurface(WidgetTester tester) async {
  // default test screen is 800x600 -> too small, the checkout form
  // is longer than that and enterText fails on off-screen fields.
  // make it bigger so all fields are visible
  await tester.binding.setSurfaceSize(const Size(900, 1600));
  addTearDown(() => tester.binding.setSurfaceSize(null));
}

void main() {
  setUpAll(() async {
    await initializeDateFormatting('de_CH');
  });

  group('Checkout flow (Epic 2)', () {
    testWidgets('happy path: fill payment form -> booking created', (tester) async {
      await _useTallSurface(tester);
      final flight = buildFlight(availableSeats: 10);
      final repo = FakeBookingRepository();

      await tester.pumpWidget(_hostFor(
        repository: repo,
        child: CheckoutScreen(flight: flight, numTickets: 2),
      ));

      await tester.enterText(
          find.byKey(const Key('checkout.card_holder')), 'Felix Huber');
      await tester.enterText(
          find.byKey(const Key('checkout.card_number')), '4111111111111111');
      await tester.enterText(
          find.byKey(const Key('checkout.expiry')), '08/27');
      await tester.enterText(find.byKey(const Key('checkout.cvv')), '123');

      await tester.tap(find.byKey(const Key('checkout.pay')));
      await tester.pump();
      await tester.pump(const Duration(milliseconds: 50));

      expect(repo.createCalls, 1);
      expect(repo.lastFlightId, flight.id);
      expect(repo.lastNumTickets, 2);
    });

    testWidgets('shows backend error when seats are no longer available',
        (tester) async {
      await _useTallSurface(tester);
      final flight = buildFlight(availableSeats: 5);
      final repo = FakeBookingRepository(
        errorOnCreate: ApiException('Not enough seats available.', statusCode: 422),
      );

      await tester.pumpWidget(_hostFor(
        repository: repo,
        child: CheckoutScreen(flight: flight, numTickets: 1),
      ));

      await tester.enterText(
          find.byKey(const Key('checkout.card_holder')), 'Felix Huber');
      await tester.enterText(
          find.byKey(const Key('checkout.card_number')), '4111111111111111');
      await tester.enterText(find.byKey(const Key('checkout.expiry')), '08/27');
      await tester.enterText(find.byKey(const Key('checkout.cvv')), '123');

      await tester.tap(find.byKey(const Key('checkout.pay')));
      await tester.pump();
      await tester.pump(const Duration(milliseconds: 50));

      expect(find.text('Not enough seats available.'), findsOneWidget);
    });

    testWidgets('rejects invalid card number', (tester) async {
      await _useTallSurface(tester);
      final flight = buildFlight(availableSeats: 5);
      final repo = FakeBookingRepository();

      await tester.pumpWidget(_hostFor(
        repository: repo,
        child: CheckoutScreen(flight: flight, numTickets: 1),
      ));

      await tester.enterText(
          find.byKey(const Key('checkout.card_holder')), 'Felix Huber');
      await tester.enterText(
          find.byKey(const Key('checkout.card_number')), '123'); // too short
      await tester.enterText(find.byKey(const Key('checkout.expiry')), '08/27');
      await tester.enterText(find.byKey(const Key('checkout.cvv')), '123');

      await tester.tap(find.byKey(const Key('checkout.pay')));
      await tester.pump();

      expect(repo.createCalls, 0);
      expect(find.text('Ungültige Kartennummer.'), findsOneWidget);
    });
  });
}
