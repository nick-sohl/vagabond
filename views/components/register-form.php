<?php
/**
 * Register form component
 *
 * @var string|null $error
 */
$error = $error ?? null;
?>

<div class="w-full max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Register</h1>

    <?php if ($error) : ?>
        <?php $type = 'error'; $message = $error; include __DIR__ . '/../elements/alert.php'; ?>
    <?php endif; ?>

    <form hx-post="/auth/register" hx-target="#main" class="flex flex-col gap-4 mt-4">
        <div class="flex gap-4">
            <div class="flex-1">
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input
                    id="first_name"
                    name="first_name"
                    type="text"
                    required
                    placeholder="Felix"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
                />
            </div>
            <div class="flex-1">
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input
                    id="last_name"
                    name="last_name"
                    type="text"
                    required
                    placeholder="Huber"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
                />
            </div>
        </div>

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
                placeholder="Min. 6 characters"
                class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
            />
        </div>

        <div>
            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input
                id="confirm_password"
                name="confirm_password"
                type="password"
                required
                placeholder="Repeat password"
                class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
            />
        </div>

        <?php $label = 'Register'; $variant = 'primary'; $type = 'submit'; $class = 'w-full'; include __DIR__ . '/../elements/button.php'; ?>
    </form>

    <p class="text-sm text-gray-500 mt-4 text-center">
        Already have an account?
        <a href="/auth/login" hx-get="/auth/login" hx-target="#main" class="text-brand font-semibold hover:underline">Login</a>
    </p>
</div>
