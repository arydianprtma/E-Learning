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
                        <h1 class="text-2xl font-bold text-gray-800">Daftar Kelas</h1>
                        <p class="text-gray-600">Daftar kelas yang Anda ajar</p>
                    </div>
                </div>

                <?php if (empty($data['classes'])): ?>
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <i class="fas fa-chalkboard text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kelas</h3>
                        <p class="text-gray-500">Anda belum ditugaskan ke kelas manapun.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['classes'] as $class): ?>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                        <i class="fas fa-book text-xl"></i>
                                    </div>
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full font-medium">
                                        <?= $class['level']; ?> - <?= $class['major']; ?>
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-gray-900 mb-1"><?= $class['subject_name']; ?></h3>
                                <p class="text-gray-500 text-sm mb-4">Kelas <?= $class['class_name']; ?></p>
                                
                                <div class="border-t border-gray-100 pt-4 mt-4 flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Semester Ganjil</span>
                                    <a href="<?= BASEURL; ?>/teacher/class_detail/<?= $class['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<?php require_once '../app/Views/Layouts/footer.php'; ?>
