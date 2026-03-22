<?php
/** @var array<int, array<string, string>> $airports */
/** @var string $targetInput */
/** @var string $resultId */
?>

<?php if (empty($airports)) : ?>
    <p class="p-2 text-gray-500">No airports found.</p>
<?php else : ?>
    <?php foreach ($airports as $airport) : ?>
        <?php $label = htmlspecialchars($airport['iata_code'] . ' — ' . $airport['city']); ?>
        <div
            class="p-2 cursor-pointer hover:bg-gray-100"
            onclick="document.getElementById('<?= $targetInput ?>').value = '<?= $label ?>'; document.getElementById('<?= $resultId ?>').innerHTML = '';"
        >
            <?= $label ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
