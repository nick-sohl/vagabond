
<?php
/** @var array<int, array<string, mixed>> $flights */
?>

<div>
    <h1>Flights</h1>
    <?php foreach ($flights as $flight) { ?>
        <ul>
          <li><?php echo $flight['flight_number']; ?></li>
        </ul>

    <?php } ?>
</div>
