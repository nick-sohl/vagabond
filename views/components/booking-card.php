<?php
/**
 * Booking card component — expects $booking (associative array from JOIN query)
 *
 * @var array<string, mixed> $booking
 */

$statusClasses = match ($booking['status']) {
    'confirmed' => 'bg-green-100 text-green-700',
    'pending'   => 'bg-yellow-100 text-yellow-700',
    'cancelled' => 'bg-red-100 text-red-700',
    default     => 'bg-gray-100 text-gray-700',
};
?>

<div class="card-hover bg-white border border-black p-5">
    <!-- Header: Airline + Flight Number + Status -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($booking['airline_name']) ?></span>
            <span class="text-xs text-gray-500"><?= htmlspecialchars($booking['flight_number']) ?></span>
        </div>
        <span class="text-xs px-2 py-1 <?= $statusClasses ?> capitalize">
            <?= htmlspecialchars($booking['status']) ?>
        </span>
    </div>

    <!-- Route: Departure → Arrival -->
    <div class="flex items-center justify-between mb-4">
        <div class="text-left">
            <p class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($booking['dep_iata']) ?></p>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($booking['dep_city']) ?></p>
            <p class="text-xs text-gray-400"><?= date('H:i', strtotime($booking['departure_time'])) ?></p>
        </div>

        <div class="flex flex-col items-center px-4">
            <p class="text-xs text-gray-400 mb-1">
                <?php
                $dep = new DateTime($booking['departure_time']);
                $arr = new DateTime($booking['arrival_time']);
                $diff = $dep->diff($arr);
                echo $diff->h . 'h ' . $diff->i . 'm';
                ?>
            </p>
            <div class="w-24 h-px bg-gray-300 relative">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-0 h-0 border-t-4 border-t-transparent border-b-4 border-b-transparent border-l-6 border-l-gray-300"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($booking['airplane_model']) ?></p>
        </div>

        <div class="text-right">
            <p class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($booking['arr_iata']) ?></p>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($booking['arr_city']) ?></p>
            <p class="text-xs text-gray-400"><?= date('H:i', strtotime($booking['arrival_time'])) ?></p>
        </div>
    </div>

    <!-- Footer: Date + Tickets + Price -->
    <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-sm">
        <p class="text-gray-500"><?= date('D, d M Y', strtotime($booking['departure_time'])) ?></p>
        <div class="flex items-center gap-4">
            <span class="text-gray-500"><?= $booking['num_tickets'] ?> ticket<?= $booking['num_tickets'] > 1 ? 's' : '' ?></span>
            <span class="text-lg font-bold text-gray-900">CHF <?= number_format($booking['total_price'], 2) ?></span>
        </div>
    </div>
</div>
