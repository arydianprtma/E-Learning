<?php require_once dirname(__DIR__) . '/Layouts/header.php'; ?>
<?php require_once dirname(__DIR__) . '/Layouts/sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Pengumuman</h1>
            <a href="<?= BASEURL; ?>/admin/announcements" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="<?= BASEURL; ?>/admin/announcements_update" method="POST">
                <input type="hidden" name="id" value="<?= $data['announcement']['id']; ?>">
                
                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Judul</label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($data['announcement']['title']); ?>" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>

                <div class="mb-6">
                    <label for="content" class="block mb-2 text-sm font-medium text-gray-900">Isi Pengumuman</label>
                    <textarea name="content" id="content" rows="10" 
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required><?= htmlspecialchars($data['announcement']['content']); ?></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="<?= BASEURL; ?>/admin/announcements" class="text-gray-700 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-gray-100 hover:text-blue-700">
                        Batal
                    </a>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<?php require_once dirname(__DIR__) . '/Layouts/footer.php'; ?>