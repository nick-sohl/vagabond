// thrown by ApiClient when the backend gives back an error or when the
// network is down. status code is kept so the screen can react,
// e.g. 401 -> "log in again"
class ApiException implements Exception {
  ApiException(this.message, {this.statusCode});

  final String message;
  final int? statusCode;

  bool get isUnauthorized => statusCode == 401;

  @override
  String toString() => 'ApiException($statusCode): $message';
}
