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
                    <?php 
                    $activeYear = null;
                    foreach($data['years'] as $y) {
                        if($y['is_active']) {
                            $activeYear = $y;
                            break;
                        }
                    }
                    ?>
                    <!-- Active Year Card -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-[1.02] transition-transform duration-300">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium mb-1">Tahun Akademik Aktif</p>
                                <h2 class="text-3xl font-bold">
                                    <?= $activeYear ? $activeYear['name'] : 'Belum diset'; ?>
                                </h2>
                                <p class="text-blue-100 mt-2 flex items-center gap-2">
                                    <i class="fas fa-calendar-check"></i>
                                    Semester <?= $activeYear ? ucfirst($activeYear['semester']) : '-'; ?>
                                </p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Years Card -->
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500 transform hover:scale-[1.02] transition-transform duration-300">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Total Riwayat Tahun</p>
                                <h2 class="text-3xl font-bold text-gray-800">
                                    <?= count($data['years']); ?>
                                </h2>
                                <p class="text-gray-400 mt-2 flex items-center gap-2">
                                    <i class="fas fa-history"></i>
                                    Arsip Data
                                </p>
                            </div>
                            <div class="p-3 bg-indigo-50 rounded-lg">
                                <i class="fas fa-layer-group text-2xl text-indigo-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Header -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-gray-800 text-2xl font-bold">Daftar Tahun Akademik</h3>
                        <p class="text-gray-500 text-sm mt-1">Kelola data tahun ajaran dan semester sekolah.</p>
                    </div>
                    <button onclick="openModal('addModal')" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Tahun Baru</span>
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
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($data['years'] as $year): ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= $year['name']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 capitalize">
                                        <?php if($year['semester'] == 'odd'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-leaf text-[10px]"></i> Ganjil
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-snowflake text-[10px]"></i> Genap
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($year['is_active']): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ring-1 ring-inset ring-green-600/20">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                Non-Aktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <?php if(!$year['is_active']): ?>
                                                <a href="<?= BASEURL; ?>/admin/academic_years_activate/<?= $year['id']; ?>" class="text-green-600 hover:text-green-900" title="Set Aktif">
                                                    <i class="fas fa-check-circle text-lg"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($year)); ?>)" class="text-amber-500 hover:text-amber-700 transition-colors" title="Edit">
                                                <i class="fas fa-edit text-lg"></i>
                                            </button>
                                            
                                            <button onclick="deleteYear(<?= $year['id']; ?>)" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                                <i class="fas fa-trash-alt text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(empty($data['years'])): ?>
                        <div class="p-8 text-center text-gray-500">
                            <div class="mb-3">
                                <i class="fas fa-calendar-times text-4xl text-gray-300"></i>
                            </div>
                            <p>Belum ada data tahun akademik.</p>
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
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-calendar-plus text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Tahun Akademik</h3>
                        <div class="mt-2">
                            <form id="addForm" action="<?= BASEURL; ?>/admin/academic_years_add" method="POST" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun (e.g. 2024/2025)</label>
                                    <input type="text" name="name" required placeholder="YYYY/YYYY" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 border p-2.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                                    <select name="semester" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 border p-2.5">
                                        <option value="odd">Ganjil (Odd)</option>
                                        <option value="even">Genap (Even)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-5 h-5">
                                        <span class="ml-2 text-sm text-gray-700">Set sebagai tahun aktif?</span>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitForm('addForm')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Tahun Akademik</h3>
                        <div class="mt-2">
                            <form id="editForm" action="<?= BASEURL; ?>/admin/academic_years_update" method="POST" class="space-y-4">
                                <input type="hidden" name="id" id="edit_id">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun (e.g. 2024/2025)</label>
                                    <input type="text" name="name" id="edit_name" required placeholder="YYYY/YYYY" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 border p-2.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                                    <select name="semester" id="edit_semester" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 border p-2.5">
                                        <option value="odd">Ganjil (Odd)</option>
                                        <option value="even">Genap (Even)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-300 focus:ring focus:ring-amber-200 focus:ring-opacity-50 w-5 h-5">
                                        <span class="ml-2 text-sm text-gray-700">Set sebagai tahun aktif?</span>
                                    </label>
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
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_semester').value = data.semester;
    document.getElementById('edit_is_active').checked = data.is_active == 1;
    openModal('editModal');
}

function submitForm(formId) {
    document.getElementById(formId).dispatchEvent(new Event('submit'));
}

function deleteYear(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data tahun akademik akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASEURL; ?>/admin/academic_years_delete/' + id;
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
                // If success json but we expect redirect usually, handle here if needed
                // But controller redirects, so response.redirected should be true or we follow logic
            } 
            
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                // Fallback reload if no redirect detected but success
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