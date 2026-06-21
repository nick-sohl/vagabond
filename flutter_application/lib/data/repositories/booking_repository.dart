import '../api/api_client.dart';
import '../models/booking.dart';

class BookingRepository {
  BookingRepository(this._api);

  final ApiClient _api;

  Future<List<Booking>> myBookings({String? airlineIata, double? maxPrice}) async {
    final query = <String, dynamic>{
      if (airlineIata != null && airlineIata.isNotEmpty) 'airline': airlineIata,
      'max_price': ?maxPrice,
    };
    final raw = await _api.get('/bookings', query: query.isEmpty ? null : query)
        as Map<String, dynamic>;
    return (raw['data'] as List<dynamic>)
        .map((e) => Booking.fromJson(e as Map<String, dynamic>))
        .toList(growable: false);
  }

  Future<int> create({required int flightId, required int numTickets}) async {
    final raw = await _api.post('/bookings', {
      'flight_id': flightId,
      'num_tickets': numTickets,
    }) as Map<String, dynamic>;
    return (raw['booking_id'] as num).toInt();
  }
}
