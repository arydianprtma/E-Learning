<?php require_once dirname(__DIR__) . '/Layouts/header.php'; ?>
<?php require_once dirname(__DIR__) . '/Layouts/sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Manajemen Pengumuman</h1>
            <button onclick="document.getElementById('addAnnouncementModal').classList.remove('hidden')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengumuman</span>
            </button>
        </div>

        <?php if(isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p><?= $_GET['success']; ?></p>
        </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= $_GET['error']; ?></p>
        </div>
        <?php endif; ?>

        <!-- Announcements Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Judul</th>
                        <th scope="col" class="px-6 py-3">Isi</th>
                        <th scope="col" class="px-6 py-3">Dibuat Oleh</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = ($data['pagination']['current_page'] - 1) * 10 + 1;
                    foreach($data['announcements'] as $row): 
                    ?>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            <?= $no++; ?>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            <?= htmlspecialchars($row['title']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= substr(strip_tags($row['content']), 0, 100) . (strlen($row['content']) > 100 ? '...' : ''); ?>
                        </td>
                         <td class="px-6 py-4">
                            <?= htmlspecialchars($row['author_name']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= date('d M Y H:i', strtotime($row['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= BASEURL; ?>/admin/announcements_edit/<?= $row['id']; ?>" 
                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">Edit</a>
                            <a href="<?= BASEURL; ?>/admin/announcements_delete/<?= $row['id']; ?>" 
                               onclick="confirmAction(event, this.href, 'Hapus Pengumuman?', 'Apakah anda yakin ingin menghapus pengumuman ini?', 'warning', 'Ya, Hapus!', '#d33')"
                               class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($data['announcements'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pengumuman
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($data['pagination']['total_pages'] > 1): ?>
        <div class="flex justify-center mt-6">
            <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px text-sm">
                    <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                    <li>
                        <a href="<?= BASEURL; ?>/admin/announcements?page=<?= $i; ?>" 
                           class="flex items-center justify-center px-3 h-8 leading-tight <?= $i == $data['pagination']['current_page'] ? 'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'; ?>">
                            <?= $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- Add Announcement Modal -->
<div id="addAnnouncementModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black bg-opacity-50 flex">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Pengumuman
                </h3>
                <button type="button" onclick="document.getElementById('addAnnouncementModal').classList.add('hidden')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="<?= BASEURL; ?>/admin/announcements_add" method="POST">
                <div class="p-4 md:p-5 space-y-4">
                    <div>
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
                        <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label for="content" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi Pengumuman</label>
                        <textarea name="content" id="content" rows="6" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required></textarea>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan</button>
                    <button type="button" onclick="document.getElementById('addAnnouncementModal').classList.add('hidden')" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/Layouts/footer.php'; ?>