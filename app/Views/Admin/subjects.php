<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100 font-sans">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                
                <!-- Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Total Subjects -->
                    <div class="bg-gradient-to-r from-teal-600 to-teal-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-[1.02] transition-transform duration-300">
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-teal-100 text-sm font-medium mb-1">Total Mata Pelajaran</p>
                                <h2 class="text-3xl font-bold"><?= count($data['subjects']); ?></h2>
                                <p class="text-teal-100 mt-2 text-xs">
                                    Mapel Terdaftar
                                </p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                                <i class="fas fa-book-open text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects List Summary -->
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-teal-500 transform hover:scale-[1.02] transition-transform duration-300">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Daftar Kurikulum</p>
                                <h2 class="text-xl font-bold text-gray-800">
                                    Kurikulum Sekolah
                                </h2>
                                <p class="text-gray-400 mt-2 text-xs flex items-center gap-1">
                                    <i class="fas fa-check-circle text-teal-500"></i>
                                    Status Aktif
                                </p>
                            </div>
                            <div class="p-3 bg-teal-50 rounded-lg">
                                <i class="fas fa-list-alt text-2xl text-teal-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Actions -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div class="flex-1 w-full md:w-auto">
                        <form action="<?= BASEURL; ?>/admin/subjects" method="GET" class="relative">
                            <input type="text" name="keyword" value="<?= isset($data['pagination']['keyword']) ? $data['pagination']['keyword'] : ''; ?>" placeholder="Cari nama atau kode mapel..." class="w-full md:w-64 pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </form>
                    </div>
                    <button onclick="openModal('addModal')" class="bg-primary text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Mapel</span>
                    </button>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-3 bg-blue-50 border-l-4 border-blue-400">
                        <p class="text-xs font-semibold text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span class="font-bold">Informasi:</span> Aksi akan muncul ketika mouse/kursor diarahkan ke setiap baris data.
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                        
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mapel</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($data['subjects'] as $subject): ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800 border border-teal-200">
                                            <?= $subject['code']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-teal-50 rounded-full flex items-center justify-center text-teal-500 mr-3">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900"><?= $subject['name']; ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($subject)); ?>)" class="text-amber-500 hover:text-amber-700 transition-colors" title="Edit">
                                                <i class="fas fa-edit text-lg"></i>
                                            </button>
                                            <button onclick="deleteSubject(<?= $subject['id']; ?>)" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                                <i class="fas fa-trash-alt text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(empty($data['subjects'])): ?>
                        <div class="p-8 text-center text-gray-500">
                            <div class="mb-3">
                                <i class="fas fa-book-open text-4xl text-gray-300"></i>
                            </div>
                            <p>Belum ada data mata pelajaran.</p>
                        </div>
                    <?php else: ?>
                         <!-- Pagination -->
                         <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                Menampilkan <span class="font-medium"><?= ($data['pagination']['current_page'] - 1) * 10 + 1; ?></span> sampai <span class="font-medium"><?= min($data['pagination']['current_page'] * 10, $data['pagination']['total_rows']); ?></span> dari <span class="font-medium"><?= $data['pagination']['total_rows']; ?></span> data
                            </div>
                            <div class="flex gap-2">
                                <?php if ($data['pagination']['current_page'] > 1): ?>
                                    <a href="<?= BASEURL; ?>/admin/subjects?page=<?= $data['pagination']['current_page'] - 1; ?>&keyword=<?= $data['pagination']['keyword']; ?>" class="px-3 py-1 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                                        <i class="fas fa-chevron-left mr-1"></i> Prev
                                    </a>
                                <?php endif; ?>

                                <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                    <?php if ($i == $data['pagination']['current_page']): ?>
                                        <span class="px-3 py-1 rounded-md bg-teal-600 border border-teal-600 text-white text-sm font-medium">
                                            <?= $i; ?>
                                        </span>
                                    <?php elseif ($i <= 3 || $i >= $data['pagination']['total_pages'] - 2 || abs($i - $data['pagination']['current_page']) <= 1): ?>
                                        <a href="<?= BASEURL; ?>/admin/subjects?page=<?= $i; ?>&keyword=<?= $data['pagination']['keyword']; ?>" class="px-3 py-1 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                                            <?= $i; ?>
                                        </a>
                                    <?php elseif ($i == 4 && $data['pagination']['current_page'] > 5): ?>
                                        <span class="px-2 py-1 text-gray-400">...</span>
                                    <?php elseif ($i == $data['pagination']['total_pages'] - 3 && $data['pagination']['current_page'] < $data['pagination']['total_pages'] - 4): ?>
                                        <span class="px-2 py-1 text-gray-400">...</span>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                    <a href="<?= BASEURL; ?>/admin/subjects?page=<?= $data['pagination']['current_page'] + 1; ?>&keyword=<?= $data['pagination']['keyword']; ?>" class="px-3 py-1 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                                        Next <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('addModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-book-medical text-teal-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Mapel Baru</h3>
                        <div class="mt-2">
                            <form id="addForm" action="<?= BASEURL; ?>/admin/subjects_add" method="POST" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Mapel (e.g. MAT01)</label>
                                    <input type="text" name="code" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 border p-2.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mapel</label>
                                    <input type="text" name="name" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 border p-2.5">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitForm('addForm')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Simpan
                </button>
                <button type="button" onclick="closeModal('addModal')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('editModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-edit text-amber-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Mata Pelajaran</h3>
                        <div class="mt-2">
                            <form id="editForm" action="<?= BASEURL; ?>/admin/subjects_update" method="POST" class="space-y-4">
                                <input type="hidden" name="id" id="edit_id">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Mapel (e.g. MAT01)</label>
                                    <input type="text" name="code" id="edit_code" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 border p-2.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mapel</label>
                                    <input type="text" name="name" id="edit_name" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 border p-2.5">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitForm('editForm')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Update
                </button>
                <button type="button" onclick="closeModal('editModal')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_code').value = data.code;
    document.getElementById('edit_name').value = data.name;
    openModal('editModal');
}

function submitForm(formId) {
    document.getElementById(formId).dispatchEvent(new Event('submit'));
}

function deleteSubject(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data mata pelajaran akan dihapus permanen! Jika sedang digunakan dalam jadwal, penghapusan mungkin gagal.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASEURL; ?>/admin/subjects_delete/' + id;
        }
    });
}

// Attach event listeners to forms
['addForm', 'editForm'].forEach(formId => {
    document.getElementById(formId).addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.closest('.inline-block').querySelector('button[onclick^="submitForm"]');
        const originalText = submitBtn.innerText;
        
        submitBtn.innerText = 'Memproses...';
        submitBtn.disabled = true;

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const contentType = response.headers.get('content-type');
            
            if (contentType && contentType.includes('application/json')) {
                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.error.message || 'Terjadi kesalahan');
                }
            } 
            
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                 window.location.reload();
            }

        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err.message || 'Terjadi kesalahan koneksi',
                confirmButtonColor: '#ef4444'
            });
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    });
});
</script>

<?php require_once '../app/Views/Layouts/footer.php'; ?>