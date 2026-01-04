<?php require_once '../app/Views/Layouts/header.php'; ?>

<?php
// Parse dates if they exist
$entryDay = ''; $entryMonth = ''; $entryYear = '';
if (!empty($data['student']['entry_date'])) {
    $entryParts = explode('-', $data['student']['entry_date']);
    if (count($entryParts) == 3) {
        $entryYear = $entryParts[0];
        $entryMonth = $entryParts[1];
        $entryDay = $entryParts[2];
    }
}

$gradDay = ''; $gradMonth = ''; $gradYear = '';
if (!empty($data['student']['graduation_date'])) {
    $gradParts = explode('-', $data['student']['graduation_date']);
    if (count($gradParts) == 3) {
        $gradYear = $gradParts[0];
        $gradMonth = $gradParts[1];
        $gradDay = $gradParts[2];
    }
}
?>

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-6 py-8">
                <h3 class="text-gray-700 text-3xl font-medium">Edit Data Siswa</h3>
                
                <div class="mt-8">
                    <div class="p-6 bg-white rounded-md shadow-md">
                        <h2 class="text-lg text-gray-700 font-semibold capitalize mb-4">Form Edit Siswa</h2>
                        <form id="editForm" action="<?= BASEURL; ?>/admin/students_update" method="POST">
                            <input type="hidden" name="id" value="<?= $data['student']['id']; ?>">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label class="text-gray-700" for="full_name">Nama Lengkap</label>
                                    <input name="full_name" type="text" value="<?= $data['student']['full_name']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="nis">NIS</label>
                                    <input name="nis" type="text" value="<?= $data['student']['nis']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="nisn">NISN</label>
                                    <input name="nisn" type="text" value="<?= $data['student']['nisn']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="username">Username</label>
                                    <input name="username" type="text" value="<?= $data['student']['username']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="email">Email</label>
                                    <input name="email" type="email" value="<?= $data['student']['email']; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="text-gray-700" for="is_active">Status Akun</label>
                                    <select name="is_active" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="1" <?= $data['student']['is_active'] ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="0" <?= !$data['student']['is_active'] ? 'selected' : ''; ?>>Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Personal Data Section -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-md font-bold text-gray-700 mb-3">Data Pribadi</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-gray-700" for="gender">Jenis Kelamin</label>
                                        <select name="gender" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" <?= (isset($data['student']['gender']) && $data['student']['gender'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                            <option value="P" <?= (isset($data['student']['gender']) && $data['student']['gender'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="place_of_birth">Tempat Lahir</label>
                                        <input name="place_of_birth" type="text" value="<?= isset($data['student']['place_of_birth']) ? $data['student']['place_of_birth'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="date_of_birth">Tanggal Lahir</label>
                                        <input name="date_of_birth" type="date" value="<?= isset($data['student']['date_of_birth']) ? $data['student']['date_of_birth'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="religion">Agama</label>
                                        <select name="religion" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Pilih Agama</option>
                                            <?php 
                                            $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
                                            foreach($religions as $rel) {
                                                $selected = (isset($data['student']['religion']) && $data['student']['religion'] == $rel) ? 'selected' : '';
                                                echo "<option value=\"$rel\" $selected>$rel</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="citizenship">Kewarganegaraan</label>
                                        <input name="citizenship" type="text" value="<?= isset($data['student']['citizenship']) ? $data['student']['citizenship'] : 'Indonesia'; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="phone">No HP</label>
                                        <input name="phone" type="text" value="<?= isset($data['student']['phone']) ? $data['student']['phone'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>

                            <!-- Address Data Section -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-md font-bold text-gray-700 mb-3">Data Alamat</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="col-span-1 sm:col-span-2">
                                        <label class="text-gray-700" for="address">Alamat Lengkap</label>
                                        <textarea name="address" rows="3" class="form-textarea w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?= isset($data['student']['address']) ? $data['student']['address'] : ''; ?></textarea>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="province">Provinsi</label>
                                        <input name="province" type="text" value="<?= isset($data['student']['province']) ? $data['student']['province'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Jawa Barat">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="city">Kabupaten/Kota</label>
                                        <input name="city" type="text" value="<?= isset($data['student']['city']) ? $data['student']['city'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Bandung">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="district">Kecamatan</label>
                                        <input name="district" type="text" value="<?= isset($data['student']['district']) ? $data['student']['district'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Contoh: Cicendo">
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="postal_code">Kode Pos</label>
                                        <input name="postal_code" type="text" value="<?= isset($data['student']['postal_code']) ? $data['student']['postal_code'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>

                            <!-- Parent Data Section -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-md font-bold text-gray-700 mb-3">Data Orang Tua / Wali</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-gray-700" for="parent_name">Nama Orang Tua / Wali <span class="text-red-500">*</span></label>
                                        <input name="parent_name" type="text" value="<?= isset($data['student']['parent_name']) ? $data['student']['parent_name'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="parent_relationship">Hubungan <span class="text-red-500">*</span></label>
                                        <select name="parent_relationship" class="form-select w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Pilih Hubungan</option>
                                            <option value="Ayah" <?= (isset($data['student']['parent_relationship']) && $data['student']['parent_relationship'] == 'Ayah') ? 'selected' : ''; ?>>Ayah</option>
                                            <option value="Ibu" <?= (isset($data['student']['parent_relationship']) && $data['student']['parent_relationship'] == 'Ibu') ? 'selected' : ''; ?>>Ibu</option>
                                            <option value="Wali" <?= (isset($data['student']['parent_relationship']) && $data['student']['parent_relationship'] == 'Wali') ? 'selected' : ''; ?>>Wali</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-700" for="parent_phone">No HP Orang Tua <span class="text-red-500">*</span></label>
                                        <input name="parent_phone" type="tel" value="<?= isset($data['student']['parent_phone']) ? $data['student']['parent_phone'] : ''; ?>" class="form-input w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required pattern="[0-9]{10,13}" title="Nomor telepon harus berupa angka 10-13 digit">
                                    </div>
                                    <div class="col-span-1 sm:col-span-2">
                                        <label class="text-gray-700" for="parent_address">Alamat Orang Tua <span class="text-red-500">*</span></label>
                                        <textarea name="parent_address" rows="2" class="form-textarea w-full mt-2 rounded-md border-gray-300 border p-2 focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Kosongkan jika sama dengan alamat siswa" required><?= isset($data['student']['parent_address']) ? $data['student']['parent_address'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-md font-bold text-gray-700 mb-3">Info Masuk & Lulus</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Tanggal Masuk -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                                        <div class="flex space-x-2">
                                            <input type="number" id="entry_day" value="<?= $entryDay; ?>" class="shadow border rounded w-16 py-2 px-2 text-center" placeholder="DD" min="1" max="31" required oninput="updateDates()">
                                            <select id="entry_month" class="shadow border rounded flex-1 py-2 px-2" required onchange="updateDates()">
                                                <option value="">Bulan</option>
                                                <option value="01" <?= $entryMonth == '01' ? 'selected' : ''; ?>>Januari</option>
                                                <option value="02" <?= $entryMonth == '02' ? 'selected' : ''; ?>>Februari</option>
                                                <option value="03" <?= $entryMonth == '03' ? 'selected' : ''; ?>>Maret</option>
                                                <option value="04" <?= $entryMonth == '04' ? 'selected' : ''; ?>>April</option>
                                                <option value="05" <?= $entryMonth == '05' ? 'selected' : ''; ?>>Mei</option>
                                                <option value="06" <?= $entryMonth == '06' ? 'selected' : ''; ?>>Juni</option>
                                                <option value="07" <?= $entryMonth == '07' ? 'selected' : ''; ?>>Juli</option>
                                                <option value="08" <?= $entryMonth == '08' ? 'selected' : ''; ?>>Agustus</option>
                                                <option value="09" <?= $entryMonth == '09' ? 'selected' : ''; ?>>September</option>
                                                <option value="10" <?= $entryMonth == '10' ? 'selected' : ''; ?>>Oktober</option>
                                                <option value="11" <?= $entryMonth == '11' ? 'selected' : ''; ?>>November</option>
                                                <option value="12" <?= $entryMonth == '12' ? 'selected' : ''; ?>>Desember</option>
                                            </select>
                                            <input type="number" id="entry_year" value="<?= $entryYear; ?>" class="shadow border rounded w-20 py-2 px-2 text-center" placeholder="YYYY" min="2000" max="2100" required oninput="calculateGradYear()">
                                            
                                            <!-- Date Picker Helper -->
                                            <div class="relative">
                                                <input type="date" id="entry_picker" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" onchange="fillDateFromPicker(this, 'entry')">
                                                <button type="button" class="shadow border rounded border-gray-200 bg-gray-50 text-gray-500 hover:bg-white hover:text-indigo-600 hover:border-indigo-300 transition-all px-3 py-2 flex items-center justify-center" title="Pilih Tanggal dari Kalender">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="entry_date" id="entry_date" value="<?= $data['student']['entry_date']; ?>">
                                    </div>

                                    <!-- Tanggal Lulus -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lulus (Estimasi)</label>
                                        <div class="flex space-x-2">
                                            <input type="number" id="grad_day" value="<?= $gradDay; ?>" class="shadow border rounded w-16 py-2 px-2 text-center" placeholder="DD" min="1" max="31" required oninput="updateDates()">
                                            <select id="grad_month" class="shadow border rounded flex-1 py-2 px-2" required onchange="updateDates()">
                                                <option value="">Bulan</option>
                                                <option value="01" <?= $gradMonth == '01' ? 'selected' : ''; ?>>Januari</option>
                                                <option value="02" <?= $gradMonth == '02' ? 'selected' : ''; ?>>Februari</option>
                                                <option value="03" <?= $gradMonth == '03' ? 'selected' : ''; ?>>Maret</option>
                                                <option value="04" <?= $gradMonth == '04' ? 'selected' : ''; ?>>April</option>
                                                <option value="05" <?= $gradMonth == '05' ? 'selected' : ''; ?>>Mei</option>
                                                <option value="06" <?= $gradMonth == '06' ? 'selected' : ''; ?>>Juni</option>
                                                <option value="07" <?= $gradMonth == '07' ? 'selected' : ''; ?>>Juli</option>
                                                <option value="08" <?= $gradMonth == '08' ? 'selected' : ''; ?>>Agustus</option>
                                                <option value="09" <?= $gradMonth == '09' ? 'selected' : ''; ?>>September</option>
                                                <option value="10" <?= $gradMonth == '10' ? 'selected' : ''; ?>>Oktober</option>
                                                <option value="11" <?= $gradMonth == '11' ? 'selected' : ''; ?>>November</option>
                                                <option value="12" <?= $gradMonth == '12' ? 'selected' : ''; ?>>Desember</option>
                                            </select>
                                            <input type="number" id="grad_year" value="<?= $gradYear; ?>" class="shadow border rounded w-20 py-2 px-2 text-center" placeholder="YYYY" min="2000" max="2100" required oninput="updateDates()" readonly class="bg-gray-100 cursor-not-allowed" title="Tahun lulus otomatis 3 tahun setelah masuk">
                                        </div>
                                        <input type="hidden" name="graduation_date" id="graduation_date" value="<?= $data['student']['graduation_date']; ?>">
                                    </div>
                                </div>
                                
                                <div class="mt-2 text-sm">
                                    <p id="duration_preview" class="hidden text-purple-600 font-bold"><i class="fas fa-clock mr-1"></i> Durasi Pendidikan: 3 Tahun</p>
                                    <p id="date_error" class="hidden text-red-500 font-bold"><i class="fas fa-exclamation-circle mr-1"></i> Bulan/Tanggal lulus tidak boleh lebih awal dari masuk!</p>
                                </div>
                            </div>
                            <div class="flex justify-end mt-4 space-x-2">
                                <button type="button" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:bg-yellow-600 flex items-center" onclick="resetEditForm()">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                                <a href="<?= BASEURL; ?>/admin/students" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:bg-gray-400">Batal</a>
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

    function resetEditForm() {
        document.getElementById('editForm').reset();
        // Wait for reset to complete then update dates
        setTimeout(updateDates, 0);
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

    // Call updateDates on load to set initial state
    document.addEventListener('DOMContentLoaded', function() {
        updateDates();
    });

document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Validation
    const entryDate = document.getElementById('entry_date').value;
    const gradDate = document.getElementById('graduation_date').value;
    
    if (entryDate && gradDate) {
        const entryObj = new Date(entryDate);
        const gradObj = new Date(gradDate);
        if (gradObj <= entryObj) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Tanggal lulus harus lebih akhir dari tanggal masuk!',
                confirmButtonColor: '#ef4444'
            });
            return;
        }
    }

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
                window.location.href = '<?= BASEURL; ?>/admin/students?success=Data siswa berhasil diupdate';
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
