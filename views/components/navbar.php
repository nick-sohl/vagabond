
<?php

$navLinks = [
    '/flights' => 'Flights',
    '/bookings' => 'Bookings',
    '/account' => 'Account',
];

?>

<nav class="w-full p-4 flex justify-between border-b border-black">
  <div hx-get="/" hx-target="#main" class="cursor-pointer">Logo</div>
  <ul class="flex gap-4">
    <?php foreach ($navLinks as $href => $label) { ?>
        <?php // INFO: hx-target is inherited and can be placed on a parent element?>
        <li>
          <button hx-get="<?php echo $href; ?>" hx-target="#main" class="hover:border-b-2 border-black"><?php echo $label; ?></button>
        </li>
    <?php } ?>
  </ul>
</nav>
