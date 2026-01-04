<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-6 py-8">
                <h3 class="text-gray-700 text-3xl font-medium">Edit Data Orang Tua</h3>
                
                <div class="mt-8">
                    <div class="p-6 bg-white rounded-md shadow-md">
                        <h2 class="text-lg text-gray-700 font-semibold capitalize mb-4">Form Edit Orang Tua</h2>
                        <form id="editForm" action="<?= BASEURL; ?>/admin/parents_update" method="POST">
                            <input type="hidden" name="id" value="<?= $data['parent']['id']; ?>">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label class="text-gray-700" for="full_name">Nama Lengkap</label>
                                    <input name="full_name" type="text" value="<?= $data['parent']['full_name']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="phone">No. Telepon</label>
                                    <input name="phone" type="text" value="<?= $data['parent']['phone']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="text-gray-700" for="username">Username</label>
                                    <input name="username" type="text" value="<?= $data['parent']['username']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="email">Email</label>
                                    <input name="email" type="email" value="<?= $data['parent']['email']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                            </div>
                            <div class="flex justify-end mt-4 space-x-2">
                                <a href="<?= BASEURL; ?>/admin/parents" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:bg-gray-400">Batal</a>
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
                window.location.href = '<?= BASEURL; ?>/admin/parents?success=Data orang tua berhasil diupdate';
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
