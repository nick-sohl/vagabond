import 'package:flutter/foundation.dart';

import '../../data/api/api_exception.dart';
import '../../data/models/airline.dart';
import '../../data/models/airport.dart';
import '../../data/models/flight.dart';
import '../../data/repositories/flight_repository.dart';

enum SearchStatus { idle, loading, loaded, error }

// state for the search screen.
// keeps the current filters, dropdown data (airlines+airports), the
// current result page and the pagination info
class FlightSearchState extends ChangeNotifier {
  FlightSearchState(this._repo);

  final FlightRepository _repo;

  FlightFilters _filters = const FlightFilters();
  FlightFilters get filters => _filters;

  List<Flight> _flights = const [];
  List<Flight> get flights => _flights;

  List<Airline> _airlines = const [];
  List<Airline> get airlines => _airlines;

  List<Airport> _airports = const [];
  List<Airport> get airports => _airports;

  SearchStatus _status = SearchStatus.idle;
  SearchStatus get status => _status;

  String? _error;
  String? get error => _error;

  int _page = 1;
  int _totalPages = 1;
  int _total = 0;
  int get page => _page;
  int get totalPages => _totalPages;
  int get total => _total;

  bool get hasMore => _page < _totalPages;

  void updateFilters(FlightFilters next) {
    _filters = next;
    notifyListeners();
  }

  Future<void> loadReferenceData() async {
    if (_airlines.isNotEmpty && _airports.isNotEmpty) return;
    try {
      final results = await Future.wait([
        _repo.airlines(),
        _repo.airports(),
      ]);
      _airlines = results[0] as List<Airline>;
      _airports = results[1] as List<Airport>;
      notifyListeners();
    } on ApiException catch (e) {
      _error = e.message;
      notifyListeners();
    }
  }

  Future<void> search() async {
    _status = SearchStatus.loading;
    _error = null;
    _page = 1;
    notifyListeners();
    try {
      final page = await _repo.search(_filters, page: 1);
      _flights = page.flights;
      _page = page.page;
      _totalPages = page.totalPages;
      _total = page.total;
      _status = SearchStatus.loaded;
    } on ApiException catch (e) {
      _error = e.message;
      _status = SearchStatus.error;
    }
    notifyListeners();
  }

  Future<void> loadNextPage() async {
    if (!hasMore || _status == SearchStatus.loading) return;
    _status = SearchStatus.loading;
    notifyListeners();
    try {
      final next = await _repo.search(_filters, page: _page + 1);
      _flights = [..._flights, ...next.flights];
      _page = next.page;
      _totalPages = next.totalPages;
      _total = next.total;
      _status = SearchStatus.loaded;
    } on ApiException catch (e) {
      _error = e.message;
      _status = SearchStatus.error;
    }
    notifyListeners();
  }

  void reset() {
    _filters = const FlightFilters();
    _flights = const [];
    _page = 1;
    _totalPages = 1;
    _total = 0;
    _status = SearchStatus.idle;
    _error = null;
    notifyListeners();
  }
}
