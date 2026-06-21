import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../../core/utils/format.dart';
import '../../../data/models/airline.dart';
import '../../../data/models/airport.dart';
import '../../../data/repositories/flight_repository.dart';
import '../../state/flight_search_state.dart';
import '../../widgets/flight_card.dart';
import 'flight_detail_screen.dart';

// Epic 1: the search mask + results list.
// all the data lives in FlightSearchState so the widgets stay "dumb"
// (and are easier to test with a fake repo)
class FlightSearchScreen extends StatelessWidget {
  const FlightSearchScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (ctx) {
        final state = FlightSearchState(ctx.read<FlightRepository>());
        state.loadReferenceData();
        return state;
      },
      child: const _FlightSearchView(),
    );
  }
}

class _FlightSearchView extends StatelessWidget {
  const _FlightSearchView();

  @override
  Widget build(BuildContext context) {
    final state = context.watch<FlightSearchState>();
    return RefreshIndicator(
      onRefresh: () => state.search(),
      child: CustomScrollView(
        slivers: [
          SliverToBoxAdapter(child: _SearchForm(state: state)),
          if (state.status == SearchStatus.error)
            SliverToBoxAdapter(child: _ErrorBanner(message: state.error ?? 'Fehler')),
          if (state.status == SearchStatus.idle)
            const SliverFillRemaining(
              hasScrollBody: false,
              child: _EmptyHint(),
            )
          else if (state.flights.isEmpty && state.status == SearchStatus.loaded)
            const SliverFillRemaining(
              hasScrollBody: false,
              child: _NoResults(),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              sliver: SliverList.separated(
                itemCount: state.flights.length + (state.hasMore ? 1 : 0),
                separatorBuilder: (context, index) => const SizedBox(height: 6),
                itemBuilder: (context, index) {
                  if (index >= state.flights.length) {
                    return _PaginationTile(state: state);
                  }
                  final flight = state.flights[index];
                  return FlightCard(
                    flight: flight,
                    onTap: () => Navigator.of(context).push(MaterialPageRoute(
                      builder: (_) => FlightDetailScreen(flight: flight),
                    )),
                  );
                },
              ),
            ),
          if (state.status == SearchStatus.loaded && state.flights.isNotEmpty)
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Text(
                  '${state.flights.length} von ${state.total} Flügen angezeigt',
                  textAlign: TextAlign.center,
                  style: Theme.of(context).textTheme.labelSmall,
                ),
              ),
            ),
        ],
      ),
    );
  }
}

class _SearchForm extends StatefulWidget {
  const _SearchForm({required this.state});

  final FlightSearchState state;

  @override
  State<_SearchForm> createState() => _SearchFormState();
}

class _SearchFormState extends State<_SearchForm> {
  final _formKey = GlobalKey<FormState>();
  final _maxPrice = TextEditingController();
  final _departTime = TextEditingController();

  @override
  void initState() {
    super.initState();
    _maxPrice.text = widget.state.filters.maxPrice?.toStringAsFixed(0) ?? '';
    _departTime.text = widget.state.filters.departTime ?? '';
  }

  @override
  void dispose() {
    _maxPrice.dispose();
    _departTime.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final state = widget.state;
    final filters = state.filters;
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Form(
        key: _formKey,
        child: Card(
          child: Padding(
            padding: const EdgeInsets.all(14),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                _AirportField(
                  key: const Key('search.departure'),
                  label: 'Abreiseort',
                  icon: Icons.flight_takeoff,
                  initial: filters.departure,
                  airports: state.airports,
                  onChanged: (value) =>
                      state.updateFilters(filters.copyWith(departure: value)),
                ),
                const SizedBox(height: 10),
                _AirportField(
                  key: const Key('search.arrival'),
                  label: 'Zielort',
                  icon: Icons.flight_land,
                  initial: filters.arrival,
                  airports: state.airports,
                  onChanged: (value) =>
                      state.updateFilters(filters.copyWith(arrival: value)),
                ),
                const SizedBox(height: 10),
                Row(children: [
                  Expanded(
                    child: _DateField(
                      value: filters.departDate,
                      onPicked: (date) {
                        state.updateFilters(filters.copyWith(
                          departDate: date,
                          clearDepartDate: date == null,
                        ));
                      },
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: TextFormField(
                      key: const Key('search.depart_time'),
                      controller: _departTime,
                      keyboardType: TextInputType.datetime,
                      decoration: const InputDecoration(
                        labelText: 'Ab Zeit',
                        hintText: 'HH:MM',
                        prefixIcon: Icon(Icons.schedule),
                      ),
                      onChanged: (value) => state
                          .updateFilters(filters.copyWith(departTime: value)),
                    ),
                  ),
                ]),
                const SizedBox(height: 10),
                _AirlineDropdown(
                  selected: filters.airline,
                  airlines: state.airlines,
                  onChanged: (value) =>
                      state.updateFilters(filters.copyWith(airline: value ?? '')),
                ),
                const SizedBox(height: 10),
                TextFormField(
                  key: const Key('search.max_price'),
                  controller: _maxPrice,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(
                    labelText: 'Max. Preis (CHF)',
                    prefixIcon: Icon(Icons.payments_outlined),
                  ),
                  onChanged: (value) {
                    final parsed = double.tryParse(value);
                    state.updateFilters(filters.copyWith(
                      maxPrice: parsed,
                      clearMaxPrice: value.isEmpty,
                    ));
                  },
                ),
                const SizedBox(height: 8),
                SwitchListTile.adaptive(
                  key: const Key('search.only_available'),
                  contentPadding: EdgeInsets.zero,
                  title: const Text('Nur verfügbare Tickets'),
                  value: filters.onlyAvailable,
                  onChanged: (value) =>
                      state.updateFilters(filters.copyWith(onlyAvailable: value)),
                ),
                const SizedBox(height: 8),
                Row(children: [
                  Expanded(
                    child: FilledButton.icon(
                      key: const Key('search.submit'),
                      onPressed: state.status == SearchStatus.loading
                          ? null
                          : () => state.search(),
                      icon: state.status == SearchStatus.loading
                          ? const SizedBox(
                              height: 16,
                              width: 16,
                              child: CircularProgressIndicator(
                                strokeWidth: 2, color: Colors.white,
                              ),
                            )
                          : const Icon(Icons.search),
                      label: const Text('Suchen'),
                    ),
                  ),
                  const SizedBox(width: 10),
                  TextButton(
                    onPressed: state.status == SearchStatus.loading
                        ? null
                        : () {
                            _maxPrice.clear();
                            _departTime.clear();
                            state.reset();
                          },
                    child: const Text('Zurücksetzen'),
                  ),
                ]),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

class _AirportField extends StatelessWidget {
  const _AirportField({
    super.key,
    required this.label,
    required this.icon,
    required this.initial,
    required this.airports,
    required this.onChanged,
  });

  final String label;
  final IconData icon;
  final String? initial;
  final List<Airport> airports;
  final ValueChanged<String> onChanged;

  @override
  Widget build(BuildContext context) {
    return Autocomplete<Airport>(
      displayStringForOption: (a) => a.iata,
      optionsBuilder: (TextEditingValue value) {
        final q = value.text.trim().toUpperCase();
        if (q.isEmpty) return const Iterable<Airport>.empty();
        return airports.where((a) =>
            a.iata.startsWith(q) ||
            a.city.toUpperCase().contains(q));
      },
      onSelected: (a) => onChanged(a.iata),
      fieldViewBuilder: (context, controller, focusNode, _) {
        if (initial != null && initial!.isNotEmpty && controller.text.isEmpty) {
          controller.text = initial!;
        }
        return TextField(
          controller: controller,
          focusNode: focusNode,
          textCapitalization: TextCapitalization.characters,
          decoration: InputDecoration(
            labelText: label,
            prefixIcon: Icon(icon),
            hintText: 'IATA (z.B. ZRH)',
          ),
          onChanged: (value) => onChanged(value.trim().toUpperCase()),
        );
      },
      optionsViewBuilder: (context, onSelected, options) {
        return Align(
          alignment: Alignment.topLeft,
          child: Material(
            elevation: 4,
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxHeight: 240, maxWidth: 360),
              child: ListView(
                shrinkWrap: true,
                children: options
                    .take(20)
                    .map((a) => ListTile(
                          title: Text('${a.iata} — ${a.city}'),
                          subtitle: Text(a.name ?? a.country ?? ''),
                          onTap: () => onSelected(a),
                        ))
                    .toList(),
              ),
            ),
          ),
        );
      },
    );
  }
}

class _AirlineDropdown extends StatelessWidget {
  const _AirlineDropdown({
    required this.selected,
    required this.airlines,
    required this.onChanged,
  });

  final String? selected;
  final List<Airline> airlines;
  final ValueChanged<String?> onChanged;

  @override
  Widget build(BuildContext context) {
    return DropdownButtonFormField<String?>(
      key: const Key('search.airline'),
      initialValue: (selected ?? '').isEmpty ? null : selected,
      decoration: const InputDecoration(
        labelText: 'Fluggesellschaft',
        prefixIcon: Icon(Icons.airplanemode_active),
      ),
      items: [
        const DropdownMenuItem<String?>(value: null, child: Text('Alle')),
        ...airlines.map((a) =>
            DropdownMenuItem<String?>(value: a.iata, child: Text(a.label))),
      ],
      onChanged: onChanged,
    );
  }
}

class _DateField extends StatelessWidget {
  const _DateField({required this.value, required this.onPicked});

  final DateTime? value;
  final ValueChanged<DateTime?> onPicked;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: () async {
        final now = DateTime.now();
        final picked = await showDatePicker(
          context: context,
          initialDate: value ?? now,
          firstDate: now.subtract(const Duration(days: 1)),
          lastDate: now.add(const Duration(days: 365)),
        );
        if (picked != null) onPicked(picked);
      },
      child: InputDecorator(
        decoration: const InputDecoration(
          labelText: 'Datum',
          prefixIcon: Icon(Icons.event),
        ),
        child: Row(children: [
          Expanded(child: Text(value == null ? 'Beliebig' : Fmt.date(value!))),
          if (value != null)
            IconButton(
              tooltip: 'Datum löschen',
              icon: const Icon(Icons.close, size: 18),
              onPressed: () => onPicked(null),
            ),
        ]),
      ),
    );
  }
}

class _PaginationTile extends StatelessWidget {
  const _PaginationTile({required this.state});

  final FlightSearchState state;

  @override
  Widget build(BuildContext context) {
    final loading = state.status == SearchStatus.loading;
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Center(
        child: loading
            ? const CircularProgressIndicator()
            : TextButton.icon(
                key: const Key('search.load_more'),
                onPressed: state.loadNextPage,
                icon: const Icon(Icons.expand_more),
                label: const Text('Mehr laden'),
              ),
      ),
    );
  }
}

class _ErrorBanner extends StatelessWidget {
  const _ErrorBanner({required this.message});

  final String message;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Material(
        color: theme.colorScheme.errorContainer,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(children: [
            Icon(Icons.error_outline, color: theme.colorScheme.error),
            const SizedBox(width: 8),
            Expanded(
              child: Text(message,
                  style: TextStyle(color: theme.colorScheme.onErrorContainer)),
            ),
          ]),
        ),
      ),
    );
  }
}

class _EmptyHint extends StatelessWidget {
  const _EmptyHint();

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.travel_explore, size: 48, color: Theme.of(context).colorScheme.primary),
          const SizedBox(height: 12),
          Text(
            'Bereit für die nächste Reise?',
            style: Theme.of(context).textTheme.titleMedium,
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 6),
          const Text(
            'Trage Filter ein oder tippe direkt auf "Suchen" um alle verfügbaren Flüge zu sehen.',
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}

class _NoResults extends StatelessWidget {
  const _NoResults();

  @override
  Widget build(BuildContext context) {
    return const Padding(
      padding: EdgeInsets.all(24),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.sentiment_dissatisfied, size: 48),
          SizedBox(height: 12),
          Text('Keine Flüge gefunden.', textAlign: TextAlign.center),
          SizedBox(height: 6),
          Text('Passe die Filter an und versuche es erneut.', textAlign: TextAlign.center),
        ],
      ),
    );
  }
}
