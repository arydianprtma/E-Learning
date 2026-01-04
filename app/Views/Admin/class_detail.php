<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100 font-sans">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                
                <!-- Breadcrumb & Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                            <a href="<?= BASEURL; ?>/admin/classes" class="hover:text-indigo-600 transition-colors">Manajemen Kelas</a>
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span class="text-indigo-600 font-medium">Detail Kelas</span>
                        </div>
                        <h3 class="text-gray-800 text-3xl font-bold">
                            Kelas <?= $data['class']['name'] ?? 'Unknown'; ?>
                        </h3>
                        <p class="text-gray-500 mt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                <i class="fas fa-layer-group text-[10px]"></i> <?= $data['class']['level'] ?? '-'; ?>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                <i class="fas fa-graduation-cap text-[10px]"></i> <?= $data['class']['major'] ?? '-'; ?>
                            </span>
                        </p>
                    </div>
                    <a href="<?= BASEURL; ?>/admin/classes" class="group flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-full shadow-sm hover:bg-gray-50 text-gray-500 hover:text-indigo-600 transition-all duration-200">
                        <i class="fas fa-arrow-left group-hover:-translate-x-0.5 transition-transform"></i>
                    </a>
                </div>

                <!-- Info Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Total Students -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Siswa</p>
                            <h2 class="text-3xl font-bold text-gray-800 mt-1"><?= count($data['students_in_class']); ?></h2>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 text-xl">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <!-- Total Subjects -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Mata Pelajaran</p>
                            <h2 class="text-3xl font-bold text-gray-800 mt-1"><?= count($data['subjects_in_class']); ?></h2>
                        </div>
                        <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center text-teal-600 text-xl">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    
                    <!-- Students Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                            <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-user-graduate text-indigo-500"></i> Daftar Siswa
                            </h4>
                            <button onclick="openModal('addStudentModal')" class="text-sm bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded-lg font-medium transition-colors flex items-center gap-1">
                                <i class="fas fa-plus text-xs"></i> Tambah
                            </button>
                        </div>
                        <div class="overflow-x-auto flex-1">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <?php if(empty($data['students_in_class'])): ?>
                                        <tr>
                                            <td colspan="2" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                Belum ada siswa di kelas ini.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($data['students_in_class'] as $student): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold mr-3">
                                                        <?= substr($student['full_name'], 0, 1); ?>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900"><?= $student['full_name']; ?></div>
                                                        <div class="text-xs text-gray-500"><?= $student['nis']; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button onclick="removeStudent(<?= $student['id']; ?>)" class="text-red-400 hover:text-red-600 transition-colors p-1 rounded hover:bg-red-50" title="Hapus dari kelas">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Subjects Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                            <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-book-open text-teal-500"></i> Jadwal Mapel
                            </h4>
                            <button onclick="openModal('addSubjectModal')" class="text-sm bg-teal-50 text-teal-600 hover:bg-teal-100 px-3 py-1.5 rounded-lg font-medium transition-colors flex items-center gap-1">
                                <i class="fas fa-plus text-xs"></i> Tambah
                            </button>
                        </div>
                        <div class="overflow-x-auto flex-1">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Guru</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <?php if(empty($data['subjects_in_class'])): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                Belum ada jadwal mata pelajaran.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($data['subjects_in_class'] as $subject): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?= $subject['subject_name']; ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-6 w-6 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 text-xs mr-2">
                                                        <i class="fas fa-user-tie text-[10px]"></i>
                                                    </div>
                                                    <span class="text-sm text-gray-600"><?= $subject['teacher_name']; ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col gap-2">
                                                    <?php if(empty($subject['schedules'])): ?>
                                                        <span class="text-xs text-gray-400 italic">Belum ada jadwal</span>
                                                    <?php else: ?>
                                                        <?php foreach($subject['schedules'] as $sch): ?>
                                                            <div class="flex items-center gap-2 text-xs bg-gray-100 px-2 py-1 rounded">
                                                                <span class="font-medium text-gray-700 w-12"><?= $sch['day']; ?></span>
                                                                <span class="text-gray-500"><?= substr($sch['start_time'], 0, 5); ?> - <?= substr($sch['end_time'], 0, 5); ?></span>
                                                                <a href="<?= BASEURL; ?>/admin/class_delete_schedule/<?= $sch['id']; ?>" class="text-red-400 hover:text-red-600 ml-auto" onclick="return confirmAction(event, this.href, 'Hapus Jadwal?', 'Anda yakin ingin menghapus jadwal ini?')">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                    <button onclick="openScheduleModal(<?= $subject['id']; ?>, '<?= $subject['subject_name']; ?>')" class="text-xs text-indigo-600 hover:text-indigo-800 mt-1 flex items-center gap-1">
                                                        <i class="fas fa-plus"></i> Atur Jadwal
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button onclick="removeSubject(<?= $subject['id']; ?>)" class="text-red-400 hover:text-red-600 transition-colors p-1 rounded hover:bg-red-50" title="Hapus Mapel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Add Student Modal -->
<div id="addStudentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('addStudentModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-user-plus text-indigo-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Siswa ke Kelas</h3>
                        <div class="mt-2">
                            <form id="addStudentForm" action="<?= BASEURL; ?>/admin/class_add_student" method="POST" class="space-y-4">
                                <input type="hidden" name="class_id" value="<?= $data['class_id']; ?>">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="showRegistered" onchange="toggleRegisteredStudents()" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4 w-4">
                                            <label for="showRegistered" class="ml-2 text-xs text-gray-500 cursor-pointer">Tampilkan yang sudah terdaftar</label>
                                        </div>
                                    </div>
                                    <select name="student_id" id="studentSelect" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2.5">
                                        <option value="">-- Pilih Siswa --</option>
                                        <?php foreach($data['all_students'] as $student): ?>
                                            <option value="<?= $student['id']; ?>" 
                                                    data-registered="<?= $student['is_registered']; ?>"
                                                    class="<?= $student['is_registered'] ? 'text-gray-400 bg-gray-50' : ''; ?>"
                                                    <?= $student['is_registered'] ? 'disabled hidden' : ''; ?>>
                                                <?= $student['full_name']; ?> (<?= isset($student['identification_number']) ? $student['identification_number'] : 'N/A'; ?>)<?= $student['is_registered'] ? ' - Terdaftar' : ''; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Secara default, siswa yang sudah terdaftar di kelas ini disembunyikan.</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitForm('addStudentForm')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Tambahkan
                </button>
                <button type="button" onclick="closeModal('addStudentModal')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div id="addSubjectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('addSubjectModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-book-medical text-teal-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Jadwal Mapel</h3>
                        <div class="mt-2">
                            <form id="addSubjectForm" action="<?= BASEURL; ?>/admin/class_add_subject" method="POST" class="space-y-4">
                                <input type="hidden" name="class_id" value="<?= $data['class_id']; ?>">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                                    <select name="subject_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 border p-2.5">
                                        <option value="">-- Pilih Mapel --</option>
                                        <?php foreach($data['all_subjects'] as $subject): ?>
                                            <option value="<?= $subject['id']; ?>"><?= $subject['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Guru Pengampu</label>
                                    <select name="teacher_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 border p-2.5">
                                        <option value="">-- Pilih Guru --</option>
                                        <?php foreach($data['all_teachers'] as $teacher): ?>
                                            <option value="<?= $teacher['id']; ?>"><?= $teacher['full_name']; ?> (<?= isset($teacher['identification_number']) ? $teacher['identification_number'] : 'N/A'; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitForm('addSubjectForm')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Tambahkan
                </button>
                <button type="button" onclick="closeModal('addSubjectModal')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div id="addScheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('addScheduleModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-clock text-indigo-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Jadwal - <span id="scheduleSubjectName"></span></h3>
                        <div class="mt-2">
                            <form id="addScheduleForm" action="<?= BASEURL; ?>/admin/class_add_schedule" method="POST" class="space-y-4">
                                <input type="hidden" name="class_subject_id" id="schedule_class_subject_id">
                                <input type="hidden" name="class_id" value="<?= $data['class_id']; ?>">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                                    <select name="day" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2.5">
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Start Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                                        <div class="flex gap-2 items-center">
                                            <select id="start_hour" onchange="updateTime('start')" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2.5">
                                                <?php for($i=0; $i<=23; $i++): $h = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                                    <option value="<?= $h; ?>" <?= $i==7 ? 'selected' : '' ?>><?= $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <span class="font-bold text-gray-500">:</span>
                                            <select id="start_minute" onchange="updateTime('start')" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2.5">
                                                <?php for($i=0; $i<60; $i+=5): $m = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                                    <option value="<?= $m; ?>"><?= $m; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="start_time" id="start_time_input">
                                    </div>
                                    <!-- End Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                                        <div class="flex gap-2 items-center">
                                            <select id="end_hour" onchange="updateTime('end')" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2.5">
                                                <?php for($i=0; $i<=23; $i++): $h = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                                    <option value="<?= $h; ?>" <?= $i==8 ? 'selected' : '' ?>><?= $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <span class="font-bold text-gray-500">:</span>
                                            <select id="end_minute" onchange="updateTime('end')" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2.5">
                                                <?php for($i=0; $i<60; $i+=5): $m = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                                    <option value="<?= $m; ?>"><?= $m; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="end_time" id="end_time_input">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitForm('addScheduleForm')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Simpan
                </button>
                <button type="button" onclick="closeModal('addScheduleModal')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Time Selection Logic
function updateTime(type) {
    const hour = document.getElementById(type + '_hour').value;
    const minute = document.getElementById(type + '_minute').value;
    document.getElementById(type + '_time_input').value = hour + ':' + minute;
}

// Initialize times on load
document.addEventListener('DOMContentLoaded', function() {
    updateTime('start');
    updateTime('end');
    toggleRegisteredStudents(); // Initialize toggle state
});

function toggleRegisteredStudents() {
    const show = document.getElementById('showRegistered').checked;
    const select = document.getElementById('studentSelect');
    const options = select.options;
    
    for (let i = 0; i < options.length; i++) {
        if (options[i].getAttribute('data-registered') == '1') {
            if (show) {
                options[i].removeAttribute('hidden');
                options[i].removeAttribute('disabled'); 
            } else {
                options[i].setAttribute('hidden', 'hidden');
                options[i].setAttribute('disabled', 'disabled');
            }
        }
    }
}

// Open/Close Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openScheduleModal(classSubjectId, subjectName) {
    document.getElementById('schedule_class_subject_id').value = classSubjectId;
    document.getElementById('scheduleSubjectName').innerText = subjectName;
    openModal('addScheduleModal');
}


function submitForm(formId) {
    document.getElementById(formId).dispatchEvent(new Event('submit'));
}

function removeStudent(id) {
    Swal.fire({
        title: 'Hapus Siswa?',
        text: "Siswa akan dikeluarkan dari kelas ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Keluarkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
             window.location.href = '<?= BASEURL; ?>/admin/class_remove_student/' + id + '?class_id=<?= $data['class']['id']; ?>';
        }
    });
}

function removeSubject(id) {
     Swal.fire({
        title: 'Hapus Mapel?',
        text: "Mata pelajaran ini akan dihapus dari jadwal kelas.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
             window.location.href = '<?= BASEURL; ?>/admin/class_remove_subject/' + id + '?class_id=<?= $data['class']['id']; ?>';
        }
    });
}

// AJAX Handling
['addStudentForm', 'addSubjectForm', 'addScheduleForm'].forEach(formId => {
    const form = document.getElementById(formId);
    if(form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.closest('.inline-block').querySelector('button[onclick^="submitForm"]');
            const originalText = submitBtn.innerText;
            
            submitBtn.innerText = 'Memproses...';
            submitBtn.disabled = true;

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const contentType = response.headers.get('content-type');
                
                if (contentType && contentType.includes('application/json')) {
                    const result = await response.json();
                    if (!result.success) {
                        throw new Error(result.error.message || 'Terjadi kesalahan');
                    }
                } 
                
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                     window.location.reload();
                }

            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message || 'Terjadi kesalahan koneksi',
                    confirmButtonColor: '#ef4444'
                });
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        });
    }
});
</script>

<?php require_once '../app/Views/Layouts/footer.php'; ?>
