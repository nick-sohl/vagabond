<?php
/**
 * Destination card component
 *
 * @var string $country
 * @var string $tagline
 */
?>

<a href="/flights?country=<?= urlencode($country) ?>" hx-get="/flights?country=<?= urlencode($country) ?>" hx-target="#main" hx-push-url="true" class="card-hover block bg-white border border-black p-5 group">
    <div class="flex items-start justify-between">
        <div>
            <h3 class="font-bold text-gray-900"><?= htmlspecialchars($country) ?></h3>
            <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($tagline) ?></p>
        </div>
        <span class="text-gray-400 group-hover:text-gray-900 transition-colors text-lg">&nearr;</span>
    </div>
</a>
