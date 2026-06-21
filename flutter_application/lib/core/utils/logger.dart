import 'dart:developer' as developer;

// small wrapper around dart:developer so I have one place to log from.
// IMPORTANT: never log the bearer token or passwords here!
class Log {
  Log._();

  static void info(String tag, String message) {
    developer.log(message, name: tag, level: 800);
  }

  static void warn(String tag, String message) {
    developer.log(message, name: tag, level: 900);
  }

  static void error(String tag, String message, [Object? error, StackTrace? st]) {
    developer.log(message, name: tag, level: 1000, error: error, stackTrace: st);
  }
}
