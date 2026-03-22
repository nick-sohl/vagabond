<?php /** Footer component */ ?>

<footer class="bg-brand border-t border-black" role="contentinfo">
    <!-- Top section: Links + Tagline -->
    <div class="flex flex-col md:flex-row">
        <!-- Left: Links -->
        <div class="flex-1 flex flex-col">
            <a
                href="/privacy"
                class="flex items-center justify-between px-6 py-5 border-b border-black/30 text-black font-bold text-lg uppercase tracking-wide hover:bg-brand-dark/20 transition-colors"
            >
                <span>Privacy Policy</span>
                <span aria-hidden="true" class="text-xl">&#8599;</span>
            </a>
            <a
                href="/terms"
                class="flex items-center justify-between px-6 py-5 text-black font-bold text-lg uppercase tracking-wide hover:bg-brand-dark/20 transition-colors"
            >
                <span>Terms and Conditions</span>
                <span aria-hidden="true" class="text-xl">&#8599;</span>
            </a>
        </div>

        <!-- Right: Tagline -->
        <div class="flex-1 flex items-center justify-end px-6 py-5 border-l-0 md:border-l border-black/30">
            <p class="text-2xl md:text-4xl lg:text-5xl font-bold uppercase tracking-wide text-right text-white/80"
            >
                Be there.<br>
                Life live.<br>
                Enjoy all.
            </p>
        </div>
    </div>

    <!-- Bottom: Wordmark -->
    <div class="border-t border-black/30 px-6 py-4 overflow-hidden">
        <p class="text-7xl md:text-8xl lg:text-9xl font-black italic text-black leading-none tracking-tighter">
            Vagabond
        </p>
    </div>
</footer>
