/// Airline serving a flight, e.g. "LX – Swiss International Air Lines".
class Airline {
  Airline({
    required this.iata,
    required this.name,
    this.id,
  });

  final int? id;
  final String iata;
  final String name;

  factory Airline.fromJson(Map<String, dynamic> json) => Airline(
        id: (json['id'] as num?)?.toInt(),
        iata: json['iata_code'] as String? ?? json['iata'] as String? ?? '',
        name: json['name'] as String? ?? '',
      );

  String get label => '$iata – $name';

  @override
  bool operator ==(Object other) =>
      other is Airline && other.iata == iata && other.name == name;

  @override
  int get hashCode => Object.hash(iata, name);
}
