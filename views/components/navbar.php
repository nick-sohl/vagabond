<?php
/**
 * Navbar component
 *
 * Two states based on authentication:
 * - Not authenticated: Flights + Login
 * - Authenticated: Flights + Bookings + Account + Logout
 *
 * Mobile: hamburger toggle, links stack vertically
 */

$isAuthenticated = isset($_SESSION['user_id']);
$linkClass = 'flex items-center px-6 py-3 border-b lg:border-b-0 lg:border-l border-black text-sm font-bold uppercase tracking-wide hover:bg-gray-100 transition-colors';
?>

<nav class="w-full bg-white border-b border-black" aria-label="Main navigation">
    <div class="flex items-stretch justify-between">
        <!-- Logo -->
        <a
            href="/"
            hx-get="/"
            hx-target="#main"
            class="flex items-center gap-2 px-4 py-3 hover:opacity-80 transition-opacity"
            aria-label="Vagabond — Home"
        >
            <img src="/images/logo.svg" alt="" class="h-6" aria-hidden="true" />
        </a>

        <!-- Hamburger (mobile only) -->
        <button
            type="button"
            class="flex items-center px-4 py-3 border-l border-black lg:hidden"
            aria-label="Toggle menu"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Nav Links — inline on desktop -->
        <div id="nav-links" class="hidden lg:flex items-stretch">
            <?php if ($isAuthenticated) : ?>
                <a href="/flights" hx-get="/flights" hx-target="#main" class="<?= $linkClass ?>">Flights</a>
                <a href="/bookings" hx-get="/bookings" hx-target="#main" class="<?= $linkClass ?>">Bookings</a>
                <a href="/account" hx-get="/account" hx-target="#main" class="<?= $linkClass ?>">Account</a>
                <a href="/auth/logout" hx-boost="false" class="<?= $linkClass ?>">Logout</a>
            <?php else : ?>
                <a href="/flights" hx-get="/flights" hx-target="#main" class="<?= $linkClass ?>">Flights</a>
                <a href="/auth/login" hx-get="/auth/login" hx-target="#main" class="<?= $linkClass ?> bg-brand text-white hover:bg-brand-dark hover:!bg-brand-dark">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mobile dropdown (same links, stacked) -->
    <div id="nav-links-mobile" class="hidden lg:hidden flex-col border-t border-black">
        <?php if ($isAuthenticated) : ?>
            <a href="/flights" hx-get="/flights" hx-target="#main" class="<?= $linkClass ?>">Flights</a>
            <a href="/bookings" hx-get="/bookings" hx-target="#main" class="<?= $linkClass ?>">Bookings</a>
            <a href="/account" hx-get="/account" hx-target="#main" class="<?= $linkClass ?>">Account</a>
            <a href="/auth/logout" hx-boost="false" class="<?= $linkClass ?>">Logout</a>
        <?php else : ?>
            <a href="/flights" hx-get="/flights" hx-target="#main" class="<?= $linkClass ?>">Flights</a>
            <a href="/auth/login" hx-get="/auth/login" hx-target="#main" class="<?= $linkClass ?> bg-brand text-white hover:bg-brand-dark">Login</a>
        <?php endif; ?>
    </div>
</nav>

<script>
(function() {
    var menu = document.getElementById('nav-links-mobile');
    document.querySelector('nav button[aria-label="Toggle menu"]').addEventListener('click', function() {
        menu.classList.toggle('hidden');
        menu.classList.toggle('flex');
    });
    menu.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            menu.classList.add('hidden');
            menu.classList.remove('flex');
        });
    });
})();
</script>
