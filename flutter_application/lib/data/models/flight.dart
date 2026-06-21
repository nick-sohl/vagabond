import 'airline.dart';
import 'airport.dart';
import '../../core/utils/format.dart';

class Airplane {
  Airplane({this.model, this.manufacturer});

  final String? model;
  final String? manufacturer;

  factory Airplane.fromJson(Map<String, dynamic> json) => Airplane(
        model: json['model'] as String?,
        manufacturer: json['manufacturer'] as String?,
      );
}

class Flight {
  Flight({
    required this.id,
    required this.flightNumber,
    required this.departureTime,
    required this.arrivalTime,
    required this.price,
    required this.availableSeats,
    required this.totalSeats,
    required this.departure,
    required this.arrival,
    required this.airline,
    this.airplane,
  });

  final int id;
  final String flightNumber;
  final DateTime departureTime;
  final DateTime arrivalTime;
  final double price;
  final int availableSeats;
  final int totalSeats;
  final Airport departure;
  final Airport arrival;
  final Airline airline;
  final Airplane? airplane;

  Duration get duration => arrivalTime.difference(departureTime);
  bool get isAvailable => availableSeats > 0;

  String get durationLabel => Fmt.duration(duration);

  factory Flight.fromJson(Map<String, dynamic> json) => Flight(
        id: (json['id'] as num).toInt(),
        flightNumber: json['flight_number'] as String,
        departureTime: Fmt.parseBackendDateTime(json['departure_time'] as String),
        arrivalTime: Fmt.parseBackendDateTime(json['arrival_time'] as String),
        price: (json['price'] as num).toDouble(),
        availableSeats: (json['available_seats'] as num).toInt(),
        totalSeats: (json['total_seats'] as num).toInt(),
        departure: Airport.fromJson(json['departure'] as Map<String, dynamic>),
        arrival: Airport.fromJson(json['arrival'] as Map<String, dynamic>),
        airline: Airline.fromJson(json['airline'] as Map<String, dynamic>),
        airplane: json['airplane'] is Map<String, dynamic>
            ? Airplane.fromJson(json['airplane'] as Map<String, dynamic>)
            : null,
      );
}
