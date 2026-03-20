<?php
/** @var string $page */
/** @var string $view */
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vagabond</title>
    <?php // Request HTMX javascript ?>
    <script src="https://unpkg.com/htmx.org@2.0.8"></script>
    <?php // Request stylesheet generated from tailwind stylings ?>
    <link rel="stylesheet" href="/css/output.css">
  </head>
  <?php // INFO: https://htmx.org/attributes/hx-boost/ ?>
  <body hx-boost="true">
    <header>
      <?php include __DIR__ . '/../components/navbar.php'; ?>
    </header>
    <main id="main" class="py-6 border-2 border-red-600">
      <?php include __DIR__ . '/../pages/' . $page . '/' . $view . '.php'; ?>
    </main>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <!-- <script src="index.js"></script> -->
  </body>
</html>
