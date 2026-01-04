<?php require_once '../app/Views/Layouts/header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-6 py-8">
                <h3 class="text-gray-700 text-3xl font-medium">Dashboard <?= ucfirst($data['role']); ?></h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <!-- Card 1 -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Statistik Akademik</h3>
                                <p class="text-sm text-gray-500">Ringkasan data akademik terkini.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <i class="fas fa-calendar-alt fa-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Jadwal Pelajaran</h3>
                                <p class="text-sm text-gray-500">Lihat jadwal pelajaran hari ini.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                <i class="fas fa-bullhorn fa-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Pengumuman</h3>
                                <p class="text-sm text-gray-500">Informasi terbaru dari sekolah.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Aktivitas Terbaru</h2>
                    <div class="bg-white shadow-md rounded-md p-6 text-center text-gray-500 border border-gray-200">
                        <div class="flex flex-col items-center justify-center py-8">
                            <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-4"></i>
                            <p>Belum ada aktivitas tercatat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>