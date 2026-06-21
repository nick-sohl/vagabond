import 'package:vagabond/data/api/api_exception.dart';
import 'package:vagabond/data/models/airline.dart';
import 'package:vagabond/data/models/airport.dart';
import 'package:vagabond/data/models/booking.dart';
import 'package:vagabond/data/models/flight.dart';
import 'package:vagabond/data/models/user.dart';
import 'package:vagabond/data/repositories/auth_repository.dart';
import 'package:vagabond/data/repositories/booking_repository.dart';
import 'package:vagabond/data/repositories/flight_repository.dart';

Flight buildFlight({
  int id = 1,
  String flightNumber = 'LX318',
  String departure = 'ZRH',
  String arrival = 'LHR',
  String airlineIata = 'LX',
  String airlineName = 'Swiss',
  double price = 245.5,
  int availableSeats = 36,
  int totalSeats = 180,
  DateTime? departureTime,
}) {
  final dep = departureTime ?? DateTime(2026, 7, 15, 7, 30);
  return Flight(
    id: id,
    flightNumber: flightNumber,
    departureTime: dep,
    arrivalTime: dep.add(const Duration(hours: 1, minutes: 15)),
    price: price,
    availableSeats: availableSeats,
    totalSeats: totalSeats,
    departure: Airport(iata: departure, city: 'Zürich'),
    arrival: Airport(iata: arrival, city: 'London'),
    airline: Airline(iata: airlineIata, name: airlineName),
  );
}

Booking buildBooking({
  int id = 1,
  int numTickets = 1,
  double totalPrice = 245.5,
  String status = 'confirmed',
  DateTime? bookingDate,
  Flight? flight,
}) {
  final f = flight ?? buildFlight();
  return Booking(
    id: id,
    bookingDate: bookingDate ?? DateTime(2026, 6, 1, 10),
    numTickets: numTickets,
    totalPrice: totalPrice,
    status: status,
    flight: BookingFlight(
      id: f.id,
      flightNumber: f.flightNumber,
      departureTime: f.departureTime,
      arrivalTime: f.arrivalTime,
      price: f.price,
      departure: f.departure,
      arrival: f.arrival,
      airline: f.airline,
    ),
  );
}

class FakeFlightRepository implements FlightRepository {
  FakeFlightRepository({
    this.flights = const [],
    this.airlinesData = const [],
    this.airportsData = const [],
    this.errorOnSearch,
  });

  final List<Flight> flights;
  final List<Airline> airlinesData;
  final List<Airport> airportsData;
  final ApiException? errorOnSearch;

  FlightFilters? lastFilters;
  int searchCount = 0;

  @override
  Future<FlightPage> search(FlightFilters filters, {int page = 1, int perPage = 20}) async {
    searchCount++;
    lastFilters = filters;
    if (errorOnSearch != null) throw errorOnSearch!;
    final filtered = flights.where((f) {
      if ((filters.departure ?? '').isNotEmpty &&
          f.departure.iata != filters.departure) {
        return false;
      }
      if ((filters.arrival ?? '').isNotEmpty &&
          f.arrival.iata != filters.arrival) {
        return false;
      }
      if (filters.maxPrice != null && f.price > filters.maxPrice!) {
        return false;
      }
      if (filters.onlyAvailable && f.availableSeats <= 0) {
        return false;
      }
      if ((filters.airline ?? '').isNotEmpty &&
          f.airline.iata != filters.airline) {
        return false;
      }
      return true;
    }).toList();
    return FlightPage(
      flights: filtered,
      page: page,
      perPage: perPage,
      total: filtered.length,
      totalPages: 1,
    );
  }

  @override
  Future<Flight> findById(int id) async =>
      flights.firstWhere((f) => f.id == id);

  @override
  Future<List<Airline>> airlines() async => airlinesData;

  @override
  Future<List<Airport>> airports({String? query}) async => airportsData;
}

class FakeBookingRepository implements BookingRepository {
  FakeBookingRepository({this.bookings = const [], this.errorOnCreate});

  final List<Booking> bookings;
  final ApiException? errorOnCreate;
  int? lastFlightId;
  int? lastNumTickets;
  int createCalls = 0;

  @override
  Future<List<Booking>> myBookings({String? airlineIata, double? maxPrice}) async {
    return bookings;
  }

  @override
  Future<int> create({required int flightId, required int numTickets}) async {
    createCalls++;
    lastFlightId = flightId;
    lastNumTickets = numTickets;
    if (errorOnCreate != null) throw errorOnCreate!;
    return 4242;
  }
}

class FakeAuthRepository implements AuthRepository {
  FakeAuthRepository({this.shouldFail = false});

  bool shouldFail;
  String? lastEmail;
  String? lastPassword;
  int logoutCalls = 0;

  @override
  Future<AuthResult> login(String email, String password) async {
    lastEmail = email;
    lastPassword = password;
    if (shouldFail) {
      throw ApiException('Invalid email or password.', statusCode: 401);
    }
    return AuthResult(
      token: 'fake-token',
      user: AppUser(id: 1, firstName: 'Felix', lastName: 'Huber', email: email),
    );
  }

  @override
  Future<AuthResult> register({
    required String firstName,
    required String lastName,
    required String email,
    required String password,
    required String confirmPassword,
  }) async {
    return AuthResult(
      token: 'fake-token',
      user: AppUser(id: 2, firstName: firstName, lastName: lastName, email: email),
    );
  }

  @override
  Future<AppUser> me() async =>
      AppUser(id: 1, firstName: 'Felix', lastName: 'Huber', email: 'felix.huber@example.ch');

  @override
  Future<void> logout() async {
    logoutCalls++;
  }
}
