<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                
                <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 shadow-lg text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-3xl font-bold mb-2">Dashboard Overview</h3>
                        <p class="text-blue-100 text-lg">Selamat datang kembali, <span class="font-semibold text-white"><?= ucfirst($data['username']); ?></span>! Pantau aktivitas akademik sekolah dengan mudah.</p>
                    </div>
                    <div class="absolute right-0 top-0 h-full opacity-10 transform translate-x-10 -translate-y-5">
                        <i class="fas fa-school fa-10x"></i>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Admin Card -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                        <div class="p-5 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Admin</p>
                                <h4 class="text-2xl font-bold text-gray-800"><?= $data['total_admins']; ?></h4>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                                <i class="fas fa-user-shield fa-lg"></i>
                            </div>
                        </div>
                        <div class="bg-blue-50 px-5 py-2 border-t border-blue-100">
                            <a href="<?= BASEURL; ?>/admin/admins" class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center">
                                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Guru Card -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                        <div class="p-5 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Guru</p>
                                <h4 class="text-2xl font-bold text-gray-800"><?= $data['total_teachers']; ?></h4>
                            </div>
                            <div class="p-3 bg-green-50 rounded-full text-green-600">
                                <i class="fas fa-chalkboard-teacher fa-lg"></i>
                            </div>
                        </div>
                        <div class="bg-green-50 px-5 py-2 border-t border-green-100">
                            <a href="<?= BASEURL; ?>/admin/teachers" class="text-xs font-medium text-green-600 hover:text-green-800 flex items-center">
                                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Siswa Card -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                        <div class="p-5 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Siswa</p>
                                <h4 class="text-2xl font-bold text-gray-800"><?= $data['total_students']; ?></h4>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="text-green-600 font-semibold" title="Siswa Aktif"><?= $data['active_students']; ?></span> Aktif | 
                                    <span class="text-blue-600 font-semibold" title="Alumni"><?= $data['graduated_students']; ?></span> Alumni
                                </div>
                            </div>
                            <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                        </div>
                        <div class="bg-indigo-50 px-5 py-2 border-t border-indigo-100">
                            <a href="<?= BASEURL; ?>/admin/students" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 flex items-center">
                                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Orang Tua Card -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                        <div class="p-5 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Orang Tua</p>
                                <h4 class="text-2xl font-bold text-gray-800"><?= $data['total_parents']; ?></h4>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                        <div class="bg-purple-50 px-5 py-2 border-t border-purple-100">
                            <a href="<?= BASEURL; ?>/admin/parents" class="text-xs font-medium text-purple-600 hover:text-purple-800 flex items-center">
                                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Latest Announcements -->
                <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-800">Pengumuman Terbaru</h4>
                        <a href="<?= BASEURL; ?>/admin/announcements" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Semua</a>
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
                                <p>Belum ada pengumuman yang dibuat.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Student Growth Chart -->
                    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-lg font-bold text-gray-800">Pertumbuhan Siswa</h4>
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Per Tahun Akademik</span>
                        </div>
                        <div class="relative" style="height: 300px;">
                            <canvas id="studentGrowthChart"></canvas>
                        </div>
                    </div>

                    <!-- Quick Activity / Distribution (Placeholder for now) -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h4 class="text-lg font-bold text-gray-800 mb-6">Distribusi Pengguna</h4>
                        <div class="relative" style="height: 300px;">
                            <canvas id="userDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data for Student Growth
    <?php
        $years = [];
        $totals = [];
        if(isset($data['student_stats']) && is_array($data['student_stats'])) {
            foreach ($data['student_stats'] as $stat) {
                $years[] = $stat['year'];
                $totals[] = $stat['total'];
            }
        }
    ?>
    
    const studentCtx = document.getElementById('studentGrowthChart').getContext('2d');
    const studentGrowthChart = new Chart(studentCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($years); ?>,
            datasets: [{
                label: 'Jumlah Siswa',
                data: <?= json_encode($totals); ?>,
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.15)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                        
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#4F46E5',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    display: false 
                }, 
                tooltip: { 
                    backgroundColor: '#111827', 
                    titleColor: '#ffffff', 
                    bodyColor: '#ffffff', 
                    padding: 12, 
                    callbacks: { 
                        label: ctx => `Jumlah Siswa: ${ctx.parsed.y} orang` 
                    } 
                } 
            }, 
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        stepSize: 1, 
                        callback: v => v + ' siswa' 
                    } 
                }, 
                x: { 
                    grid: { display: false } 
                } 
            } 
        }
    });

    // Data for User Distribution (Pie Chart)
    const distributionCtx = document.getElementById('userDistributionChart').getContext('2d');
    const userDistributionChart = new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Guru', 'Siswa', 'Orang Tua'],
            datasets: [{
                data: [
                    <?= $data['total_admins']; ?>,
                    <?= $data['total_teachers']; ?>,
                    <?= $data['total_students']; ?>,
                    <?= $data['total_parents']; ?>
                ],
                backgroundColor: [
                    '#2563EB', // Admin
                    '#22C55E', // Guru
                    '#FB7185', // Siswa
                    '#8B5CF6'  // Orang Tua
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });
</script>

<?php require_once '../app/Views/Layouts/footer.php'; ?>