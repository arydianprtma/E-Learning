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
                    <h3 class="text-purple-700 text-3xl font-medium">Manajemen Siswa</h3>
                    
                    <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 mt-4 md:mt-0 w-full md:w-auto">
                        <!-- Search & Filter Form -->
                        <form action="<?= BASEURL; ?>/admin/students" method="GET" class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
                            <!-- Status Filter -->
                            <div class="relative">
                                <select name="status" onchange="this.form.submit()" class="appearance-none w-full md:w-40 pl-3 pr-8 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all shadow-sm bg-white text-gray-700 cursor-pointer">
                                    <option value="" <?= !isset($data['pagination']['status']) || $data['pagination']['status'] === null ? 'selected' : ''; ?>>Semua Status</option>
                                    <option value="0" <?= isset($data['pagination']['status']) && $data['pagination']['status'] === 0 ? 'selected' : ''; ?>>Masih Sekolah</option>
                                    <option value="1" <?= isset($data['pagination']['status']) && $data['pagination']['status'] === 1 ? 'selected' : ''; ?>>Lulus (Alumni)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>

                            <!-- Search Bar -->
                            <div class="relative group">
                                <input type="text" name="keyword" value="<?= isset($data['pagination']['keyword']) ? $data['pagination']['keyword'] : ''; ?>" 
                                       placeholder="Cari siswa (nama/NIS/username)..." 
                                       class="w-full md:w-64 pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all shadow-sm group-hover:border-purple-300">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                                </div>
                            </div>
                        </form>

                        <!-- Add Button -->
                        <button onclick="toggleModal('addModal'); resetStudentForm();" class="bg-purple-600 text-white px-4 py-2.5 rounded-lg shadow-md hover:bg-purple-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i> Tambah Siswa
                        </button>
                    </div>
                </div>

                <!-- Flash Message -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-md rounded-r" role="alert">
                        <div class="flex">
                            <div class="py-1"><i class="fas fa-check-circle mr-3 text-green-500"></i></div>
                            <div>
                                <p class="font-bold">Berhasil!</p>
                                <p class="text-sm"><?= htmlspecialchars($_GET['success']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-md rounded-r" role="alert">
                        <div class="flex">
                            <div class="py-1"><i class="fas fa-exclamation-circle mr-3 text-red-500"></i></div>
                            <div>
                                <p class="font-bold">Error!</p>
                                <p class="text-sm"><?= htmlspecialchars($_GET['error']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Main Content -->
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full shadow-md overflow-hidden sm:rounded-lg border-b border-gray-200">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Nama / NIS</th>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Akun</th>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Kelulusan</th>
                                        <th class="px-6 py-4 border-b border-purple-200 bg-purple-50 text-left text-xs leading-4 font-bold text-purple-600 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <?php if(empty($data['students'])): ?>
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                                <i class="fas fa-search mb-3 text-4xl text-gray-300"></i>
                                                <p>Tidak ada data siswa yang ditemukan.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($data['students'] as $student): ?>
                                        <tr class="hover:bg-purple-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full shadow-sm object-cover" src="https://ui-avatars.com/api/?name=<?= urlencode($student['full_name']); ?>&background=random" alt="" />
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm leading-5 font-bold text-gray-800"><?= $student['full_name']; ?></div>
                                                        <div class="text-xs leading-5 text-gray-500 bg-gray-100 px-2 py-0.5 rounded inline-block mt-1">NIS: <?= $student['identification_number']; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 font-medium text-gray-900"><?= $student['username']; ?></div>
                                                <div class="text-xs leading-5 text-gray-500"><?= $student['email']; ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <?php if($student['is_active']): ?>
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
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <?php if($student['is_graduated']): ?>
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        <span class="w-2 h-2 bg-blue-400 rounded-full mr-1.5 self-center"></span>
                                                        Lulus
                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1.5 self-center"></span>
                                                        Masih Sekolah
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                                <div class="flex space-x-3">
                                                    <a href="<?= BASEURL; ?>/admin/students_edit/<?= $student['id']; ?>" class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <a href="<?= BASEURL; ?>/admin/student_toggle_graduation/<?= $student['id']; ?>" class="<?= $student['is_graduated'] ? 'text-gray-500 hover:text-gray-700' : 'text-purple-600 hover:text-purple-800'; ?> transition-colors" title="<?= $student['is_graduated'] ? 'Batalkan Kelulusan' : 'Set Lulus'; ?>" onclick="return confirmAction(event, this.href, 'Ubah Status Kelulusan?', 'Apakah anda yakin ingin mengubah status kelulusan siswa ini?', 'question', 'Ya, Ubah!', '#3b82f6')">
                                                        <i class="fas <?= $student['is_graduated'] ? 'fa-user-graduate' : 'fa-graduation-cap'; ?>"></i>
                                                    </a>

                                                    <a href="<?= BASEURL; ?>/admin/user_toggle_active/<?= $student['id']; ?>/students" class="<?= $student['is_active'] ? 'text-orange-500 hover:text-orange-700' : 'text-green-600 hover:text-green-800'; ?> transition-colors" title="<?= $student['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>">
                                                        <i class="fas <?= $student['is_active'] ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                                    </a>

                                                    <button onclick="openResetPasswordModal(<?= $student['id']; ?>, '<?= $student['username']; ?>')" class="text-blue-600 hover:text-blue-900 transition-colors" title="Reset Password">
                                                        <i class="fas fa-key"></i>
                                                    </button>

                                                    <a href="<?= BASEURL; ?>/admin/students_delete/<?= $student['id']; ?>" class="text-red-600 hover:text-red-900 transition-colors" title="Hapus" onclick="return confirmAction(event, this.href, 'Hapus Siswa?', 'Data siswa akan dihapus permanen!')">
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
                                <a href="<?= BASEURL; ?>/admin/students?page=<?= $data['pagination']['current_page'] - 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400">Previous</span>
                            <?php endif; ?>
                            
                            <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                <a href="<?= BASEURL; ?>/admin/students?page=<?= $data['pagination']['current_page'] + 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
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
                                        <a href="<?= BASEURL; ?>/admin/students?page=<?= $data['pagination']['current_page'] - 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Numbers -->
                                    <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                        <a href="<?= BASEURL; ?>/admin/students?page=<?= $i; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" 
                                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium 
                                           <?= $i == $data['pagination']['current_page'] ? 'bg-purple-50 text-purple-600 z-10 border-purple-500' : 'bg-white text-gray-500 hover:bg-gray-50' ?>">
                                            <?= $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <!-- Next -->
                                    <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                        <a href="<?= BASEURL; ?>/admin/students?page=<?= $data['pagination']['current_page'] + 1; ?><?= $data['pagination']['keyword'] ? '&keyword=' . $data['pagination']['keyword'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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

<!-- Modal Tambah Siswa -->
<div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('addModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-user-plus text-purple-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Siswa Baru</h3>
                        <div class="mt-2">
                            <form action="<?= BASEURL; ?>/admin/students_add" method="POST" id="addStudentForm" onsubmit="return validateStudentForm()">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="full_name">Nama Lengkap</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="full_name" name="full_name" type="text" placeholder="Nama Lengkap" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nis">NIS</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="nis" name="nis" type="text" placeholder="NIS" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nisn">NISN</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="nisn" name="nisn" type="text" placeholder="NISN" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="username" name="username" type="text" placeholder="Username" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="email" name="email" type="email" placeholder="Email">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="password" name="password" type="password" placeholder="Password" required>
                                    </div>
                                </div>

                                <!-- Personal Data Section -->
                                <div class="border-t border-gray-200 pt-4 mt-2">
                                    <h4 class="text-md font-bold text-gray-700 mb-3">Data Pribadi</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="gender">Jenis Kelamin</label>
                                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="gender" name="gender">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="place_of_birth">Tempat Lahir</label>
                                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="place_of_birth" name="place_of_birth" type="text" placeholder="Tempat Lahir">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="date_of_birth">Tanggal Lahir</label>
                                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="date_of_birth" name="date_of_birth" type="date">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="religion">Agama</label>
                                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="religion" name="religion">
                                                <option value="">Pilih Agama</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Kristen">Kristen</option>
                                                <option value="Katolik">Katolik</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Buddha">Buddha</option>
                                                <option value="Konghucu">Konghucu</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="citizenship">Kewarganegaraan</label>
                                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="citizenship" name="citizenship" type="text" value="Indonesia" placeholder="Kewarganegaraan">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">No HP</label>
                                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="phone" name="phone" type="text" placeholder="No HP">
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Data Section -->
                                <div class="border-t border-gray-200 pt-4 mt-2">
                                    <h4 class="text-md font-bold text-gray-700 mb-3">Data Alamat</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="col-span-1 md:col-span-2 mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="address">Alamat Lengkap</label>
                                            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="address" name="address" rows="2" placeholder="Alamat Lengkap"></textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="province">Provinsi</label>
                                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="province" name="province" type="text" placeholder="Provinsi">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="city">Kabupaten/Kota</label>
                                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="city" name="city" type="text" placeholder="Kabupaten/Kota">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="district">Kecamatan</label>
                                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="district" name="district" type="text" placeholder="Kecamatan">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="postal_code">Kode Pos</label>
                                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-500" id="postal_code" name="postal_code" type="text" placeholder="Kode Pos">
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-4 mt-2">
                                    <h4 class="text-md font-bold text-gray-700 mb-3">Info Masuk & Lulus</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Tanggal Masuk -->
                                        <div>
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                                            <div class="flex space-x-2">
                                                <input type="number" id="entry_day" class="shadow border rounded w-16 py-2 px-2 text-center" placeholder="DD" min="1" max="31" required oninput="updateDates()">
                                                <select id="entry_month" class="shadow border rounded flex-1 py-2 px-2" required onchange="updateDates()">
                                                    <option value="">Bulan</option>
                                                    <option value="01">Januari</option>
                                                    <option value="02">Februari</option>
                                                    <option value="03">Maret</option>
                                                    <option value="04">April</option>
                                                    <option value="05">Mei</option>
                                                    <option value="06">Juni</option>
                                                    <option value="07">Juli</option>
                                                    <option value="08">Agustus</option>
                                                    <option value="09">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                                <input type="number" id="entry_year" class="shadow border rounded w-20 py-2 px-2 text-center" placeholder="YYYY" min="2000" max="2100" required oninput="calculateGradYear()">
                                                
                                                <!-- Date Picker Helper -->
                                                <div class="relative">
                                                    <input type="date" id="entry_picker" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" onchange="fillDateFromPicker(this, 'entry')">
                                                    <button type="button" class="shadow border rounded border-gray-200 bg-gray-50 text-gray-500 hover:bg-white hover:text-indigo-600 hover:border-indigo-300 transition-all px-3 py-2 flex items-center justify-center" title="Pilih Tanggal dari Kalender">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="entry_date" id="entry_date">
                                        </div>

                                        <!-- Tanggal Lulus -->
                                        <div>
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lulus (Estimasi)</label>
                                            <div class="flex space-x-2">
                                                <input type="number" id="grad_day" class="shadow border rounded w-16 py-2 px-2 text-center" placeholder="DD" min="1" max="31" required oninput="updateDates()">
                                                <select id="grad_month" class="shadow border rounded flex-1 py-2 px-2" required onchange="updateDates()">
                                                    <option value="">Bulan</option>
                                                    <option value="01">Januari</option>
                                                    <option value="02">Februari</option>
                                                    <option value="03">Maret</option>
                                                    <option value="04">April</option>
                                                    <option value="05">Mei</option>
                                                    <option value="06">Juni</option>
                                                    <option value="07">Juli</option>
                                                    <option value="08">Agustus</option>
                                                    <option value="09">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                                <input type="number" id="grad_year" class="shadow border rounded w-20 py-2 px-2 text-center" placeholder="YYYY" min="2000" max="2100" required oninput="updateDates()" readonly class="bg-gray-100 cursor-not-allowed" title="Tahun lulus otomatis 3 tahun setelah masuk">
                                            </div>
                                            <input type="hidden" name="graduation_date" id="graduation_date">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 text-sm">
                                        <p id="duration_preview" class="hidden text-purple-600 font-bold"><i class="fas fa-clock mr-1"></i> Durasi Pendidikan: 3 Tahun</p>
                                        <p id="date_error" class="hidden text-red-500 font-bold"><i class="fas fa-exclamation-circle mr-1"></i> Bulan/Tanggal lulus tidak boleh lebih awal dari masuk!</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end mt-6">
                                    <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded mr-auto" onclick="resetStudentForm()">
                                        <i class="fas fa-undo mr-1"></i> Reset
                                    </button>
                                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2" onclick="toggleModal('addModal')">
                                        Batal
                                    </button>
                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded shadow-lg transform transition hover:scale-105">
                                        Simpan Data
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
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeResetPasswordModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-key text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Reset Password Siswa</h3>
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

    function fillDateFromPicker(picker, type) {
        if (picker.value) {
            const dateParts = picker.value.split('-'); // YYYY-MM-DD
            if (dateParts.length === 3) {
                document.getElementById(type + '_day').value = parseInt(dateParts[2]); // Remove leading zero
                document.getElementById(type + '_month').value = dateParts[1];
                document.getElementById(type + '_year').value = dateParts[0];
                
                if (type === 'entry') {
                    calculateGradYear();
                } else {
                    updateDates();
                }
            }
        }
    }

    function calculateGradYear() {
        const entryYear = document.getElementById('entry_year').value;
        const gradYearInput = document.getElementById('grad_year');
        
        if (entryYear && entryYear.length === 4) {
            const year = parseInt(entryYear);
            if (!isNaN(year)) {
                gradYearInput.value = year + 3;
                updateDates();
            }
        } else {
            gradYearInput.value = '';
        }
    }

    function updateDates() {
        const entryDay = document.getElementById('entry_day').value.padStart(2, '0');
        const entryMonth = document.getElementById('entry_month').value;
        const entryYear = document.getElementById('entry_year').value;
        
        const gradDay = document.getElementById('grad_day').value.padStart(2, '0');
        const gradMonth = document.getElementById('grad_month').value;
        const gradYear = document.getElementById('grad_year').value;

        const durationPreview = document.getElementById('duration_preview');
        const dateError = document.getElementById('date_error');
        
        // Reset hidden fields
        document.getElementById('entry_date').value = '';
        document.getElementById('graduation_date').value = '';
        
        // Hide messages initially
        if(durationPreview) durationPreview.classList.add('hidden');
        if(dateError) dateError.classList.add('hidden');

        if (entryDay && entryMonth && entryYear && entryYear.length === 4) {
             document.getElementById('entry_date').value = `${entryYear}-${entryMonth}-${entryDay}`;
        }

        if (gradDay && gradMonth && gradYear) {
             document.getElementById('graduation_date').value = `${gradYear}-${gradMonth}-${gradDay}`;
        }

        // Validate logic if all fields are filled
        if (entryDay && entryMonth && entryYear && gradDay && gradMonth && gradYear) {
            const entryDate = new Date(`${entryYear}-${entryMonth}-${entryDay}`);
            const gradDate = new Date(`${gradYear}-${gradMonth}-${gradDay}`);
            
            if (gradDate > entryDate) {
                if(durationPreview) durationPreview.classList.remove('hidden');
            } else {
                if(dateError) dateError.classList.remove('hidden');
            }
        }
    }

    function validateStudentForm() {
        const entryDate = document.getElementById('entry_date').value;
        const gradDate = document.getElementById('graduation_date').value;
        
        if (!entryDate || !gradDate) {
            alert('Mohon lengkapi tanggal masuk dan tanggal lulus dengan benar!');
            return false;
        }
        
        const entryObj = new Date(entryDate);
        const gradObj = new Date(gradDate);
        
        if (gradObj <= entryObj) {
            alert('Tanggal lulus harus lebih akhir dari tanggal masuk!');
            return false;
        }
        
        return true;
    }

    function resetStudentForm() {
        document.getElementById('addStudentForm').reset();
        const durationPreview = document.getElementById('duration_preview');
        const dateError = document.getElementById('date_error');
        if(durationPreview) durationPreview.classList.add('hidden');
        if(dateError) dateError.classList.add('hidden');
        document.getElementById('entry_date').value = '';
        document.getElementById('graduation_date').value = '';
    }

    function openResetPasswordModal(userId, username) {
        const modal = document.getElementById('resetPasswordModal');
        const form = document.getElementById('resetPasswordForm');
        const usernameSpan = document.getElementById('reset_username');
        
        // Set action URL
        form.action = "<?= BASEURL; ?>/admin/user_reset_password/" + userId + "/students";
        
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