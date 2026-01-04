<?php require_once '../app/Views/Layouts/header.php'; ?>
<div class="flex h-screen overflow-hidden bg-slate-50">
    <?php require_once '../app/Views/Layouts/sidebar.php'; ?>
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden md:ml-64 transition-all duration-300">
        <?php require_once '../app/Views/Layouts/topbar.php'; ?>
        
        <main class="w-full flex-grow p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-slate-800">Cek Nilai Siswa</h1>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form action="<?= BASEURL; ?>/admin/grades" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="class_id" id="class_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="">Semua Kelas</option>
                            <?php foreach($data['classes'] as $class): ?>
                                <option value="<?= $class['id']; ?>" <?= $data['selected_class'] == $class['id'] ? 'selected' : ''; ?>>
                                    <?= $class['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <select name="subject_id" id="subject_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="">Semua Mata Pelajaran</option>
                            <?php foreach($data['subjects'] as $subject): ?>
                                <option value="<?= $subject['id']; ?>" <?= $data['selected_subject'] == $subject['id'] ? 'selected' : ''; ?>>
                                    <?= $subject['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                    </div>
                    <div>
                         <?php if($data['selected_class'] || $data['selected_subject']): ?>
                            <a href="<?= BASEURL; ?>/admin/grades" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg transition-colors">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Results -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kuis</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Praktek</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if(empty($data['grades'])): ?>
                                <tr>
                                    <td colspan="10" class="px-6 py-10 text-center text-gray-500">
                                        <?php if($data['selected_class'] || $data['selected_subject']): ?>
                                            Tidak ada data nilai ditemukan.
                                        <?php else: ?>
                                            Silakan pilih filter Kelas atau Mata Pelajaran untuk menampilkan data.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($data['grades'] as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?= $row['student_name']; ?></div>
                                        <div class="text-xs text-gray-500"><?= $row['nis']; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['class_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['subject_name']; ?></td>
                                    
                                    <?php 
                                    $types = ['Tugas', 'Kuis', 'UTS', 'UAS', 'Praktek'];
                                    $total = 0;
                                    $count = 0;
                                    foreach($types as $type): 
                                        $score = isset($row['grades'][$type]) ? $row['grades'][$type] : null;
                                        if($score !== null) {
                                            $total += $score;
                                            $count++;
                                        }
                                    ?>
                                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            <?= $score !== null ? $score : '-'; ?>
                                        </td>
                                    <?php endforeach; ?>
                                    
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                        <?= $count > 0 ? round($total / $count, 1) : '-'; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require_once '../app/Views/Layouts/footer.php'; ?>