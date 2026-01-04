<div class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-white transition-transform duration-300 transform -translate-x-full md:translate-x-0 z-50 shadow-xl" id="sidebar">
    <div class="flex items-center justify-center h-16 bg-slate-950 border-b border-slate-800">
        <span class="text-xl font-bold tracking-wider flex items-center">
            <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
            E-Learning
        </span>
    </div>
    
    <nav class="mt-5 px-4 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 4rem);">
        <?php 
        // Robust URL parsing for active state
        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', trim($uri_path, '/'));
        
        // Find the index of 'admin' or 'dashboard' or 'auth' to locate the start of the app routes
        $app_start_index = -1;
        foreach ($uri_segments as $index => $segment) {
            if (in_array(strtolower($segment), ['admin', 'dashboard', 'auth', 'teacher', 'student'])) {
                $app_start_index = $index;
                break;
            }
        }
        
        $controller = ($app_start_index !== -1 && isset($uri_segments[$app_start_index])) ? strtolower($uri_segments[$app_start_index]) : '';
        $method = ($app_start_index !== -1 && isset($uri_segments[$app_start_index + 1])) ? strtolower($uri_segments[$app_start_index + 1]) : '';
        
        // Helper closure to check active state
        $checkActive = function($check_controller, $check_method = null) use ($controller, $method) {
             if ($controller !== $check_controller) return false;
             if ($check_method === null) return true;
             if ($method === $check_method) return true;
             if (strpos($method, $check_method) === 0) return true;
             return false;
        };

        $role_id = $_SESSION['role_id'] ?? 0;
        ?>
        
        <!-- Dashboard -->
        <?php 
        $isActive = (
            $checkActive('dashboard') || 
            ($checkActive('admin') && ($method == '' || $method == 'index')) ||
            ($checkActive('teacher') && ($method == '' || $method == 'index'))
        );
        ?>
        <a href="<?= BASEURL; ?>/dashboard" 
           class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
           <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
            <?php if ($isActive): ?>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
            <?php endif; ?>
            <i class="fas fa-tachometer-alt w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
            <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Dashboard</span>
        </a>

        <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Informasi
                </p>
            </div>

            <!-- Pengumuman -->
            <?php $isActive = $checkActive('admin', 'announcements'); ?>
            <a href="<?= BASEURL; ?>/admin/announcements" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-bullhorn w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Pengumuman</span>
            </a>

        <?php if($role_id == 1): // ADMIN ?>
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Akademik
                </p>
            </div>

            <!-- Cek Nilai Siswa -->
            <?php $isActive = $checkActive('admin', 'grades'); ?>
            <a href="<?= BASEURL; ?>/admin/grades" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-graduation-cap w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Cek Nilai Siswa</span>
            </a>

            <!-- Tahun Akademik -->
            <?php $isActive = $checkActive('admin', 'academic_years'); ?>
            <a href="<?= BASEURL; ?>/admin/academic_years" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-calendar-alt w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Tahun Akademik</span>
            </a>
            
            <!-- Manajemen Kelas -->
            <?php $isActive = ($checkActive('admin', 'classes') || $checkActive('admin', 'class_detail')); ?>
            <a href="<?= BASEURL; ?>/admin/classes" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-chalkboard w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Manajemen Kelas</span>
            </a>
            
            <!-- Mata Pelajaran -->
            <?php $isActive = $checkActive('admin', 'subjects'); ?>
            <a href="<?= BASEURL; ?>/admin/subjects" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-book w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Mata Pelajaran</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Pengguna
                </p>
            </div>

            <!-- Admin -->
            <?php $isActive = $checkActive('admin', 'admins'); ?>
            <a href="<?= BASEURL; ?>/admin/admins" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-user-shield w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Admin</span>
            </a>
            
            <!-- Guru -->
            <?php $isActive = $checkActive('admin', 'teachers'); ?>
            <a href="<?= BASEURL; ?>/admin/teachers" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-chalkboard-teacher w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Guru</span>
            </a>
            
            <!-- Siswa -->
            <?php $isActive = $checkActive('admin', 'students'); ?>
            <a href="<?= BASEURL; ?>/admin/students" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-user-graduate w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Siswa</span>
            </a>
            
            <!-- Orang Tua -->
            <?php $isActive = $checkActive('admin', 'parents'); ?>
            <a href="<?= BASEURL; ?>/admin/parents" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-user-friends w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Orang Tua</span>
            </a>

            

        <?php elseif($role_id == 2): // TEACHER ?>
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Menu Guru
                </p>
            </div>

            <!-- Jadwal Mengajar -->
            <?php $isActive = $checkActive('teacher', 'schedule'); ?>
            <a href="<?= BASEURL; ?>/teacher/schedule" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-calendar-week w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Jadwal Mengajar</span>
            </a>

            <!-- Daftar Kelas -->
            <?php $isActive = ($checkActive('teacher', 'classes') || $checkActive('teacher', 'class_detail')); ?>
            <a href="<?= BASEURL; ?>/teacher/classes" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-chalkboard w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Daftar Kelas</span>
            </a>

        <?php elseif($role_id == 3): // STUDENT ?>
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Menu Siswa
                </p>
            </div>

            <!-- Jadwal Pelajaran -->
            <?php $isActive = $checkActive('student', 'schedule'); ?>
            <a href="<?= BASEURL; ?>/student/schedule" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-calendar-day w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Jadwal Pelajaran</span>
            </a>

            <!-- Lihat Nilai -->
            <?php $isActive = $checkActive('student', 'grades'); ?>
            <a href="<?= BASEURL; ?>/student/grades" 
               class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
               <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                <?php if ($isActive): ?>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
                <?php endif; ?>
                <i class="fas fa-star w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Lihat Nilai</span>
            </a>

        <?php endif; ?>
        
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                Akun
            </p>
        </div>

        <!-- Profile -->
        <?php $isActive = $checkActive('profile'); ?>
        <a href="<?= BASEURL; ?>/profile" 
           class="relative group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 
           <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
            <?php if ($isActive): ?>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-8 w-1 bg-white rounded-r-md"></div>
            <?php endif; ?>
            <i class="fas fa-user-circle w-6 text-center mr-2 text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
            <span class="<?= $isActive ? 'translate-x-1' : '' ?> transition-transform duration-200">Profil Saya</span>
        </a>

        <a href="<?= BASEURL; ?>/auth/logout" 
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 text-slate-300 hover:bg-red-600 hover:text-white mb-6">
            <i class="fas fa-sign-out-alt w-6 text-center mr-2 text-lg text-slate-400 group-hover:text-white"></i>
            Logout
        </a>
    </nav>
</div>
