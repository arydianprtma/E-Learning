<?php require_once dirname(__DIR__) . '/Layouts/header.php'; ?>
<?php require_once dirname(__DIR__) . '/Layouts/sidebar.php'; ?>

<?php
// Parse dates if they exist (for Students)
$entryDay = ''; $entryMonth = ''; $entryYear = '';
$gradDay = ''; $gradMonth = ''; $gradYear = '';

if ($data['user']['role_id'] == 3 && isset($data['profile'])) {
    if (!empty($data['profile']['entry_date'])) {
        $entryParts = explode('-', $data['profile']['entry_date']);
        if (count($entryParts) == 3) {
            $entryYear = $entryParts[0];
            $entryMonth = $entryParts[1];
            $entryDay = $entryParts[2];
        }
    }
    if (!empty($data['profile']['graduation_date'])) {
        $gradParts = explode('-', $data['profile']['graduation_date']);
        if (count($gradParts) == 3) {
            $gradYear = $gradParts[0];
            $gradMonth = $gradParts[1];
            $gradDay = $gradParts[2];
        }
    }
}
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Profil Saya</h1>

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

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold mr-4">
                        <?= strtoupper(substr($data['user']['username'], 0, 1)); ?>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800"><?= $data['user']['username']; ?></h2>
                        <p class="text-gray-500"><?= $data['user']['email']; ?></p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            <?php 
                                switch($data['user']['role_id']) {
                                    case 1: echo 'Administrator'; break;
                                    case 2: echo 'Guru'; break;
                                    case 3: echo 'Siswa'; break;
                                    case 4: echo 'Orang Tua'; break;
                                    default: echo 'User';
                                }
                            ?>
                        </span>
                    </div>
                </div>

                <?php if ($data['user']['role_id'] == 3 && isset($data['profile'])): ?>
                <!-- Student Profile Edit Form -->
                <div class="border-t border-gray-200 pt-6">
                    <form action="<?= BASEURL; ?>/profile/update" method="POST">
                        
                        <!-- Account & Main Data -->
                         <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Akun & Utama</h3>
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label class="text-gray-700" for="full_name">Nama Lengkap</label>
                                    <input name="full_name" type="text" value="<?= $data['profile']['full_name']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="nis">NIS</label>
                                    <input name="nis" type="text" value="<?= $data['profile']['nis']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="nisn">NISN</label>
                                    <input name="nisn" type="text" value="<?= $data['profile']['nisn']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="username">Username</label>
                                    <input name="username" type="text" value="<?= $data['user']['username']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="email">Email</label>
                                    <input name="email" type="email" value="<?= $data['user']['email']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <!-- Status Akun hidden for student -->
                        </div>

                        <!-- Personal Data Section -->
                        <div class="border-t border-gray-200 pt-4 mt-6">
                                <h4 class="text-md font-bold text-gray-700 mb-3">Data Pribadi</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-gray-700" for="gender">Jenis Kelamin</label>
                                        <select name="gender" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" <?= (isset($data['profile']['gender']) && $data['profile']['gender'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                            <option value="P" <?= (isset($data['profile']['gender']) && $data['profile']['gender'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="place_of_birth">Tempat Lahir</label>
                                        <input name="place_of_birth" type="text" value="<?= isset($data['profile']['place_of_birth']) ? $data['profile']['place_of_birth'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="date_of_birth">Tanggal Lahir</label>
                                        <input name="date_of_birth" type="date" value="<?= isset($data['profile']['date_of_birth']) ? $data['profile']['date_of_birth'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="religion">Agama</label>
                                        <select name="religion" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Pilih Agama</option>
                                            <?php 
                                            $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
                                            foreach($religions as $rel) {
                                                $selected = (isset($data['profile']['religion']) && $data['profile']['religion'] == $rel) ? 'selected' : '';
                                                echo "<option value=\"$rel\" $selected>$rel</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="citizenship">Kewarganegaraan</label>
                                        <input name="citizenship" type="text" value="<?= isset($data['profile']['citizenship']) ? $data['profile']['citizenship'] : 'Indonesia'; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="phone">No HP</label>
                                        <input name="phone" type="text" value="<?= isset($data['profile']['phone']) ? $data['profile']['phone'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>

                        <!-- Address Data Section -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-md font-bold text-gray-700 mb-3">Data Alamat</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="col-span-1 sm:col-span-2">
                                        <label class="text-gray-700" for="address">Alamat Lengkap</label>
                                        <textarea name="address" rows="3" class="form-textarea w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?= isset($data['profile']['address']) ? $data['profile']['address'] : ''; ?></textarea>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="province">Provinsi</label>
                                        <input name="province" type="text" value="<?= isset($data['profile']['province']) ? $data['profile']['province'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Jawa Barat">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="city">Kabupaten/Kota</label>
                                        <input name="city" type="text" value="<?= isset($data['profile']['city']) ? $data['profile']['city'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Bandung">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="district">Kecamatan</label>
                                        <input name="district" type="text" value="<?= isset($data['profile']['district']) ? $data['profile']['district'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Cicendo">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="postal_code">Kode Pos</label>
                                        <input name="postal_code" type="text" value="<?= isset($data['profile']['postal_code']) ? $data['profile']['postal_code'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>

                        <!-- Parent Data Section -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-md font-bold text-gray-700 mb-3">Data Orang Tua / Wali</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-gray-700" for="parent_name">Nama Orang Tua / Wali <span class="text-red-500">*</span></label>
                                        <input name="parent_name" type="text" value="<?= isset($data['profile']['parent_name']) ? $data['profile']['parent_name'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="parent_relationship">Hubungan <span class="text-red-500">*</span></label>
                                        <select name="parent_relationship" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Pilih Hubungan</option>
                                            <option value="Ayah" <?= (isset($data['profile']['parent_relationship']) && $data['profile']['parent_relationship'] == 'Ayah') ? 'selected' : ''; ?>>Ayah</option>
                                            <option value="Ibu" <?= (isset($data['profile']['parent_relationship']) && $data['profile']['parent_relationship'] == 'Ibu') ? 'selected' : ''; ?>>Ibu</option>
                                            <option value="Wali" <?= (isset($data['profile']['parent_relationship']) && $data['profile']['parent_relationship'] == 'Wali') ? 'selected' : ''; ?>>Wali</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="parent_phone">No HP Orang Tua <span class="text-red-500">*</span></label>
                                        <input name="parent_phone" type="tel" value="<?= isset($data['profile']['parent_phone']) ? $data['profile']['parent_phone'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required pattern="[0-9]{10,13}" title="Nomor telepon harus berupa angka 10-13 digit">
                                    </div>
                                    <div class="col-span-1 sm:col-span-2">
                                        <label class="text-gray-700" for="parent_address">Alamat Orang Tua <span class="text-red-500">*</span></label>
                                        <textarea name="parent_address" rows="2" class="form-textarea w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Kosongkan jika sama dengan alamat siswa" required><?= isset($data['profile']['parent_address']) ? $data['profile']['parent_address'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                        <!-- Info Masuk & Lulus (Read Only for Student) -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h4 class="text-md font-bold text-gray-700 mb-3">Info Masuk & Lulus</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                                    <input type="text" value="<?= $data['profile']['entry_date']; ?>" class="form-input w-full rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lulus</label>
                                    <input type="text" value="<?= $data['profile']['graduation_date']; ?>" class="form-input w-full rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Password Change Section (Optional) -->
                         <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 mt-8">Ganti Password (Opsional)</h3>
                        <div class="mb-6">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password Baru</label>
                            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
                <?php elseif ($data['user']['role_id'] == 2 && isset($data['profile'])): ?>
                <!-- Teacher Profile Edit Form -->
                <div class="border-t border-gray-200 pt-6">
                    <form action="<?= BASEURL; ?>/profile/update" method="POST">
                        
                        <!-- Identity Data (Wajib) -->
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Identitas Guru (Wajib)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="text-gray-700" for="full_name">Nama Lengkap</label>
                                <input name="full_name" type="text" value="<?= $data['profile']['full_name']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label class="text-gray-700" for="nip">NIP</label>
                                <input name="nip" type="text" value="<?= $data['profile']['nip']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="text-gray-700" for="nuptk">NUPTK</label>
                                <input name="nuptk" type="text" value="<?= isset($data['profile']['nuptk']) ? $data['profile']['nuptk'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="text-gray-700" for="email">Email</label>
                                <input name="email" type="email" value="<?= $data['user']['email']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label class="text-gray-700" for="front_title">Gelar Depan</label>
                                <input name="front_title" type="text" value="<?= isset($data['profile']['front_title']) ? $data['profile']['front_title'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Dr.">
                            </div>
                            <div>
                                <label class="text-gray-700" for="back_title">Gelar Belakang</label>
                                <input name="back_title" type="text" value="<?= isset($data['profile']['back_title']) ? $data['profile']['back_title'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: S.Pd., M.Pd.">
                            </div>
                            <div>
                                <label class="text-gray-700" for="gender">Jenis Kelamin</label>
                                <select name="gender" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" <?= (isset($data['profile']['gender']) && $data['profile']['gender'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?= (isset($data['profile']['gender']) && $data['profile']['gender'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                             <div>
                                <label class="text-gray-700" for="place_of_birth">Tempat Lahir</label>
                                <input name="place_of_birth" type="text" value="<?= isset($data['profile']['place_of_birth']) ? $data['profile']['place_of_birth'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="text-gray-700" for="date_of_birth">Tanggal Lahir</label>
                                <input name="date_of_birth" type="date" value="<?= isset($data['profile']['date_of_birth']) ? $data['profile']['date_of_birth'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="text-gray-700" for="religion">Agama</label>
                                <select name="religion" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Agama</option>
                                    <?php 
                                    $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
                                    foreach($religions as $rel) {
                                        $selected = (isset($data['profile']['religion']) && $data['profile']['religion'] == $rel) ? 'selected' : '';
                                        echo "<option value=\"$rel\" $selected>$rel</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-gray-700" for="marital_status">Status Pernikahan</label>
                                <select name="marital_status" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Status</option>
                                    <?php 
                                    $statuses = ['Belum Menikah', 'Menikah', 'Janda/Duda'];
                                    foreach($statuses as $stat) {
                                        $selected = (isset($data['profile']['marital_status']) && $data['profile']['marital_status'] == $stat) ? 'selected' : '';
                                        echo "<option value=\"$stat\" $selected>$stat</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Employment Data (PENTING) - Mostly Read-Only -->
                        <div class="border-t border-gray-200 pt-4 mt-6">
                            <h4 class="text-md font-bold text-gray-700 mb-3">Data Kepegawaian (Info)</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-gray-700" for="employment_status">Status Kepegawaian</label>
                                    <input type="text" value="<?= isset($data['profile']['employment_status']) ? $data['profile']['employment_status'] : '-'; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="position">Jabatan</label>
                                    <input type="text" value="<?= isset($data['profile']['position']) ? $data['profile']['position'] : '-'; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="subjects">Mata Pelajaran</label>
                                    <input type="text" value="<?= isset($data['profile']['subjects']) ? $data['profile']['subjects'] : '-'; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="start_teaching_date">Mulai Mengajar</label>
                                    <input type="text" value="<?= isset($data['profile']['start_teaching_date']) ? $data['profile']['start_teaching_date'] : '-'; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="teaching_hours_per_week">Jam Mengajar / Minggu</label>
                                    <input type="text" value="<?= isset($data['profile']['teaching_hours_per_week']) ? $data['profile']['teaching_hours_per_week'] : '-'; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="status_detail">Status Aktif</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= (isset($data['profile']['status_detail']) && $data['profile']['status_detail'] == 'Aktif') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?> mt-2">
                                        <?= isset($data['profile']['status_detail']) ? $data['profile']['status_detail'] : 'Aktif'; ?>
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-2 italic">* Data kepegawaian hanya dapat diubah oleh Administrator.</p>
                        </div>

                        <!-- Academic & Professional Data -->
                        <div class="border-t border-gray-200 pt-4 mt-6">
                            <h4 class="text-md font-bold text-gray-700 mb-3">Data Akademik & Profesional</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-gray-700" for="last_education">Pendidikan Terakhir</label>
                                    <select name="last_education" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Pilih Pendidikan</option>
                                        <?php 
                                        $educations = ['D3', 'D4', 'S1', 'S2', 'S3'];
                                        foreach($educations as $edu) {
                                            $selected = (isset($data['profile']['last_education']) && $data['profile']['last_education'] == $edu) ? 'selected' : '';
                                            echo "<option value=\"$edu\" $selected>$edu</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="study_program">Program Studi</label>
                                    <input name="study_program" type="text" value="<?= isset($data['profile']['study_program']) ? $data['profile']['study_program'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="text-gray-700" for="university">Perguruan Tinggi</label>
                                    <input name="university" type="text" value="<?= isset($data['profile']['university']) ? $data['profile']['university'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="text-gray-700" for="graduation_year">Tahun Lulus</label>
                                    <input name="graduation_year" type="number" min="1950" max="<?= date('Y'); ?>" value="<?= isset($data['profile']['graduation_year']) ? $data['profile']['graduation_year'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="col-span-1 sm:col-span-2">
                                    <div class="flex items-center mt-2">
                                        <input id="is_certified" name="is_certified" type="checkbox" value="1" <?= (isset($data['profile']['is_certified']) && $data['profile']['is_certified']) ? 'checked' : ''; ?> class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_certified" class="ml-2 block text-sm text-gray-900">
                                            Sudah memiliki Sertifikasi Pendidik
                                        </label>
                                    </div>
                                </div>
                                <div class="col-span-1 sm:col-span-2" id="cert_number_container" style="<?= (isset($data['profile']['is_certified']) && $data['profile']['is_certified']) ? '' : 'display:none;'; ?>">
                                    <label class="text-gray-700" for="certificate_number">Nomor Sertifikat</label>
                                    <input name="certificate_number" type="text" value="<?= isset($data['profile']['certificate_number']) ? $data['profile']['certificate_number'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>

                        <!-- Password Change Section (Optional) -->
                         <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 mt-8">Ganti Password (Opsional)</h3>
                        <div class="mb-6">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password Baru</label>
                            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Perubahan</button>
                        </div>
                    </form>
                    
                    <script>
                        document.getElementById('is_certified').addEventListener('change', function() {
                            const container = document.getElementById('cert_number_container');
                            if(this.checked) {
                                container.style.display = 'block';
                            } else {
                                container.style.display = 'none';
                            }
                        });
                    </script>
                </div>
                <?php else: ?>
                <!-- Default View for Non-Student Users (e.g. Admin, Teacher) -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ganti Password</h3>
                    <form action="<?= BASEURL; ?>/profile/update" method="POST" class="max-w-md">
                        <div class="mb-4">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password Baru</label>
                            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Password</button>
                    </form>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?php require_once dirname(__DIR__) . '/Layouts/footer.php'; ?>
