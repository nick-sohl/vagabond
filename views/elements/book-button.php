<?php /** Book button element — expects $flightId */ ?>

<button hx-post="/bookings" hx-vals='{"flightId": "<?= $flightId ?>"}'>
    Book Flight
</button>
