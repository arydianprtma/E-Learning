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
                    <h3 class="text-gray-700 text-3xl font-medium">Edit Admin</h3>
                    <a href="<?= BASEURL; ?>/admin/admins" class="text-indigo-600 hover:text-indigo-900">Kembali</a>
                </div>
                
                <div class="mt-8 bg-white p-6 rounded-md shadow-md">
                    <form id="editForm" action="<?= BASEURL; ?>/admin/admins_update" method="POST">
                        <input type="hidden" name="id" value="<?= $data['admin']['id']; ?>">
                        
                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username</label>
                                <input type="text" name="username" value="<?= $data['admin']['username']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2 border">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="<?= $data['admin']['email']; ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2 border">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Akun</label>
                                <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2 border">
                                    <option value="1" <?= $data['admin']['is_active'] ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="0" <?= !$data['admin']['is_active'] ? 'selected' : ''; ?>>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">Update Admin</button>
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
            // If it's not JSON, it's likely a redirect (success)
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                // Fallback
                window.location.href = '<?= BASEURL; ?>/admin/admins?success=Admin berhasil diupdate';
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