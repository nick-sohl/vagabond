<?php
/** @var array<int, array<string, mixed>> $flights */
/** @var array<int, array<string, mixed>> $airlines */
/** @var int $currentPage */
/** @var int $totalPages */
/** @var int $totalFlights */

// Rebuild current query string without 'page' for pagination links
$queryParams = $_GET;
unset($queryParams['page']);
$baseQuery = http_build_query($queryParams);
$pageUrl = '/flights' . ($baseQuery ? '?' . $baseQuery . '&' : '?');
?>

<div class="max-w-6xl mx-auto px-4">
    <!-- Filter bar -->
    <?php include __DIR__ . '/../../components/flight-filter-form.php'; ?>

    <h1 class="text-2xl font-bold mb-6 mt-8"><?= $totalFlights ?> Flight<?= $totalFlights !== 1 ? 's' : '' ?> available</h1>

    <div class="flex flex-col gap-4">
        <?php foreach ($flights as $flight) : ?>
            <?php include __DIR__ . '/../../components/flight-card.php'; ?>
        <?php endforeach; ?>
    </div>

    <?php if (empty($flights)) : ?>
        <div class="text-center py-12">
            <p class="text-gray-500 mb-4">No flights found matching your filters.</p>
            <a
                href="/flights"
                hx-get="/flights"
                hx-target="#main"
                hx-push-url="true"
                class="inline-block px-6 py-2.5 font-semibold text-sm uppercase tracking-wide transition-colors border border-black bg-white text-black hover:bg-gray-100"
            >
                Reset Filters
            </a>
        </div>
    <?php endif; ?>

    <?php if ($totalPages > 1) : ?>
        <nav class="flex items-center justify-center gap-2 mt-8" aria-label="Pagination">
            <?php if ($currentPage > 1) : ?>
                <a
                    href="<?= $pageUrl ?>page=<?= $currentPage - 1 ?>"
                    hx-get="<?= $pageUrl ?>page=<?= $currentPage - 1 ?>"
                    hx-target="#main"
                    hx-push-url="true"
                    class="px-4 py-2 border border-black text-sm font-semibold hover:bg-gray-100 transition-colors"
                >&larr; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a
                    href="<?= $pageUrl ?>page=<?= $i ?>"
                    hx-get="<?= $pageUrl ?>page=<?= $i ?>"
                    hx-target="#main"
                    hx-push-url="true"
                    class="px-4 py-2 border text-sm font-semibold transition-colors <?= $i === $currentPage ? 'border-black bg-black text-white' : 'border-black hover:bg-gray-100' ?>"
                ><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages) : ?>
                <a
                    href="<?= $pageUrl ?>page=<?= $currentPage + 1 ?>"
                    hx-get="<?= $pageUrl ?>page=<?= $currentPage + 1 ?>"
                    hx-target="#main"
                    hx-push-url="true"
                    class="px-4 py-2 border border-black text-sm font-semibold hover:bg-gray-100 transition-colors"
                >Next &rarr;</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>
