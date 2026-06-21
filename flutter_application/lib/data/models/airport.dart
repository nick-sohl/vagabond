/// Airport endpoint (e.g. ZRH – Zürich).
class Airport {
  Airport({
    required this.iata,
    required this.city,
    this.id,
    this.name,
    this.country,
  });

  final int? id;
  final String iata;
  final String city;
  final String? name;
  final String? country;

  factory Airport.fromJson(Map<String, dynamic> json) => Airport(
        id: (json['id'] as num?)?.toInt(),
        iata: json['iata_code'] as String? ?? json['iata'] as String? ?? '',
        city: json['city'] as String? ?? '',
        name: json['name'] as String?,
        country: json['country'] as String?,
      );

  String get label => '$iata — $city';
}
