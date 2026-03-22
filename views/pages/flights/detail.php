<?php
/**
 * @var array<string, mixed> $flight
 * @var string|null $error
 */
$error = $error ?? null;
?>

<div class="max-w-6xl mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left: Flight Details -->
        <div class="bg-white border border-black p-6">
            <h2 class="text-lg font-bold mb-4">Flight Details</h2>

            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="font-semibold"><?= htmlspecialchars($flight['airline_name']) ?></span>
                    <span class="text-sm text-gray-500"><?= htmlspecialchars($flight['flight_number']) ?></span>
                </div>

                <!-- Route -->
                <div class="flex items-center justify-between">
                    <div class="text-left">
                        <p class="text-2xl font-bold"><?= htmlspecialchars($flight['dep_iata']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($flight['dep_city']) ?></p>
                        <p class="text-xs text-gray-400"><?= date('H:i', strtotime($flight['departure_time'])) ?></p>
                    </div>

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
                    </div>

                    <div class="text-right">
                        <p class="text-2xl font-bold"><?= htmlspecialchars($flight['arr_iata']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($flight['arr_city']) ?></p>
                        <p class="text-xs text-gray-400"><?= date('H:i', strtotime($flight['arrival_time'])) ?></p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date</span>
                        <span class="font-medium"><?= date('D, d M Y', strtotime($flight['departure_time'])) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Aircraft</span>
                        <span class="font-medium"><?= htmlspecialchars($flight['airplane_manufacturer'] . ' ' . $flight['airplane_model']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Available Seats</span>
                        <span class="font-medium"><?= $flight['available_seats'] ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Price per Ticket</span>
                        <span class="font-bold text-lg">CHF <?= number_format($flight['price'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Checkout Form -->
        <div>
            <?php include __DIR__ . '/../../components/checkout-form.php'; ?>
        </div>
    </div>
</div>
