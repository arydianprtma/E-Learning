<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100 font-sans">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Jadwal Mengajar Mingguan</h1>
                        <p class="text-gray-600">Jadwal lengkap pelajaran Anda minggu ini</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    foreach ($days as $day):
                        $daySchedule = array_filter($data['schedule'], function($s) use ($day) {
                            return $s['day'] == $day;
                        });
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-semibold text-gray-800"><?= $day; ?></h3>
                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full font-medium">
                                <?= count($daySchedule); ?> Mapel
                            </span>
                        </div>
                        
                        <?php if (empty($daySchedule)): ?>
                            <div class="p-6 text-center text-gray-500 text-sm">
                                Tidak ada jadwal mengajar.
                            </div>
                        <?php else: ?>
                            <div class="divide-y divide-gray-100">
                                <?php foreach ($daySchedule as $sch): ?>
                                    <div class="p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-indigo-600 font-medium text-sm">
                                                <?= substr($sch['start_time'], 0, 5); ?> - <?= substr($sch['end_time'], 0, 5); ?>
                                            </span>
                                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded">
                                                <?= $sch['class_name']; ?>
                                            </span>
                                        </div>
                                        <h4 class="font-medium text-gray-900"><?= $sch['subject_name']; ?></h4>
                                        <div class="mt-3 text-right">
                                            <a href="<?= BASEURL; ?>/teacher/class_detail/<?= $sch['class_subject_id']; ?>" 
                                               class="inline-flex items-center text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                                Masuk Kelas <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>
