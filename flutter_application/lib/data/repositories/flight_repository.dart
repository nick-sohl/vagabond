import '../api/api_client.dart';
import '../models/airline.dart';
import '../models/airport.dart';
import '../models/flight.dart';

class FlightFilters {
  const FlightFilters({
    this.departure,
    this.arrival,
    this.departDate,
    this.departTime,
    this.airline,
    this.maxPrice,
    this.onlyAvailable = false,
  });

  final String? departure;
  final String? arrival;
  final DateTime? departDate;
  final String? departTime;
  final String? airline;
  final double? maxPrice;
  final bool onlyAvailable;

  bool get isEmpty =>
      (departure ?? '').isEmpty &&
      (arrival ?? '').isEmpty &&
      departDate == null &&
      (departTime ?? '').isEmpty &&
      (airline ?? '').isEmpty &&
      maxPrice == null &&
      !onlyAvailable;

  Map<String, dynamic> toQuery() {
    // pad to 2 digits (so we get "2026-06-05" not "2026-6-5")
    String? two(int n) => n < 10 ? '0$n' : '$n';
    return {
      if ((departure ?? '').isNotEmpty) 'departure': departure,
      if ((arrival ?? '').isNotEmpty) 'arrival': arrival,
      if (departDate != null)
        'depart_date':
            '${departDate!.year}-${two(departDate!.month)}-${two(departDate!.day)}',
      if ((departTime ?? '').isNotEmpty) 'depart_time': departTime,
      if ((airline ?? '').isNotEmpty) 'airline': airline,
      if (maxPrice != null) 'max_price': maxPrice,
      if (onlyAvailable) 'availability': '1',
    };
  }

  FlightFilters copyWith({
    String? departure,
    String? arrival,
    DateTime? departDate,
    bool clearDepartDate = false,
    String? departTime,
    String? airline,
    double? maxPrice,
    bool clearMaxPrice = false,
    bool? onlyAvailable,
  }) {
    return FlightFilters(
      departure: departure ?? this.departure,
      arrival: arrival ?? this.arrival,
      departDate: clearDepartDate ? null : (departDate ?? this.departDate),
      departTime: departTime ?? this.departTime,
      airline: airline ?? this.airline,
      maxPrice: clearMaxPrice ? null : (maxPrice ?? this.maxPrice),
      onlyAvailable: onlyAvailable ?? this.onlyAvailable,
    );
  }
}

class FlightPage {
  FlightPage({
    required this.flights,
    required this.page,
    required this.perPage,
    required this.total,
    required this.totalPages,
  });

  final List<Flight> flights;
  final int page;
  final int perPage;
  final int total;
  final int totalPages;

  bool get hasMore => page < totalPages;
}

class FlightRepository {
  FlightRepository(this._api);

  final ApiClient _api;

  Future<FlightPage> search(
    FlightFilters filters, {
    int page = 1,
    int perPage = 20,
  }) async {
    final query = {
      ...filters.toQuery(),
      'page': page,
      'per_page': perPage,
    };
    final raw = await _api.get('/flights', query: query) as Map<String, dynamic>;
    final data = (raw['data'] as List<dynamic>)
        .map((e) => Flight.fromJson(e as Map<String, dynamic>))
        .toList(growable: false);
    final pagination = raw['pagination'] as Map<String, dynamic>;
    return FlightPage(
      flights: data,
      page: (pagination['page'] as num).toInt(),
      perPage: (pagination['per_page'] as num).toInt(),
      total: (pagination['total'] as num).toInt(),
      totalPages: (pagination['total_pages'] as num).toInt(),
    );
  }

  Future<Flight> findById(int id) async {
    final raw = await _api.get('/flights/show', query: {'id': id}) as Map<String, dynamic>;
    return Flight.fromJson(raw['data'] as Map<String, dynamic>);
  }

  Future<List<Airline>> airlines() async {
    final raw = await _api.get('/airlines') as Map<String, dynamic>;
    return (raw['data'] as List<dynamic>)
        .map((e) => Airline.fromJson(e as Map<String, dynamic>))
        .toList(growable: false);
  }

  Future<List<Airport>> airports({String? query}) async {
    final raw = await _api.get('/airports',
        query: (query == null || query.isEmpty) ? null : {'q': query}) as Map<String, dynamic>;
    return (raw['data'] as List<dynamic>)
        .map((e) => Airport.fromJson(e as Map<String, dynamic>))
        .toList(growable: false);
  }
}
