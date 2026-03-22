<?php
/** @var array<int, array<string, mixed>> $bookings */
?>

<div class="max-w-6xl mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">My Bookings</h1>

    <div class="flex flex-col gap-4">
        <?php foreach ($bookings as $booking) : ?>
            <?php include __DIR__ . '/../../components/booking-card.php'; ?>
        <?php endforeach; ?>
    </div>

    <?php if (empty($bookings)) : ?>
        <p class="text-gray-500 text-center py-12">You have no bookings yet.</p>
    <?php endif; ?>
</div>
