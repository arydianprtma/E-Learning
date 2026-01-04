<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-6 py-8">
                <h3 class="text-gray-700 text-3xl font-medium">Edit Data Guru</h3>
                
                <div class="mt-8">
                    <div class="p-6 bg-white rounded-md shadow-md">
                        <h2 class="text-lg text-gray-700 font-semibold capitalize mb-4">Form Edit Guru</h2>
                        <form id="editForm" action="<?= BASEURL; ?>/admin/teachers_update" method="POST">
                            <input type="hidden" name="id" value="<?= $data['teacher']['id']; ?>">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label class="text-gray-700" for="full_name">Nama Lengkap</label>
                                    <input name="full_name" type="text" value="<?= $data['teacher']['full_name']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="nip">NIP</label>
                                    <input name="nip" type="text" value="<?= $data['teacher']['nip']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="username">Username</label>
                                    <input name="username" type="text" value="<?= $data['teacher']['username']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="email">Email</label>
                                    <input name="email" type="email" value="<?= $data['teacher']['email']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="is_active">Status Akun</label>
                                    <select name="is_active" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="1" <?= $data['teacher']['is_active'] ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="0" <?= !$data['teacher']['is_active'] ? 'selected' : ''; ?>>Nonaktif</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Data Kepegawaian -->
                            <div class="mt-6 border-t pt-4">
                                <h3 class="text-md font-semibold text-gray-700 mb-4">Data Kepegawaian</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-gray-700" for="employment_status">Status Kepegawaian</label>
                                        <select name="employment_status" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">- Pilih Status -</option>
                                            <option value="PNS" <?= ($data['teacher']['employment_status'] ?? '') == 'PNS' ? 'selected' : ''; ?>>PNS</option>
                                            <option value="PPPK" <?= ($data['teacher']['employment_status'] ?? '') == 'PPPK' ? 'selected' : ''; ?>>PPPK</option>
                                            <option value="Honorer" <?= ($data['teacher']['employment_status'] ?? '') == 'Honorer' ? 'selected' : ''; ?>>Honorer</option>
                                            <option value="Tetap Yayasan" <?= ($data['teacher']['employment_status'] ?? '') == 'Tetap Yayasan' ? 'selected' : ''; ?>>Tetap Yayasan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="position">Jabatan</label>
                                        <select name="position" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">- Pilih Jabatan -</option>
                                            <option value="Guru Mapel" <?= ($data['teacher']['position'] ?? '') == 'Guru Mapel' ? 'selected' : ''; ?>>Guru Mapel</option>
                                            <option value="Wali Kelas" <?= ($data['teacher']['position'] ?? '') == 'Wali Kelas' ? 'selected' : ''; ?>>Wali Kelas</option>
                                            <option value="Guru BK" <?= ($data['teacher']['position'] ?? '') == 'Guru BK' ? 'selected' : ''; ?>>Guru BK</option>
                                            <option value="Kepala Sekolah" <?= ($data['teacher']['position'] ?? '') == 'Kepala Sekolah' ? 'selected' : ''; ?>>Kepala Sekolah</option>
                                            <option value="Staff Tata Usaha" <?= ($data['teacher']['position'] ?? '') == 'Staff Tata Usaha' ? 'selected' : ''; ?>>Staff Tata Usaha</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="subjects">Mata Pelajaran Diampu</label>
                                        <input name="subjects" type="text" value="<?= $data['teacher']['subjects'] ?? ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Matematika, Fisika">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="start_teaching_date">Tanggal Mulai Mengajar</label>
                                        <input name="start_teaching_date" type="date" value="<?= $data['teacher']['start_teaching_date'] ?? ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="teaching_hours_per_week">Jam Mengajar per Minggu</label>
                                        <input name="teaching_hours_per_week" type="number" min="0" value="<?= $data['teacher']['teaching_hours_per_week'] ?? ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="status_detail">Status Keaktifan</label>
                                        <select name="status_detail" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="Aktif" <?= ($data['teacher']['status_detail'] ?? '') == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                                            <option value="Cuti" <?= ($data['teacher']['status_detail'] ?? '') == 'Cuti' ? 'selected' : ''; ?>>Cuti</option>
                                            <option value="Nonaktif" <?= ($data['teacher']['status_detail'] ?? '') == 'Nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-4 space-x-2">
                                <a href="<?= BASEURL; ?>/admin/teachers" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:bg-gray-400">Batal</a>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]'); // Assuming the update button is submit type
    // If there are multiple buttons, we might need a more specific selector, but usually update is the primary submit
    // In the provided code: <button class="...">Update</button> inside form, default type is submit.
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
                window.location.href = '<?= BASEURL; ?>/admin/teachers?success=Data guru berhasil diupdate';
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
