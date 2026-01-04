<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-6 py-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-gray-700 text-3xl font-medium">Edit Kelas</h3>
                    <a href="<?= BASEURL; ?>/admin/classes" class="text-indigo-600 hover:text-indigo-900">Kembali</a>
                </div>
                
                <div class="mt-8 bg-white p-6 rounded-md shadow-md">
                    <form id="editForm" action="<?= BASEURL; ?>/admin/classes_update" method="POST">
                        <input type="hidden" name="id" value="<?= $data['class']['id']; ?>">
                        
                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Kelas (e.g. X IPA 1)</label>
                                <input type="text" name="name" value="<?= $data['class']['name']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2 border">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tingkat</label>
                                <select name="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2 border">
                                    <option value="X" <?= $data['class']['level'] == 'X' ? 'selected' : ''; ?>>X</option>
                                    <option value="XI" <?= $data['class']['level'] == 'XI' ? 'selected' : ''; ?>>XI</option>
                                    <option value="XII" <?= $data['class']['level'] == 'XII' ? 'selected' : ''; ?>>XII</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                                <select name="major" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2 border">
                                    <option value="IPA" <?= $data['class']['major'] == 'IPA' ? 'selected' : ''; ?>>IPA</option>
                                    <option value="IPS" <?= $data['class']['major'] == 'IPS' ? 'selected' : ''; ?>>IPS</option>
                                    <option value="Bahasa" <?= $data['class']['major'] == 'Bahasa' ? 'selected' : ''; ?>>Bahasa</option>
                                    <option value="Umum" <?= $data['class']['major'] == 'Umum' ? 'selected' : ''; ?>>Umum</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun Akademik</label>
                                <select name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2 border">
                                    <?php foreach($data['years'] as $year): ?>
                                        <option value="<?= $year['id']; ?>" <?= $data['class']['academic_year_id'] == $year['id'] ? 'selected' : ''; ?>>
                                            <?= $year['name']; ?> (<?= $year['semester']; ?>) <?= $year['is_active'] ? '- Aktif' : ''; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">Update Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    
    submitBtn.innerText = 'Menyimpan...';
    submitBtn.disabled = true;

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            const result = await response.json();
            if (!result.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: result.error.message,
                    confirmButtonColor: '#ef4444'
                });
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        } else {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                window.location.href = '<?= BASEURL; ?>/admin/classes?success=Kelas berhasil diupdate';
            }
        }
    } catch (err) {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan',
            text: 'Terjadi kesalahan koneksi',
            confirmButtonColor: '#ef4444'
        });
        submitBtn.innerText = originalText;
        submitBtn.disabled = false;
    }
});
</script>

<?php require_once '../app/Views/Layouts/footer.php'; ?>