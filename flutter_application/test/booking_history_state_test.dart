import 'package:flutter_test/flutter_test.dart';
import 'package:vagabond/presentation/state/booking_history_state.dart';

import '_fakes.dart';

void main() {
  group('BookingHistoryState (Epic 3)', () {
    final swissCheap = buildBooking(
      id: 1,
      totalPrice: 120,
      bookingDate: DateTime(2026, 3, 1),
      flight: buildFlight(id: 10, airlineIata: 'LX', airlineName: 'Swiss'),
    );
    final luftDear = buildBooking(
      id: 2,
      totalPrice: 980,
      bookingDate: DateTime(2026, 4, 5),
      flight: buildFlight(id: 11, airlineIata: 'LH', airlineName: 'Lufthansa'),
    );
    final swissMid = buildBooking(
      id: 3,
      totalPrice: 480,
      bookingDate: DateTime(2026, 5, 10),
      flight: buildFlight(id: 12, airlineIata: 'LX', airlineName: 'Swiss'),
    );

    test('loads bookings and sorts by date desc by default', () async {
      final repo = FakeBookingRepository(bookings: [swissCheap, luftDear, swissMid]);
      final state = BookingHistoryState(repo);

      await state.load();

      expect(state.status, HistoryStatus.loaded);
      expect(state.bookings.map((b) => b.id), [3, 2, 1]);
    });

    test('airline filter narrows results', () async {
      final repo = FakeBookingRepository(bookings: [swissCheap, luftDear, swissMid]);
      final state = BookingHistoryState(repo);
      await state.load();

      state.setAirlineFilter('LX');

      expect(state.bookings.map((b) => b.id), [3, 1]);
    });

    test('price filter and sort combine correctly', () async {
      final repo = FakeBookingRepository(bookings: [swissCheap, luftDear, swissMid]);
      final state = BookingHistoryState(repo);
      await state.load();

      state.setMaxPriceFilter(500);
      state.setSort(HistorySort.priceAsc);

      expect(state.bookings.map((b) => b.id), [1, 3]);
    });
  });
}
