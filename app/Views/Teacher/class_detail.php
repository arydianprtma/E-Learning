<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100 font-sans">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 py-8">
                <!-- Header -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-0.5 rounded font-medium">
                                    <?= $data['class_subject']['level']; ?> - <?= $data['class_subject']['major']; ?>
                                </span>
                                <span class="text-gray-400">|</span>
                                <span class="text-gray-600 text-sm"><?= $data['class_subject']['class_name']; ?></span>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900"><?= $data['class_subject']['subject_name']; ?></h1>
                        </div>
                        <a href="<?= BASEURL; ?>/teacher/classes" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kelas
                        </a>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="mb-6 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button onclick="switchTab('attendance')" id="tab-attendance" class="tab-btn border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-user-check mr-2"></i> Absensi
                        </button>
                        <button onclick="switchTab('grades')" id="tab-grades" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-star mr-2"></i> Nilai
                        </button>
                        <button onclick="switchTab('students')" id="tab-students" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-users mr-2"></i> Data Siswa
                        </button>
                    </nav>
                </div>

                <!-- Attendance Content -->
                <div id="content-attendance" class="tab-content block">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                            <h2 class="text-lg font-bold text-gray-900">Input Absensi</h2>
                            <form action="" method="GET" class="flex items-center gap-2">
                                <label for="date" class="text-sm font-medium text-gray-700">Tanggal:</label>
                                <input type="date" name="date" value="<?= $data['date']; ?>" onchange="this.form.submit()" 
                                       class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </form>
                        </div>
                        
                        <!-- Status Alert -->
                        <div class="px-6 pt-6">
                            <div class="p-4 rounded-md <?= $data['attendance_status'] == 'open' ? 'bg-green-50 text-green-700' : ($data['attendance_status'] == 'open_late' ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700'); ?>">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <?php if($data['attendance_status'] == 'open'): ?>
                                            <i class="fas fa-check-circle text-green-400"></i>
                                        <?php elseif($data['attendance_status'] == 'open_late'): ?>
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        <?php else: ?>
                                            <i class="fas fa-lock text-red-400"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-3 w-full">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-sm font-medium">
                                                <?= $data['status_message']; ?>
                                            </h3>
                                            
                                            <!-- Countdown Timer (Only for 'closed' status and within 15 mins) -->
                                            <?php 
                                            $showCountdown = false;
                                            $secondsRemaining = 0;
                                            if ($data['attendance_status'] == 'closed' && !empty($data['schedule_today']) && $data['date'] == date('Y-m-d')) {
                                                $now = time();
                                                $startTime = strtotime(date('Y-m-d') . ' ' . $data['schedule_today']['start_time']);
                                                $diff = $startTime - $now;
                                                
                                                // Show if within 15 minutes (900 seconds) and not yet started
                                                if ($diff > 0 && $diff <= 900) {
                                                    $showCountdown = true;
                                                    $secondsRemaining = $diff;
                                                }
                                            }
                                            ?>
                                            
                                            <?php if($showCountdown): ?>
                                            <div id="countdown-container" class="flex items-center gap-2 text-sm font-bold animate-pulse">
                                                <i class="fas fa-stopwatch"></i>
                                                <span>Dibuka dalam: </span>
                                                <span id="countdown-timer">--:--</span>
                                            </div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    let timeLeft = <?= $secondsRemaining; ?>;
                                                    const timerDisplay = document.getElementById('countdown-timer');
                                                    const container = document.getElementById('countdown-container');
                                                    
                                                    const countdown = setInterval(() => {
                                                        if (timeLeft <= 0) {
                                                            clearInterval(countdown);
                                                            timerDisplay.textContent = "Sekarang!";
                                                            // Optional: reload page to open attendance
                                                            setTimeout(() => location.reload(), 1000);
                                                        } else {
                                                            const minutes = Math.floor(timeLeft / 60);
                                                            const seconds = timeLeft % 60;
                                                            timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                                                            timeLeft--;
                                                        }
                                                    }, 1000);
                                                });
                                            </script>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if($data['schedule_today']): ?>
                                            <div class="mt-1 text-sm">
                                                Jadwal Hari Ini: <?= substr($data['schedule_today']['start_time'], 0, 5); ?> - <?= substr($data['schedule_today']['end_time'], 0, 5); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="<?= BASEURL; ?>/teacher/input_attendance" method="POST" id="attendance-form" onsubmit="return confirmAttendance(event)">
                            <input type="hidden" name="class_subject_id" value="<?= $data['class_subject']['id']; ?>">
                            <input type="hidden" name="date" value="<?= $data['date']; ?>">
                            <!-- NEW: Pass start and end time of the selected schedule -->
                            <?php if($data['schedule_today']): ?>
                            <input type="hidden" name="start_time" value="<?= $data['schedule_today']['start_time']; ?>">
                            <input type="hidden" name="end_time" value="<?= $data['schedule_today']['end_time']; ?>">
                            <?php endif; ?>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach($data['students'] as $student): ?>
                                            <?php 
                                                $student_id = $student['student_id'];
                                                $status = $data['attendance_map'][$student_id]['status'] ?? 'Hadir';
                                                $note = $data['attendance_map'][$student_id]['note'] ?? '';
                                                $disabled = ($data['attendance_status'] == 'closed') ? 'disabled' : '';
                                            ?>
                                            <input type="hidden" name="students[]" value="<?= $student_id; ?>">
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $student['nis']; ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $student['full_name']; ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <div class="flex justify-center space-x-4">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="status[<?= $student_id; ?>]" value="Hadir" <?= $status == 'Hadir' ? 'checked' : ''; ?> <?= $disabled; ?> class="text-green-600 focus:ring-green-500">
                                                            <span class="ml-2 text-sm text-gray-700">Hadir</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="status[<?= $student_id; ?>]" value="Izin" <?= $status == 'Izin' ? 'checked' : ''; ?> <?= $disabled; ?> class="text-blue-600 focus:ring-blue-500">
                                                            <span class="ml-2 text-sm text-gray-700">Izin</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="status[<?= $student_id; ?>]" value="Sakit" <?= $status == 'Sakit' ? 'checked' : ''; ?> <?= $disabled; ?> class="text-yellow-600 focus:ring-yellow-500">
                                                            <span class="ml-2 text-sm text-gray-700">Sakit</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="status[<?= $student_id; ?>]" value="Alpa" <?= $status == 'Alpa' ? 'checked' : ''; ?> <?= $disabled; ?> class="text-red-600 focus:ring-red-500">
                                                            <span class="ml-2 text-sm text-gray-700">Alpa</span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <input type="text" name="note[<?= $student_id; ?>]" value="<?= $note; ?>" <?= $disabled; ?> class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Keterangan...">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if($data['attendance_status'] != 'closed'): ?>
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-save mr-2 mt-0.5"></i> Simpan Absensi
                                </button>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- Grades Content -->
                <div id="content-grades" class="tab-content hidden">
                    <style>
                        /* Hide spin buttons for number inputs */
                        input[type=number]::-webkit-inner-spin-button, 
                        input[type=number]::-webkit-outer-spin-button { 
                            -webkit-appearance: none; 
                            margin: 0; 
                        }
                        input[type=number] {
                            -moz-appearance: textfield;
                            appearance: textfield;
                        }
                    </style>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-lg font-bold text-gray-900">Data Nilai</h2>
                                <button type="submit" form="bulkGradeForm" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    <i class="fas fa-save mr-2"></i> Simpan Data
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 flex items-center bg-blue-50 p-2 rounded-md border border-blue-100">
                                <i class="fas fa-info-circle mr-2 text-indigo-500"></i>
                                <span>Catatan: Masukkan nilai langsung pada kolom input berwarna <strong>abu-abu</strong>, lalu klik tombol <strong>"Simpan Data"</strong>.</span>
                            </p>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <form action="<?= BASEURL; ?>/teacher/input_grades" method="POST" id="grades-form" onsubmit="return confirmGrades(event)">
                            <input type="hidden" name="class_subject_id" value="<?= $data['class_subject']['id']; ?>">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kuis</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Praktek</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if(empty($data['students'])): ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                Belum ada data siswa.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no = 1; foreach($data['students'] as $student): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= $no++; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?= $student['full_name']; ?></div>
                                                <div class="text-xs text-gray-500"><?= $student['nis']; ?></div>
                                            </td>
                                            <?php 
                                            $types = ['Tugas', 'Kuis', 'UTS', 'UAS', 'Praktek'];
                                            $totalScore = 0;
                                            $filledCount = 0;
                                            foreach($types as $type):
                                                $studentGrades = $data['student_grades'][$student['student_id']][$type] ?? [];
                                                $currentScore = !empty($studentGrades) ? $studentGrades[0]['score'] : '';
                                                
                                                if ($currentScore !== '') {
                                                    $totalScore += floatval($currentScore);
                                                    $filledCount++;
                                                }
                                            ?>
                                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                                <input type="number" 
                                                       name="grades[<?= $student['student_id']; ?>][<?= $type; ?>]" 
                                                       value="<?= $currentScore; ?>" 
                                                       class="bg-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-20 sm:text-sm border-gray-300 rounded-md text-center mx-auto"
                                                       min="0" max="100" placeholder="-">
                                            </td>
                                            <?php endforeach; ?>
                                            
                                            <!-- Average Column -->
                                            <?php 
                                            $average = $filledCount > 0 ? round($totalScore / $filledCount, 1) : 0;
                                            ?>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                                <?= $filledCount > 0 ? $average : '-'; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Students Content -->
                <div id="content-students" class="tab-content hidden">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-bold text-gray-900">Daftar Siswa</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alpa</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach($data['students'] as $student): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $student['nis']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $student['full_name']; ?></td>
                                        
                                        <!-- Attendance Stats -->
                                        <?php 
                                        $stats = $data['attendance_summary'][$student['student_id']] ?? ['total_hadir'=>0, 'total_sakit'=>0, 'total_izin'=>0, 'total_alpa'=>0]; 
                                        ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-bold">
                                            <?= $stats['total_hadir']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-yellow-600 font-bold">
                                            <?= $stats['total_sakit']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-blue-600 font-bold">
                                            <?= $stats['total_izin']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 font-bold">
                                            <?= $stats['total_alpa']; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Grade Modal -->
            <div id="addGradeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">Input Nilai</h3>
                        <form id="gradeForm" action="<?= BASEURL; ?>/teacher/input_grade" method="POST">
                            <input type="hidden" name="class_subject_id" value="<?= $data['class_subject']['id']; ?>">
                            <input type="hidden" name="grade_id" id="grade_id">
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="student_id">Siswa</label>
                                <select name="student_id" id="student_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Pilih Siswa</option>
                                    <?php foreach($data['students'] as $student): ?>
                                        <option value="<?= $student['student_id']; ?>"><?= $student['full_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Jenis Penilaian</label>
                                <select name="type" id="type" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="Tugas">Tugas</option>
                                    <option value="Kuis">Kuis</option>
                                    <option value="UTS">UTS</option>
                                    <option value="UAS">UAS</option>
                                    <option value="Praktek">Praktek</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="score">Nilai (0-100)</label>
                                <input type="number" name="score" id="score" min="0" max="100" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Keterangan (Opsional)</label>
                                <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="button" onclick="closeModal('addGradeModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2 focus:outline-none focus:shadow-outline">
                                    Batal
                                </button>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
        // SweetAlert for Flash Messages (Success/Error)
        <?php if(isset($_GET['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= urldecode($_GET['success']); ?>',
                confirmButtonColor: '#4f46e5',
                background: '#ffffff',
                iconColor: '#10b981',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(() => {
                const url = new URL(window.location.href);
                url.searchParams.delete('success');
                window.history.replaceState({}, '', url);
            });
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= urldecode($_GET['error']); ?>',
                confirmButtonColor: '#ef4444',
                background: '#ffffff',
                iconColor: '#ef4444',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(() => {
                const url = new URL(window.location.href);
                url.searchParams.delete('error');
                window.history.replaceState({}, '', url);
            });
        <?php endif; ?>

        // Confirm Attendance Submission
        function confirmAttendance(event) {
            event.preventDefault(); // Stop default form submission
            
            const isLate = <?= json_encode($data['is_late']); ?>;
            let title = 'Konfirmasi Absensi';
            let text = "Apakah Anda yakin ingin menyimpan data absensi ini?";
            let icon = 'question';
            let confirmButtonColor = '#4f46e5';

            if (isLate) {
                title = 'Absensi Terlambat';
                text = "Anda mengisi absensi di luar jam pelajaran (Masa Toleransi). Lanjutkan penyimpanan?";
                icon = 'warning';
                confirmButtonColor = '#d97706'; // Amber-600
            }
            
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    document.getElementById('attendance-form').submit();
                }
            });
            
            return false;
        }

        // Confirm Grade Submission
        function confirmGrades(event) {
            event.preventDefault(); // Stop default form submission
            
            Swal.fire({
                title: 'Simpan Nilai?',
                text: "Pastikan data nilai sudah benar sebelum disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Simpan Nilai',
                cancelButtonText: 'Periksa Lagi',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('grades-form').submit();
                }
            });
            
            return false;
        }

        function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));
        
        // Reset buttons
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-indigo-500', 'text-indigo-600');
            el.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show active tab
        document.getElementById('content-' + tabName).classList.remove('hidden');
        document.getElementById('content-' + tabName).classList.add('block');
        
        // Highlight button
        document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-' + tabName).classList.add('border-indigo-500', 'text-indigo-600');
        
        // Update URL param without reload
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
    }

    // Initialize tab from URL
    const pageUrlParams = new URLSearchParams(window.location.search);
    const currentTab = pageUrlParams.get('tab') || 'attendance';
    switchTab(currentTab);

    function openModal(modalID) {
        document.getElementById(modalID).classList.remove('hidden');
        document.getElementById('gradeForm').reset();
        document.getElementById('grade_id').value = '';
        document.getElementById('modalTitle').textContent = 'Input Nilai';
    }

    function closeModal(modalID) {
        document.getElementById(modalID).classList.add('hidden');
    }

    function editGrade(grade) {
        openModal('addGradeModal');
        document.getElementById('modalTitle').textContent = 'Edit Nilai';
        document.getElementById('grade_id').value = grade.id;
        document.getElementById('student_id').value = grade.student_id;
        document.getElementById('type').value = grade.type;
        document.getElementById('score').value = grade.score;
        document.getElementById('description').value = grade.description;
    }
    
    function confirmDeleteGrade(id) {
        if(confirm('Apakah Anda yakin ingin menghapus nilai ini?')) {
            window.location.href = '<?= BASEURL; ?>/teacher/delete_grade/' + id;
        }
    }

    // Close modal on outside click
    window.onclick = function(event) {
        const modal = document.getElementById('addGradeModal');
        if (event.target == modal) {
            closeModal('addGradeModal');
        }
    }
</script>

<?php require_once '../app/Views/Layouts/footer.php'; ?>