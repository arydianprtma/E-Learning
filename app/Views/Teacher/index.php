<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-50 font-sans">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <div class="container mx-auto px-6 py-8">
                
                <!-- Welcome Section with Gradient -->
                <div class="mb-8 bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl shadow-lg p-8 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div>
                            <h3 class="text-3xl font-bold mb-2">Dashboard Guru</h3>
                            <p class="text-indigo-100 text-lg">Selamat datang kembali, <?= $_SESSION['username']; ?>. Semangat mengajar hari ini!</p>
                        </div>
                        <div class="mt-4 md:mt-0 bg-white bg-opacity-20 rounded-lg p-3 backdrop-filter backdrop-blur-sm">
                            <span class="text-sm font-medium"><i class="far fa-calendar-alt mr-2"></i> <?= date('d F Y'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Stats / Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Today's Schedule Count -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 p-6 flex items-center justify-between group">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Jadwal Hari Ini</p>
                            <h2 class="text-4xl font-bold text-gray-800 mt-2"><?= count($data['today_schedule']); ?></h2>
                            <p class="text-sm text-gray-400 mt-1">Kelas aktif</p>
                        </div>
                        <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 text-2xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>

                    <!-- Total Classes -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 p-6 flex items-center justify-between group">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Kelas</p>
                            <h2 class="text-4xl font-bold text-gray-800 mt-2"><?= $data['total_classes'] ?? 0; ?></h2>
                            <p class="text-sm text-gray-400 mt-1">Diampu</p>
                        </div>
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>

                    <!-- Realized Hours This Week -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 p-6 flex items-center justify-between group">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Jam Mengajar</p>
                            <h2 class="text-4xl font-bold text-gray-800 mt-2"><?= $data['teaching_hours_this_week'] ?? 0; ?></h2>
                            <p class="text-sm text-gray-400 mt-1">Minggu ini</p>
                        </div>
                        <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Today's Schedule List (Left Column, spans 2) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full">
                            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                                <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <div class="w-2 h-6 bg-indigo-500 rounded-full"></div>
                                    Jadwal Mengajar Hari Ini
                                </h4>
                                <a href="<?= BASEURL; ?>/teacher/schedule" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua</a>
                            </div>
                            <div class="overflow-x-auto flex-1 p-2">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50 rounded-lg">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider rounded-l-lg">Jam</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider rounded-r-lg">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <?php if(empty($data['today_schedule'])): ?>
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                                    <div class="flex flex-col items-center">
                                                        <i class="far fa-calendar-times text-4xl mb-3 text-gray-300"></i>
                                                        <p class="text-sm">Tidak ada jadwal mengajar hari ini.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach($data['today_schedule'] as $sch): ?>
                                            <tr class="hover:bg-gray-50 transition-colors group">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center text-sm font-bold text-gray-700">
                                                        <i class="far fa-clock text-indigo-400 mr-2"></i>
                                                        <?= substr($sch['start_time'], 0, 5); ?> - <?= substr($sch['end_time'], 0, 5); ?>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        <?= $sch['class_name']; ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                                    <?= $sch['subject_name']; ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="<?= BASEURL; ?>/teacher/class_detail/<?= $sch['class_subject_id']; ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all transform hover:scale-105">
                                                        Masuk Kelas <i class="fas fa-chevron-right ml-1"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Latest Announcements (Right Column) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-full">
                            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <h4 class="text-lg font-bold text-gray-800">Pengumuman</h4>
                                <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full font-bold"><?= count($data['announcements'] ?? []); ?></span>
                            </div>
                            <div class="p-4 space-y-4 max-h-[500px] overflow-y-auto">
                                <?php if(isset($data['announcements']) && !empty($data['announcements'])): ?>
                                    <?php foreach($data['announcements'] as $announcement): ?>
                                    <div class="relative bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-all duration-300 group">
                                        <div class="absolute top-4 right-4 text-gray-300 group-hover:text-indigo-200 transition-colors">
                                            <i class="fas fa-bullhorn text-2xl transform -rotate-12"></i>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded uppercase tracking-wide">Info</span>
                                            <span class="text-xs text-gray-400"><?= date('d M', strtotime($announcement['created_at'])); ?></span>
                                        </div>
                                        <h5 class="text-sm font-bold text-gray-800 mb-1 pr-6"><?= htmlspecialchars($announcement['title']); ?></h5>
                                        <p class="text-xs text-gray-500 line-clamp-3 mb-2"><?= strip_tags($announcement['content']); ?></p>
                                        <div class="flex items-center text-xs text-gray-400 border-t border-gray-50 pt-2 mt-2">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($announcement['author_name']); ?>&color=7F9CF5&background=EBF4FF" class="w-4 h-4 rounded-full mr-2" alt="">
                                            <?= htmlspecialchars($announcement['author_name']); ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-12 text-gray-400">
                                        <i class="far fa-bell-slash text-4xl mb-3 text-gray-200"></i>
                                        <p class="text-sm">Belum ada pengumuman terbaru.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>