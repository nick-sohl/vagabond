<?php /** Booking success page */ ?>

<div class="max-w-2xl mx-auto px-4 text-center py-16">
    <h1 class="text-3xl font-bold mb-4">Booking Confirmed!</h1>
    <p class="text-gray-500 text-lg mb-8">Your adventure awaits. Pack your bags!</p>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="/bookings" hx-get="/bookings" hx-target="#main" hx-push-url="true" class="px-6 py-2.5 font-semibold text-sm uppercase tracking-wide transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 bg-brand text-white hover:bg-brand-dark focus-visible:outline-brand">View My Bookings</a>
        <a href="/" hx-get="/" hx-target="#main" hx-push-url="true" class="px-6 py-2.5 font-semibold text-sm uppercase tracking-wide transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 bg-white text-black border border-black hover:bg-gray-100 focus-visible:outline-black">Back to Home</a>
    </div>
</div>
