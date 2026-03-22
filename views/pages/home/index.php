<?php
/** @var array<int, array<string, mixed>> $airlines */

$destinations = [
    ['country' => 'Spanien', 'tagline' => 'Sun-kissed beaches and vibrant cities'],
    ['country' => 'Italien', 'tagline' => 'Art, history, and world-class cuisine'],
    ['country' => 'Frankreich', 'tagline' => 'Romance, culture, and fine dining'],
    ['country' => 'Deutschland', 'tagline' => 'Engineering marvels and alpine beauty'],
    ['country' => 'Vereinigtes Königreich', 'tagline' => 'Royal heritage and modern energy'],
    ['country' => 'Türkei', 'tagline' => 'Where East meets West'],
    ['country' => 'USA', 'tagline' => 'Endless horizons from coast to coast'],
    ['country' => 'Japan', 'tagline' => 'Ancient traditions meet neon futures'],
    ['country' => 'Singapur', 'tagline' => 'A garden city of innovation'],
    ['country' => 'Vereinigte Arabische Emirate', 'tagline' => 'Luxury in the desert'],
    ['country' => 'Australien', 'tagline' => 'Wild landscapes and laid-back vibes'],
    ['country' => 'Brasilien', 'tagline' => 'Rhythm, rainforests, and carnival'],
];
?>

<div class="max-w-6xl mx-auto px-4">
    <?php if (isset($_SESSION['user_name'])) : ?>
        <h1 class="text-3xl font-bold">Hey <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
        <p class="text-lg text-gray-500 mb-6">Where are you headed next?</p>
    <?php endif; ?>

    <!-- Filter bar -->
    <?php include __DIR__ . '/../../components/flight-filter-form.php'; ?>

    <!-- Popular Destinations -->
    <h2 class="text-2xl font-bold mb-6 mt-8">Popular Destinations</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($destinations as $dest) : ?>
            <?php $country = $dest['country']; $tagline = $dest['tagline']; ?>
            <?php include __DIR__ . '/../../components/destination-card.php'; ?>
        <?php endforeach; ?>
    </div>
</div>
