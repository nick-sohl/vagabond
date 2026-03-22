<?php
/**
 * Button element
 *
 * @var string $label       — button text
 * @var string $variant     — 'primary' | 'secondary' | 'outline' | 'ghost' | 'disabled' (default: 'primary')
 * @var string $type        — HTML button type: 'button' | 'submit' (default: 'button')
 * @var string $class       — additional CSS classes (default: '')
 * @var array  $attributes  — extra HTML attributes as key=>value (default: [])
 */

$variant    = $variant ?? 'primary';
$type       = $type ?? 'button';
$class      = $class ?? '';
$attributes = $attributes ?? [];

$variantClasses = match ($variant) {
    'primary'   => 'bg-brand text-white hover:bg-brand-dark focus-visible:outline-brand',
    'secondary' => 'bg-brand-light text-black hover:bg-brand focus-visible:outline-brand-light',
    'outline'   => 'bg-white text-black border border-brand hover:bg-brand/10 focus-visible:outline-brand',
    'ghost'     => 'bg-white text-black border border-black hover:bg-gray-100 focus-visible:outline-black',
    'disabled'  => 'bg-gray-200 text-gray-400 cursor-not-allowed',
    default     => 'bg-brand text-white hover:bg-brand-dark focus-visible:outline-brand',
};

$isDisabled = $variant === 'disabled';

$attrs = '';
foreach ($attributes as $key => $value) {
    $attrs .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
}
?>

<button
    type="<?= $type ?>"
    class="px-6 py-2.5 font-semibold text-sm uppercase tracking-wide transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 <?= $variantClasses ?> <?= $class ?>"
    <?= $isDisabled ? 'disabled aria-disabled="true"' : '' ?>
    <?= $attrs ?>
>
    <?= htmlspecialchars($label) ?>
</button>
