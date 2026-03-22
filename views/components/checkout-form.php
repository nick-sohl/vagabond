<?php
/**
 * Checkout form component — expects $flight, $error
 *
 * @var array<string, mixed> $flight
 * @var string|null $error
 */
$error = $error ?? null;
$userName = $_SESSION['user_name'] ?? '';
?>

<div class="bg-white border border-black p-6">
    <h2 class="text-lg font-bold mb-4">Payment Details</h2>

    <?php if ($error) : ?>
        <?php $type = 'error'; $message = $error; include __DIR__ . '/../elements/alert.php'; ?>
    <?php endif; ?>

    <form hx-post="/flights/book" hx-target="#main" class="flex flex-col gap-4 mt-4">
        <input type="hidden" name="flight_id" value="<?= $flight['id'] ?>">

        <div>
            <label for="num_tickets" class="block text-sm font-medium text-gray-700 mb-1">Number of Tickets</label>
            <input
                id="num_tickets"
                name="num_tickets"
                type="number"
                min="1"
                max="<?= $flight['available_seats'] ?>"
                value="1"
                required
                oninput="document.getElementById('total-price').textContent = 'CHF ' + (this.value * <?= $flight['price'] ?>).toFixed(2)"
                class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
            />
        </div>

        <div>
            <label for="cardholder" class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name</label>
            <input
                id="cardholder"
                name="cardholder"
                type="text"
                required
                value="<?= htmlspecialchars($userName) ?>"
                placeholder="John Doe"
                class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
            />
        </div>

        <div>
            <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
            <input
                id="card_number"
                name="card_number"
                type="text"
                required
                value="4242 4242 4242 4242"
                placeholder="1234 5678 9012 3456"
                maxlength="19"
                class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
            />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="expiry" class="block text-sm font-medium text-gray-700 mb-1">Expiry</label>
                <input
                    id="expiry"
                    name="expiry"
                    type="text"
                    required
                    value="12/28"
                    placeholder="MM/YY"
                    maxlength="5"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
                />
            </div>
            <div>
                <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                <input
                    id="cvv"
                    name="cvv"
                    type="text"
                    required
                    value="123"
                    placeholder="123"
                    maxlength="4"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
                />
            </div>
        </div>

        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
            <span class="text-sm text-gray-500">Total</span>
            <span id="total-price" class="text-xl font-bold">CHF <?= number_format($flight['price'], 2) ?></span>
        </div>

        <?php $label = 'Confirm Booking'; $variant = 'primary'; $type = 'submit'; $class = 'w-full mt-2'; include __DIR__ . '/../elements/button.php'; ?>
    </form>
</div>
