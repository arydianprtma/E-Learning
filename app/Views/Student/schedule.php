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
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Jadwal Pelajaran</h1>
                            <p class="text-gray-500 text-sm mt-1">
                                <?php if($data['class']): ?>
                                    Kelas <?= $data['class']['level']; ?> - <?= $data['class']['major']; ?> (<?= $data['class']['name']; ?>)
                                <?php else: ?>
                                    Belum ada kelas
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <?php if($data['class']): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php 
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        foreach($days as $day):
                            $daySchedule = array_filter($data['schedule'], function($s) use ($day) {
                                return $s['day'] == $day;
                            });
                        ?>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="font-bold text-gray-800"><?= $day; ?></h3>
                                    <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full font-medium">
                                        <?= count($daySchedule); ?> Mapel
                                    </span>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    <?php if(empty($daySchedule)): ?>
                                        <div class="p-4 text-center text-gray-500 text-sm italic">Libur</div>
                                    <?php else: ?>
                                        <?php foreach ($daySchedule as $sch): ?>
                                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="text-indigo-600 font-medium text-sm">
                                                        <?= substr($sch['start_time'], 0, 5); ?> - <?= substr($sch['end_time'], 0, 5); ?>
                                                    </span>
                                                </div>
                                                <h4 class="font-medium text-gray-900"><?= $sch['subject_name']; ?></h4>
                                                <div class="mt-1 text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-user-tie w-4 mr-1 text-gray-400"></i>
                                                    <?= $sch['teacher_name']; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Anda belum terdaftar dalam kelas manapun. Silakan hubungi Administrator.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>
