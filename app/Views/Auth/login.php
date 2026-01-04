<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">E-Learning SMA</h2>
            <p class="mt-2 text-sm text-gray-600">Silakan login untuk masuk ke sistem</p>
        </div>
        
        <?php if(!empty($data['error'])): ?>
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <?= $data['error']; ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="<?= BASEURL; ?>/auth/login" method="POST">
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input id="username" name="username" type="text" required class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Masukkan username">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Masukkan password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <a href="#" class="font-medium text-primary hover:text-indigo-500">
                        Lupa password? Hubungi TU
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>
