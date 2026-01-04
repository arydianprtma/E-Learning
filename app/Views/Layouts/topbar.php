<header class="flex justify-between items-center h-16 px-6 bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
    <div class="flex items-center">
        <button class="text-gray-500 focus:outline-none md:hidden p-2 rounded-md hover:bg-gray-100 mr-2" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <h2 class="text-xl font-semibold text-gray-800 tracking-tight">
            <?= $data['title'] ?? 'Dashboard'; ?>
        </h2>
    </div>
    <div class="flex items-center space-x-4">
        <!-- Notification Icon (Static for now) -->
        <button class="text-gray-400 hover:text-gray-600 p-2 relative">
            <i class="fas fa-bell fa-lg"></i>
            <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
        </button>
        
        <div class="flex items-center space-x-3 border-l border-gray-200 pl-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-700"><?= $_SESSION['username'] ?? 'User'; ?></p>
                <p class="text-xs text-gray-500 capitalize"><?= $_SESSION['role'] ?? 'Guest'; ?></p>
            </div>
            <div class="relative">
                <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-100 shadow-sm" 
                     src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?? 'User'); ?>&background=random&color=fff" 
                     alt="Avatar">
            </div>
        </div>
    </div>
</header>