<?php
/**
 * Flight filter form component
 *
 * Horizontal bar with From, To, Date, Time, Airline, Availability, Price + Search.
 * Airport fields use HTMX autocomplete with per-field dropdowns.
 */
?>

<div class="flight-filter-form">
    <div class="flex flex-col lg:flex-row border border-black bg-white">
        <!-- From -->
        <div class="flex-1 px-4 py-3 border-b lg:border-b-0 lg:border-r border-black relative">
            <label for="departure" class="block text-xs text-gray-500 mb-0.5">From</label>
            <input
                id="departure"
                name="departure"
                type="text"
                placeholder="ZRH — Zurich"
                autocomplete="off"
                hx-get="/api/airports"
                hx-trigger="keyup changed delay:300ms, focus"
                hx-target="#departure-results"
                hx-params="departure"
                onfocusout="setTimeout(() => document.getElementById('departure-results').innerHTML = '', 200)"
                class="w-full text-sm font-medium text-black placeholder:text-gray-400 focus:outline-none"
            />
            <div id="departure-results" class="absolute z-10 left-0 right-0 top-full mt-px bg-white border border-black shadow-lg empty:hidden max-h-60 overflow-y-auto"></div>
        </div>

        <!-- To -->
        <div class="flex-1 px-4 py-3 border-b lg:border-b-0 lg:border-r border-black relative">
            <label for="arrival" class="block text-xs text-gray-500 mb-0.5">To</label>
            <input
                id="arrival"
                name="arrival"
                type="text"
                placeholder="MAD — Madrid"
                autocomplete="off"
                hx-get="/api/airports"
                hx-trigger="keyup changed delay:300ms, focus"
                hx-target="#arrival-results"
                hx-params="arrival"
                onfocusout="setTimeout(() => document.getElementById('arrival-results').innerHTML = '', 200)"
                class="w-full text-sm font-medium text-black placeholder:text-gray-400 focus:outline-none"
            />
            <div id="arrival-results" class="absolute z-10 left-0 right-0 top-full mt-px bg-white border border-black shadow-lg empty:hidden max-h-60 overflow-y-auto"></div>
        </div>

        <!-- Depart -->
        <div class="flex-1 px-4 py-3 border-b lg:border-b-0 lg:border-r border-black relative">
            <label for="depart-datetime" class="block text-xs text-gray-500 mb-0.5">Depart</label>
            <div class="relative">
                <input
                    id="depart-datetime"
                    name="depart_datetime"
                    type="datetime-local"
                    class="filter-datetime is-empty w-full text-sm font-medium text-black focus:outline-none bg-transparent"
                    onfocus="this.parentElement.querySelector('.datetime-placeholder').style.display='none'"
                    onblur="if(!this.value){this.parentElement.querySelector('.datetime-placeholder').style.display='';this.classList.add('is-empty')}"
                    onchange="var p=this.parentElement.querySelector('.datetime-placeholder');if(this.value){p.style.display='none';this.classList.remove('is-empty')}else{p.style.display='';this.classList.add('is-empty')}"
                />
                <span class="datetime-placeholder pointer-events-none absolute left-0 top-1/2 -translate-y-1/2 text-sm text-gray-400">Any date &amp; time</span>
            </div>
        </div>

        <!-- Airline -->
        <div class="flex-1 px-4 py-3 border-b lg:border-b-0 lg:border-r border-black">
            <label for="airline" class="block text-xs text-gray-500 mb-0.5">Airline</label>
            <select
                id="airline"
                name="airline"
                class="w-full text-sm font-medium text-black bg-transparent focus:outline-none"
            >
                <option value="">Any</option>
                <?php if (!empty($airlines)) : ?>
                    <?php foreach ($airlines as $airline) : ?>
                        <option value="<?= htmlspecialchars($airline['iata_code']) ?>"><?= htmlspecialchars($airline['name']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Availability -->
        <div class="flex-1 px-4 py-3 border-b lg:border-b-0 lg:border-r border-black">
            <label for="availability" class="block text-xs text-gray-500 mb-0.5">Availability</label>
            <select
                id="availability"
                name="availability"
                class="w-full text-sm font-medium text-black bg-transparent focus:outline-none"
            >
                <option value="">Any</option>
                <option value="available">Available only</option>
            </select>
        </div>

        <!-- Price -->
        <div class="flex-1 px-4 py-3 border-b lg:border-b-0 lg:border-r border-black">
            <label for="max-price" class="block text-xs text-gray-500 mb-0.5">Max Price</label>
            <input
                id="max-price"
                name="max_price"
                type="number"
                placeholder="CHF"
                min="0"
                step="10"
                class="w-full text-sm font-medium text-black placeholder:text-gray-400 focus:outline-none"
            />
        </div>

        <!-- Search Button -->
        <button
            type="button"
            hx-get="/flights"
            hx-target="#main"
            hx-push-url="true"
            hx-include="#departure, #arrival, #depart-datetime, #airline, #availability, #max-price"
            class="px-8 py-3 bg-brand text-white text-sm font-bold uppercase tracking-wide hover:bg-brand-dark transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
            aria-label="Search flights"
        >
            Search
        </button>
    </div>
</div>
