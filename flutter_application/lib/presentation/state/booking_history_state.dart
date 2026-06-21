import 'package:flutter/foundation.dart';

import '../../data/api/api_exception.dart';
import '../../data/models/booking.dart';
import '../../data/repositories/booking_repository.dart';

enum HistoryStatus { idle, loading, loaded, error }
enum HistorySort { dateDesc, dateAsc, priceDesc, priceAsc }

class BookingHistoryState extends ChangeNotifier {
  BookingHistoryState(this._repo);

  final BookingRepository _repo;

  List<Booking> _all = const [];
  String? _airlineFilter;
  double? _maxPriceFilter;
  HistorySort _sort = HistorySort.dateDesc;
  HistoryStatus _status = HistoryStatus.idle;
  String? _error;

  HistoryStatus get status => _status;
  String? get error => _error;
  String? get airlineFilter => _airlineFilter;
  double? get maxPriceFilter => _maxPriceFilter;
  HistorySort get sort => _sort;

  List<Booking> get bookings {
    var list = [..._all];
    if (_airlineFilter != null && _airlineFilter!.isNotEmpty) {
      list = list.where((b) => b.flight.airline.iata == _airlineFilter).toList();
    }
    if (_maxPriceFilter != null) {
      list = list.where((b) => b.totalPrice <= _maxPriceFilter!).toList();
    }
    list.sort((a, b) {
      switch (_sort) {
        case HistorySort.dateDesc:
          return b.bookingDate.compareTo(a.bookingDate);
        case HistorySort.dateAsc:
          return a.bookingDate.compareTo(b.bookingDate);
        case HistorySort.priceDesc:
          return b.totalPrice.compareTo(a.totalPrice);
        case HistorySort.priceAsc:
          return a.totalPrice.compareTo(b.totalPrice);
      }
    });
    return list;
  }

  List<String> get airlineIatas =>
      _all.map((b) => b.flight.airline.iata).toSet().toList()..sort();

  Future<void> load() async {
    _status = HistoryStatus.loading;
    _error = null;
    notifyListeners();
    try {
      _all = await _repo.myBookings();
      _status = HistoryStatus.loaded;
    } on ApiException catch (e) {
      _error = e.message;
      _status = HistoryStatus.error;
    }
    notifyListeners();
  }

  void setAirlineFilter(String? iata) {
    _airlineFilter = (iata == null || iata.isEmpty) ? null : iata;
    notifyListeners();
  }

  void setMaxPriceFilter(double? value) {
    _maxPriceFilter = value;
    notifyListeners();
  }

  void setSort(HistorySort value) {
    _sort = value;
    notifyListeners();
  }
}
