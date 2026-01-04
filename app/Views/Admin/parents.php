<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-6 py-8">
                <!-- Header Section -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h3 class="text-purple-700 text-3xl font-medium">Manajemen Orang Tua</h3>
                    
                    <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 mt-4 md:mt-0 w-full md:w-auto">
                        <!-- Search Bar -->
                        <form action="<?= BASEURL; ?>/admin/parents" method="GET" class="relative group">
                            <input type="text" name="keyword" value="<?= isset($data['pagination']['keyword']) ? $data['pagination']['keyword'] : ''; ?>" 
                                   placeholder="Cari orang tua (nama/telp/username)..." 
                                   class="w-full md:w-80 pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all shadow-sm group-hover:border-purple-300">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                            </div>
                        </form>

                        <!-- Add Button -->
                        <button onclick="toggleModal('addModal')" class="bg-purple-600 text-white px-4 py-2.5 rounded-lg shadow-md hover:bg-purple-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i> Tambah Orang Tua
                        </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full shadow-md overflow-hidden sm:rounded-lg border-b border-gray-200">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Nama / Telepon</th>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Akun</th>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <?php if(empty($data['parents'])): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                                <i class="fas fa-search mb-3 text-4xl text-gray-300"></i>
                                                <p>Tidak ada data orang tua yang ditemukan.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($data['parents'] as $parent): ?>
                                        <tr class="hover:bg-purple-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full shadow-sm object-cover" src="https://ui-avatars.com/api/?name=<?= urlencode($parent['full_name']); ?>&background=random" alt="" />
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm leading-5 font-bold text-gray-800"><?= $parent['full_name']; ?></div>
                                                        <div class="text-xs leading-5 text-gray-500 bg-gray-100 px-2 py-0.5 rounded inline-block mt-1">Telp: <?= $parent['identification_number'] ?? '-'; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 font-medium text-gray-900"><?= $parent['username']; ?></div>
                                                <div class="text-xs leading-5 text-gray-500"><?= $parent['email']; ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <?php if($parent['is_active']): ?>
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5 self-center"></span>
                                                        Aktif
                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5 self-center"></span>
                                                        Non-Aktif
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                                <div class="flex space-x-3">
                                                    <a href="<?= BASEURL; ?>/admin/parents_edit/<?= $parent['id']; ?>" class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <a href="<?= BASEURL; ?>/admin/user_toggle_active/<?= $parent['id']; ?>/parents" class="<?= $parent['is_active'] ? 'text-orange-500 hover:text-orange-700' : 'text-green-600 hover:text-green-800'; ?> transition-colors" title="<?= $parent['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>">
                                                        <i class="fas <?= $parent['is_active'] ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                                    </a>

                                                    <button onclick="openResetPasswordModal(<?= $parent['id']; ?>, '<?= $parent['username']; ?>')" class="text-blue-600 hover:text-blue-900 transition-colors" title="Reset Password">
                                                        <i class="fas fa-key"></i>
                                                    </button>

                                                    <a href="<?= BASEURL; ?>/admin/parents_delete/<?= $parent['id']; ?>" class="text-red-600 hover:text-red-900 transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <?php if ($data['pagination']['total_pages'] > 1): ?>
                    <div class="mt-6 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 shadow-sm rounded-lg">
                        <div class="flex flex-1 justify-between sm:hidden">
                            <?php if ($data['pagination']['current_page'] > 1): ?>
                                <a href="<?= BASEURL; ?>/admin/parents?page=<?= $data['pagination']['current_page'] - 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400">Previous</span>
                            <?php endif; ?>
                            
                            <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                <a href="<?= BASEURL; ?>/admin/parents?page=<?= $data['pagination']['current_page'] + 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                            <?php else: ?>
                                <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400">Next</span>
                            <?php endif; ?>
                        </div>
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
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
                                        <a href="<?= BASEURL; ?>/admin/parents?page=<?= $data['pagination']['current_page'] - 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Numbers -->
                                    <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                        <a href="<?= BASEURL; ?>/admin/parents?page=<?= $i; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" 
                                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium 
                                           <?= $i == $data['pagination']['current_page'] ? 'bg-purple-50 text-purple-600 z-10 border-purple-500' : 'bg-white text-gray-500 hover:bg-gray-50' ?>">
                                            <?= $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <!-- Next -->
                                    <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                        <a href="<?= BASEURL; ?>/admin/parents?page=<?= $data['pagination']['current_page'] + 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Tambah Orang Tua -->
<div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('addModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-user-plus text-purple-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Orang Tua Baru</h3>
                        <div class="mt-4">
                            <form id="addForm" action="<?= BASEURL; ?>/admin/parents_add" method="POST">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="full_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                        <input type="text" name="phone" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Username</label>
                                        <input type="text" name="username" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Password</label>
                                        <input type="password" name="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('addForm').submit()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan
                </button>
                <button type="button" onclick="toggleModal('addModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="resetPasswordModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeResetPasswordModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-key text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Reset Password Orang Tua</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">
                                Masukkan password baru untuk user <span id="reset_username" class="font-bold"></span>.
                            </p>
                            <form id="resetPasswordForm" action="" method="POST">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">
                                        Password Baru
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" id="new_password" name="password" type="password" placeholder="Masukkan password baru" required minlength="6">
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2" onclick="closeResetPasswordModal()">
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
    function toggleModal(modalID){
        document.getElementById(modalID).classList.toggle("hidden");
    }

    function openResetPasswordModal(userId, username) {
        const modal = document.getElementById('resetPasswordModal');
        const form = document.getElementById('resetPasswordForm');
        const usernameSpan = document.getElementById('reset_username');
        
        // Set action URL
        form.action = "<?= BASEURL; ?>/admin/user_reset_password/" + userId + "/parents";
        
        // Set username display
        usernameSpan.textContent = username;
        
        // Show modal
        modal.classList.remove('hidden');
    }

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
    }
</script>

<?php require_once '../app/Views/Layouts/footer.php'; ?>