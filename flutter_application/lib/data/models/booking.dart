import 'airline.dart';
import 'airport.dart';
import '../../core/utils/format.dart';

// flight info inside a booking is a bit smaller than the normal Flight
// (no seats / airplane info), so we have an own model for it.
// would be nice to reuse Flight but it would require nullables everywhere
class BookingFlight {
  BookingFlight({
    required this.id,
    required this.flightNumber,
    required this.departureTime,
    required this.arrivalTime,
    required this.price,
    required this.departure,
    required this.arrival,
    required this.airline,
  });

  final int id;
  final String flightNumber;
  final DateTime departureTime;
  final DateTime arrivalTime;
  final double price;
  final Airport departure;
  final Airport arrival;
  final Airline airline;

  factory BookingFlight.fromJson(Map<String, dynamic> json) => BookingFlight(
        id: (json['id'] as num).toInt(),
        flightNumber: json['flight_number'] as String,
        departureTime:
            Fmt.parseBackendDateTime(json['departure_time'] as String),
        arrivalTime: Fmt.parseBackendDateTime(json['arrival_time'] as String),
        price: (json['price'] as num).toDouble(),
        departure: Airport.fromJson(json['departure'] as Map<String, dynamic>),
        arrival: Airport.fromJson(json['arrival'] as Map<String, dynamic>),
        airline: Airline.fromJson(json['airline'] as Map<String, dynamic>),
      );
}

class Booking {
  Booking({
    required this.id,
    required this.bookingDate,
    required this.numTickets,
    required this.totalPrice,
    required this.status,
    required this.flight,
  });

  final int id;
  final DateTime bookingDate;
  final int numTickets;
  final double totalPrice;
  final String status;
  final BookingFlight flight;

  factory Booking.fromJson(Map<String, dynamic> json) => Booking(
        id: (json['id'] as num).toInt(),
        bookingDate: Fmt.parseBackendDateTime(json['booking_date'] as String),
        numTickets: (json['num_tickets'] as num).toInt(),
        totalPrice: (json['total_price'] as num).toDouble(),
        status: json['status'] as String? ?? 'confirmed',
        flight:
            BookingFlight.fromJson(json['flight'] as Map<String, dynamic>),
      );
}
