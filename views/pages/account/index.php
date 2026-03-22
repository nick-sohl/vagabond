<?php
/**
 * @var array<string, mixed> $user
 * @var string|null $profileSuccess
 * @var string|null $profileError
 * @var string|null $passwordSuccess
 * @var string|null $passwordError
 * @var string|null $deleteError
 */
$profileSuccess = $profileSuccess ?? null;
$profileError = $profileError ?? null;
$passwordSuccess = $passwordSuccess ?? null;
$passwordError = $passwordError ?? null;
$deleteError = $deleteError ?? null;
?>

<div class="max-w-2xl mx-auto px-4">
    <h1 class="text-2xl font-bold mb-8">My Account</h1>

    <!-- Profile Section -->
    <div class="bg-white border border-black p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">Profile</h2>

        <?php if ($profileSuccess) : ?>
            <?php $type = 'success'; $message = $profileSuccess; include __DIR__ . '/../../elements/alert.php'; ?>
        <?php endif; ?>
        <?php if ($profileError) : ?>
            <?php $type = 'error'; $message = $profileError; include __DIR__ . '/../../elements/alert.php'; ?>
        <?php endif; ?>

        <form hx-post="/account/profile" hx-target="#main" class="flex flex-col gap-4 mt-4">
            <div class="flex gap-4">
                <div class="flex-1">
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input
                        id="first_name"
                        name="first_name"
                        type="text"
                        required
                        value="<?= htmlspecialchars($user['first_name']) ?>"
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
                        value="<?= htmlspecialchars($user['last_name']) ?>"
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
                    value="<?= htmlspecialchars($user['email']) ?>"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                <p class="text-sm text-gray-500"><?= date('d M Y', strtotime($user['created_at'])) ?></p>
            </div>

            <?php $label = 'Save Changes'; $variant = 'primary'; $type = 'submit'; $class = 'w-full'; include __DIR__ . '/../../elements/button.php'; ?>
        </form>
    </div>

    <!-- Change Password Section -->
    <div class="bg-white border border-black p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">Change Password</h2>

        <?php if ($passwordSuccess) : ?>
            <?php $type = 'success'; $message = $passwordSuccess; include __DIR__ . '/../../elements/alert.php'; ?>
        <?php endif; ?>
        <?php if ($passwordError) : ?>
            <?php $type = 'error'; $message = $passwordError; include __DIR__ . '/../../elements/alert.php'; ?>
        <?php endif; ?>

        <form hx-post="/account/password" hx-target="#main" class="flex flex-col gap-4 mt-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input
                    id="current_password"
                    name="current_password"
                    type="password"
                    required
                    placeholder="Your current password"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
                />
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input
                    id="new_password"
                    name="new_password"
                    type="password"
                    required
                    placeholder="Min. 6 characters"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
                />
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input
                    id="confirm_password"
                    name="confirm_password"
                    type="password"
                    required
                    placeholder="Repeat new password"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand"
                />
            </div>

            <?php $label = 'Change Password'; $variant = 'primary'; $type = 'submit'; $class = 'w-full'; include __DIR__ . '/../../elements/button.php'; ?>
        </form>
    </div>

    <!-- Logout Section -->
    <div class="bg-white border border-black p-6 mb-6">
        <h2 class="text-lg font-bold mb-2">Logout</h2>
        <p class="text-sm text-gray-500 mb-4">Sign out of your account.</p>
        <a href="/auth/logout" hx-boost="false" class="inline-block px-6 py-2.5 font-semibold text-sm uppercase tracking-wide transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 bg-white text-black border border-black hover:bg-gray-100 focus-visible:outline-black">Logout</a>
    </div>

    <!-- Delete Account Section -->
    <div class="bg-white border border-red-300 p-6">
        <h2 class="text-lg font-bold text-red-700 mb-2">Delete Account</h2>
        <p class="text-sm text-gray-500 mb-4">This action is permanent and cannot be undone. All your data and bookings will be deleted.</p>

        <?php if ($deleteError) : ?>
            <?php $type = 'error'; $message = $deleteError; include __DIR__ . '/../../elements/alert.php'; ?>
        <?php endif; ?>

        <form hx-post="/account/delete" hx-target="#main" hx-confirm="Are you sure you want to delete your account? This cannot be undone." class="flex flex-col gap-4 mt-4">
            <div>
                <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm your password</label>
                <input
                    id="delete_password"
                    name="password"
                    type="password"
                    required
                    placeholder="Enter your password"
                    class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                />
            </div>

            <button
                type="submit"
                class="w-full px-6 py-2.5 font-semibold text-sm uppercase tracking-wide transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600"
            >
                Delete Account
            </button>
        </form>
    </div>
</div>
