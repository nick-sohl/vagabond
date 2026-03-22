<?php
/** @var string $page */
/** @var string $view */
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagabond</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg">
    <script src="https://unpkg.com/htmx.org@2.0.8"></script>
    <link rel="stylesheet" href="/css/output.css">
  </head>
  <!-- INFO: https://htmx.org/attributes/hx-boost/ -->
  <body hx-boost="true" class="flex flex-col min-h-screen bg-gray-50">
    <header>
      <?php include __DIR__ . '/../components/navbar.php'; ?>
    </header>
    <main id="main" class="flex-1 py-8">
      <?php include __DIR__ . '/../pages/' . $page . '/' . $view . '.php'; ?>
    </main>
    <?php include __DIR__ . '/../components/footer.php'; ?>
  </body>
</html>
