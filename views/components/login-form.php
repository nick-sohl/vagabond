<?php
/**
 * Login form component
 *
 * @var string|null $error
 */
$error = $error ?? null;
?>

<div class="w-full max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Login</h1>

    <?php if ($error) : ?>
        <?php $type = 'error'; $message = $error; include __DIR__ . '/../elements/alert.php'; ?>
    <?php endif; ?>

    <form hx-post="/auth/login" hx-target="#main" class="flex flex-col gap-4 mt-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                required
                placeholder="you@example.com"
                class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
            />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                placeholder="Your password"
                class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
            />
        </div>

        <?php $label = 'Login'; $variant = 'primary'; $type = 'submit'; $class = 'w-full'; include __DIR__ . '/../elements/button.php'; ?>
    </form>

    <p class="text-sm text-gray-500 mt-4 text-center">
        Don't have an account?
        <a href="/auth/register" hx-get="/auth/register" hx-target="#main" class="text-brand font-semibold hover:underline">Register</a>
    </p>
</div>
