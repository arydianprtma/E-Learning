<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100 font-sans">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Welcome Card -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 text-white col-span-1 md:col-span-2">
                        <h2 class="text-2xl font-bold mb-2">Selamat Datang, <?= $data['username']; ?>!</h2>
                        <p class="text-indigo-100 mb-4">Semangat belajar dan raih prestasimu hari ini.</p>
                        
                        <?php if($data['class']): ?>
                            <div class="inline-flex items-center bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                                <i class="fas fa-chalkboard mr-2"></i>
                                <span class="font-medium">Kelas <?= $data['class']['level']; ?> - <?= $data['class']['major']; ?> (<?= $data['class']['name']; ?>)</span>
                            </div>
                        <?php else: ?>
                            <div class="inline-flex items-center bg-red-400/20 backdrop-blur-sm px-4 py-2 rounded-lg text-red-100">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span class="font-medium">Anda belum masuk ke dalam kelas.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Quick Stats / Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Hari Ini</div>
                                    <div class="text-sm text-gray-500"><?= date('l, d F Y'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Latest Announcements -->
                <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-800">Pengumuman Terbaru</h4>
                    </div>
                    <div class="p-5">
                        <?php if(isset($data['announcements']) && !empty($data['announcements'])): ?>
                            <div class="space-y-4">
                                <?php foreach($data['announcements'] as $announcement): ?>
                                <div class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h5 class="text-md font-semibold text-gray-800 mb-1"><?= htmlspecialchars($announcement['title']); ?></h5>
                                            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">
                                                <?= date('d M Y', strtotime($announcement['created_at'])); ?>
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 line-clamp-2"><?= strip_tags($announcement['content']); ?></p>
                                        <div class="mt-2 flex items-center text-xs text-gray-500">
                                            <span class="mr-3"><i class="fas fa-user mr-1"></i> <?= htmlspecialchars($announcement['author_name']); ?></span>
                                            <span><i class="far fa-clock mr-1"></i> <?= date('H:i', strtotime($announcement['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-bullhorn text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada pengumuman.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Jadwal Hari Ini</h3>
                        <a href="<?= BASEURL; ?>/student/schedule" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <?php if(!empty($data['today_schedule'])): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php foreach ($data['today_schedule'] as $sch): ?>
                                <div class="p-4 rounded-lg border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50 transition-all group">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <?= substr($sch['start_time'], 0, 5); ?> - <?= substr($sch['end_time'], 0, 5); ?>
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 mb-1 group-hover:text-indigo-700"><?= $sch['subject_name']; ?></h4>
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-user-tie w-4 mr-1 text-gray-400"></i>
                                        <?= $sch['teacher_name']; ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                <i class="fas fa-coffee text-gray-400"></i>
                            </div>
                            <p class="text-gray-500">Tidak ada jadwal pelajaran hari ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>
