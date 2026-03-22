<?php
/**
 * Alert element
 *
 * @var string $type    — 'error' | 'success' | 'warning' | 'info'
 * @var string $message — alert text
 */

$variantClasses = match ($type) {
    'error'   => 'bg-red-100 border-red-400 text-red-700',
    'success' => 'bg-green-100 border-green-400 text-green-700',
    'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    'info'    => 'bg-blue-100 border-blue-400 text-blue-700',
    default   => 'bg-gray-100 border-gray-400 text-gray-700',
};
?>

<div class="border px-4 py-3 text-sm <?= $variantClasses ?>">
    <?= htmlspecialchars($message) ?>
</div>
