import 'package:intl/intl.dart';

// formatting helpers (de_CH dates/times/prices)
class Fmt {
  Fmt._();

  static final DateFormat _date = DateFormat('dd.MM.yyyy', 'de_CH');
  static final DateFormat _time = DateFormat('HH:mm', 'de_CH');
  static final DateFormat _dateTime = DateFormat('dd.MM.yyyy HH:mm', 'de_CH');

  static String date(DateTime dt) => _date.format(dt);
  static String time(DateTime dt) => _time.format(dt);
  static String dateTime(DateTime dt) => _dateTime.format(dt);

  static String price(num value) =>
      NumberFormat.currency(locale: 'de_CH', symbol: 'CHF ', decimalDigits: 2)
          .format(value);

  // e.g. "1h 35m"
  static String duration(Duration d) {
    final h = d.inHours;
    final m = d.inMinutes.remainder(60);
    return h > 0 ? '${h}h ${m}m' : '${m}m';
  }

  // backend sends "2026-06-15 07:30:00".
  // DateTime.parse only understands the "T" version, so we just swap it
  static DateTime parseBackendDateTime(String raw) {
    return DateTime.parse(raw.replaceFirst(' ', 'T'));
  }
}
