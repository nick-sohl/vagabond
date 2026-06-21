import 'package:flutter_test/flutter_test.dart';
import 'package:vagabond/data/api/api_exception.dart';
import 'package:vagabond/data/repositories/flight_repository.dart';
import 'package:vagabond/presentation/state/flight_search_state.dart';

import '_fakes.dart';

void main() {
  group('FlightSearchState (Epic 1)', () {
    test('search forwards the current filters and stores results', () async {
      final repo = FakeFlightRepository(flights: [
        buildFlight(id: 1, departure: 'ZRH', arrival: 'LHR', price: 200),
        buildFlight(id: 2, departure: 'ZRH', arrival: 'CDG', price: 300),
      ]);
      final state = FlightSearchState(repo);
      state.updateFilters(const FlightFilters(departure: 'ZRH', arrival: 'LHR'));

      await state.search();

      expect(repo.searchCount, 1);
      expect(repo.lastFilters?.departure, 'ZRH');
      expect(repo.lastFilters?.arrival, 'LHR');
      expect(state.status, SearchStatus.loaded);
      expect(state.flights.length, 1);
      expect(state.flights.first.id, 1);
    });

    test('availability switch drops sold-out flights', () async {
      final repo = FakeFlightRepository(flights: [
        buildFlight(id: 1, availableSeats: 0),
        buildFlight(id: 2, availableSeats: 12),
      ]);
      final state = FlightSearchState(repo)
        ..updateFilters(const FlightFilters(onlyAvailable: true));

      await state.search();

      expect(state.flights.length, 1);
      expect(state.flights.first.id, 2);
    });

    test('max-price filter trims expensive flights', () async {
      final repo = FakeFlightRepository(flights: [
        buildFlight(id: 1, price: 100),
        buildFlight(id: 2, price: 250),
        buildFlight(id: 3, price: 500),
      ]);
      final state = FlightSearchState(repo)
        ..updateFilters(const FlightFilters(maxPrice: 300));

      await state.search();

      expect(state.flights.map((f) => f.id), [1, 2]);
    });

    test('API errors surface as SearchStatus.error', () async {
      final repo = FakeFlightRepository(
        flights: const [],
        errorOnSearch: ApiException('boom'),
      );
      final state = FlightSearchState(repo);

      await state.search();

      expect(state.status, SearchStatus.error);
      expect(state.error, 'boom');
    });
  });
}
