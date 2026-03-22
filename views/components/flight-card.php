<?php
/**
 * Flight card component — expects $flight (associative array from JOIN query)
 *
 * @var array<string, mixed> $flight
 */
?>

<div class="card-hover bg-white border border-black p-5">
    <!-- Header: Airline + Flight Number -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($flight['airline_name']) ?></span>
            <span class="text-xs text-gray-500"><?= htmlspecialchars($flight['flight_number']) ?></span>
        </div>
        <span class="text-xs px-2 py-1 <?= $flight['available_seats'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= $flight['available_seats'] > 0 ? $flight['available_seats'] . ' seats' : 'Sold out' ?>
        </span>
    </div>

    <!-- Route: Departure → Arrival -->
    <div class="flex items-center justify-between mb-4">
        <!-- Departure -->
        <div class="text-left">
            <p class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($flight['dep_iata']) ?></p>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($flight['dep_city']) ?></p>
            <p class="text-xs text-gray-400"><?= date('H:i', strtotime($flight['departure_time'])) ?></p>
        </div>

        <!-- Arrow + Duration -->
        <div class="flex flex-col items-center px-4">
            <p class="text-xs text-gray-400 mb-1">
                <?php
                $dep = new DateTime($flight['departure_time']);
                $arr = new DateTime($flight['arrival_time']);
                $diff = $dep->diff($arr);
                echo $diff->h . 'h ' . $diff->i . 'm';
                ?>
            </p>
            <div class="w-24 h-px bg-gray-300 relative">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-0 h-0 border-t-4 border-t-transparent border-b-4 border-b-transparent border-l-6 border-l-gray-300"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($flight['airplane_model']) ?></p>
        </div>

        <!-- Arrival -->
        <div class="text-right">
            <p class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($flight['arr_iata']) ?></p>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($flight['arr_city']) ?></p>
            <p class="text-xs text-gray-400"><?= date('H:i', strtotime($flight['arrival_time'])) ?></p>
        </div>
    </div>

    <!-- Footer: Date + Price + Book -->
    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
        <p class="text-sm text-gray-500"><?= date('D, d M Y', strtotime($flight['departure_time'])) ?></p>
        <div class="flex items-center gap-3">
            <p class="text-xl font-bold text-gray-900">CHF <?= number_format($flight['price'], 2) ?></p>
            <?php if ($flight['available_seats'] > 0) : ?>
                <a href="/flights/book?id=<?= $flight['id'] ?>" hx-boost="false" class="px-6 py-2.5 font-semibold text-sm uppercase tracking-wide transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 bg-brand text-white hover:bg-brand-dark focus-visible:outline-brand">Book</a>
            <?php else : ?>
                <?php $label = 'Sold out'; $variant = 'disabled'; include __DIR__ . '/../elements/button.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
