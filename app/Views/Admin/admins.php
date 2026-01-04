<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100 font-sans">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <div class="container mx-auto px-6 py-8">
                <!-- Header Section -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800">Manajemen Admin</h3>
                        <p class="text-gray-500 mt-1">Kelola data administrator sistem</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button onclick="openModal('addModal')" class="bg-purple-600 text-white px-5 py-2.5 rounded-lg shadow-lg hover:bg-purple-700 transition duration-300 flex items-center gap-2 transform hover:-translate-y-0.5">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Admin</span>
                        </button>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex items-center justify-between animate-fade-in-down" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <p><?= htmlspecialchars($_GET['success']); ?></p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm flex items-center justify-between animate-fade-in-down" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <p><?= htmlspecialchars($_GET['error']); ?></p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Search and Toolbar -->
                <div class="bg-white rounded-xl shadow-sm p-5 mb-6 border border-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex-1 w-full md:w-auto">
                            <form action="<?= BASEURL; ?>/admin/admins" method="GET" class="relative group">
                                <input type="text" name="keyword" value="<?= isset($data['pagination']['keyword']) ? $data['pagination']['keyword'] : ''; ?>" 
                                       placeholder="Cari admin (username/email)..." 
                                       class="w-full md:w-80 pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all shadow-sm group-hover:border-purple-300">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                                </div>
                            </form>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <span>Total Admin:</span>
                            <span class="font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full"><?= $data['pagination']['total_rows']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-purple-50">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-user text-purple-400"></i>
                                            Username
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-envelope text-purple-400"></i>
                                            Email
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-toggle-on text-purple-400"></i>
                                            Status
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-purple-800 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php if(empty($data['admins'])): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="bg-gray-100 p-4 rounded-full mb-3">
                                                    <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                                </div>
                                                <p class="font-medium">Tidak ada data admin ditemukan</p>
                                                <?php if($data['pagination']['keyword']): ?>
                                                    <p class="text-sm mt-1">Coba kata kunci pencarian lain</p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($data['admins'] as $admin): ?>
                                    <tr class="hover:bg-purple-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full border-2 border-purple-100 shadow-sm" src="https://ui-avatars.com/api/?name=<?= urlencode($admin['username']); ?>&background=random&color=fff" alt="" />
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900"><?= $admin['username']; ?></div>
                                                    <div class="text-xs text-gray-400">ID: #<?= $admin['id']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600 flex items-center gap-2">
                                                <i class="far fa-envelope text-gray-400"></i>
                                                <?= $admin['email']; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($admin['is_active']): ?>
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    <i class="fas fa-check-circle mr-1.5 mt-0.5"></i> Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    <i class="fas fa-times-circle mr-1.5 mt-0.5"></i> Non-Aktif
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center items-center space-x-3">
                                                <a href="<?= BASEURL; ?>/admin/admins_edit/<?= $admin['id']; ?>" class="text-yellow-500 hover:text-yellow-700 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= BASEURL; ?>/admin/user_toggle_active/<?= $admin['id']; ?>/admins" class="<?= $admin['is_active'] ? 'text-orange-500 hover:text-orange-700 bg-orange-50 hover:bg-orange-100' : 'text-green-500 hover:text-green-700 bg-green-50 hover:bg-green-100'; ?> p-2 rounded-lg transition-colors" title="<?= $admin['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>">
                                                    <i class="fas <?= $admin['is_active'] ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                                </a>
                                                <button onclick="openResetPasswordModal(<?= $admin['id']; ?>, '<?= $admin['username']; ?>')" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Reset Password">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                                <?php if($admin['id'] != $_SESSION['user_id']): ?>
                                                <a href="<?= BASEURL; ?>/admin/admins_delete/<?= $admin['id']; ?>" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($data['pagination']['total_pages'] > 1): ?>
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Menampilkan
                                        <span class="font-medium"><?= ($data['pagination']['current_page'] - 1) * 10 + 1; ?></span>
                                        sampai
                                        <span class="font-medium"><?= min($data['pagination']['current_page'] * 10, $data['pagination']['total_rows']); ?></span>
                                        dari
                                        <span class="font-medium"><?= $data['pagination']['total_rows']; ?></span>
                                        hasil
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <!-- Previous -->
                                        <?php if ($data['pagination']['current_page'] > 1): ?>
                                            <a href="<?= BASEURL; ?>/admin/admins?page=<?= $data['pagination']['current_page'] - 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Previous</span>
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        <?php endif; ?>

                                        <!-- Numbers -->
                                        <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                            <a href="<?= BASEURL; ?>/admin/admins?page=<?= $i; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" 
                                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium 
                                               <?= $i == $data['pagination']['current_page'] ? 'bg-purple-50 text-purple-600 z-10 border-purple-500' : 'bg-white text-gray-500 hover:bg-gray-50' ?>">
                                                <?= $i; ?>
                                            </a>
                                        <?php endfor; ?>

                                        <!-- Next -->
                                        <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                            <a href="<?= BASEURL; ?>/admin/admins?page=<?= $data['pagination']['current_page'] + 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Next</span>
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        <?php endif; ?>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('addModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-user-shield text-purple-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Admin Baru</h3>
                        <div class="mt-4">
                            <form id="addForm" action="<?= BASEURL; ?>/admin/admins_add" method="POST">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input class="shadow-sm appearance-none border rounded-lg w-full py-2.5 pl-10 pr-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" id="username" name="username" type="text" placeholder="Masukkan username" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input class="shadow-sm appearance-none border rounded-lg w-full py-2.5 pl-10 pr-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" id="email" name="email" type="email" placeholder="contoh@sekolah.id" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input class="shadow-sm appearance-none border rounded-lg w-full py-2.5 pl-10 pr-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" id="password" name="password" type="password" placeholder="Min. 6 karakter" required>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Simpan
                                    </button>
                                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:w-auto sm:text-sm" onclick="closeModal('addModal')">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="resetPasswordModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('resetPasswordModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-key text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Reset Password Admin</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">
                                Masukkan password baru untuk user <span id="reset_username" class="font-bold"></span>.
                            </p>
                            <form id="resetPasswordForm" action="" method="POST">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">
                                        Password Baru
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" id="new_password" name="password" type="password" placeholder="Masukkan password baru" required minlength="6">
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2" onclick="closeModal('resetPasswordModal')">
                                        Batal
                                    </button>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openResetPasswordModal(userId, username) {
    const form = document.getElementById('resetPasswordForm');
    const usernameSpan = document.getElementById('reset_username');
    
    // Set action URL
    form.action = "<?= BASEURL; ?>/admin/user_reset_password/" + userId + "/admins";
    
    // Set username display
    usernameSpan.textContent = username;
    
    // Show modal
    openModal('resetPasswordModal');
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
        closeModal('addModal');
    }
}
</script>

<?php require_once '../app/Views/Layouts/footer.php'; ?>