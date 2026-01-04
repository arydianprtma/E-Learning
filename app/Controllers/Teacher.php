<?php

class Teacher extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index()
    {
        $teacher_id = $_SESSION['user_id'];
        $userModel = $this->model('UserModel');
        $teacher = $userModel->getTeacherById($teacher_id);
        
        $formatted_name = $teacher['full_name'];
        if($teacher) {
            $formatted_name = ($teacher['front_title'] ? $teacher['front_title'] . ' ' : '') . $teacher['full_name'] . ($teacher['back_title'] ? ', ' . $teacher['back_title'] : '');
        }

        $data = [
            'title' => 'Dashboard Guru',
            'role' => $_SESSION['role'],
            'username' => $formatted_name, // Override username with full name + titles
        ];

        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $today = $days[date('w')];
        
        $scheduleModel = $this->model('ScheduleModel');
        $data['today_schedule'] = $scheduleModel->getTodayScheduleByTeacher($teacher_id, $today);
        $data['announcements'] = $this->model('AnnouncementModel')->getAll(5);
        $data['teaching_hours_this_week'] = $scheduleModel->getRealizedHoursThisWeek($teacher_id);
        
        // Add Total Classes count for Dashboard Stats
        $data['total_classes'] = count($this->model('AssignmentModel')->getClassesByTeacher($teacher_id));

        $this->view('Teacher/index', $data);
    }

    public function schedule()
    {
        $data = [
            'title' => 'Jadwal Mengajar',
            'role' => $_SESSION['role'],
            'username' => $_SESSION['username'],
        ];
        
        $teacher_id = $_SESSION['user_id'];
        $data['schedule'] = $this->model('ScheduleModel')->getScheduleByTeacher($teacher_id);
        
        $this->view('Teacher/schedule', $data);
    }

    public function classes()
    {
        $data = [
            'title' => 'Daftar Kelas',
            'role' => $_SESSION['role'],
            'username' => $_SESSION['username'],
        ];
        
        $teacher_id = $_SESSION['user_id'];
        $data['classes'] = $this->model('AssignmentModel')->getClassesByTeacher($teacher_id);
        
        $this->view('Teacher/classes', $data);
    }

    public function class_detail($id)
    {
        // $id is class_subject_id
        $classSubject = $this->model('AssignmentModel')->getClassSubjectById($id);
        
        if(!$classSubject) {
             header('Location: ' . BASEURL . '/teacher/classes');
             exit;
        }

        // Verify teacher ownership
        // Note: teacher_id in class_subjects refers to users.id (which is session user_id)
        if($classSubject['teacher_id'] != $_SESSION['user_id']) {
             header('Location: ' . BASEURL . '/teacher/classes');
             exit;
        }

        $data = [
            'title' => 'Detail Kelas - ' . $classSubject['class_name'],
            'role' => $_SESSION['role'],
            'username' => $_SESSION['username'],
            'class_subject' => $classSubject,
            'students' => $this->model('AssignmentModel')->getStudentsInClass($classSubject['class_id']),
            'date' => isset($_GET['date']) ? $_GET['date'] : date('Y-m-d')
        ];

        // --- VALIDATION LOGIC START ---
        $dayMap = [
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu',
            'Sun' => 'Minggu'
        ];
        
        $targetDateTimestamp = strtotime($data['date']);
        $todayEnglish = date('D', $targetDateTimestamp);
        $todayIndo = $dayMap[$todayEnglish] ?? '';
        
        // Get ALL schedules for today to handle multiple sessions
        $schedules = $this->model('ScheduleModel')->getSchedulesForToday($id, $todayIndo);
        
        $schedule = null;
        if (!empty($schedules)) {
            $currentTime = time();
            $candidates = [];
            
            // Filter unsubmitted schedules
            foreach ($schedules as $s) {
                $journal = $this->model('ScheduleModel')->getJournalBySession($id, $data['date'], $s['start_time'], $s['end_time']);
                if (!$journal) {
                    $candidates[] = $s;
                }
            }
            
            if (!empty($candidates)) {
                // Priority 1: Currently Running
                foreach ($candidates as $c) {
                    $startTime = strtotime($data['date'] . ' ' . $c['start_time']);
                    $endTime = strtotime($data['date'] . ' ' . $c['end_time']);
                    if ($currentTime >= $startTime && $currentTime <= $endTime) {
                        $schedule = $c;
                        break;
                    }
                }
                
                // Priority 2: First Unsubmitted (Earliest)
                if (!$schedule) {
                    $schedule = $candidates[0];
                }
            } else {
                // All submitted: Show the last one
                $schedule = end($schedules);
            }
        }
        
        $attendance_status = 'closed'; 
        $status_message = 'Tidak ada jadwal pada tanggal ini';
        $is_late = false;
        
        // Strict Validation: Only allow input for TODAY
        $isToday = ($data['date'] == date('Y-m-d'));
        
        if ($schedule) {
            if ($isToday) {
                $currentTime = time();
                $startTime = strtotime($data['date'] . ' ' . $schedule['start_time']);
                $endTime = strtotime($data['date'] . ' ' . $schedule['end_time']);
                
                // New Rule: Cutoff is End Time + 1 Hour (3600 seconds)
                $cutoffTime = $endTime + 3600;
                
                // Normal Window (Start to End)
                if ($currentTime >= $startTime && $currentTime <= $endTime) {
                    $attendance_status = 'open';
                    $status_message = 'Absensi Dibuka';
                } 
                // Late Window (End to End + 1 Hour)
                elseif ($currentTime > $endTime && $currentTime <= $cutoffTime) {
                    $attendance_status = 'open_late';
                    $status_message = 'Absensi Dibuka (Terlambat)';
                    $is_late = true;
                } 
                // Closed
                else {
                    $attendance_status = 'closed';
                    if ($currentTime > $cutoffTime) {
                        $status_message = 'Absensi Ditutup (Melewati batas toleransi 1 jam)';
                    } else {
                        $status_message = 'Absensi Belum Dibuka';
                    }
                }
            } else {
                $attendance_status = 'closed';
                $status_message = 'Absensi hanya dapat diisi pada hari jadwal berlangsung';
            }
        }

        $data['attendance_status'] = $attendance_status;
        $data['status_message'] = $status_message;
        $data['is_late'] = $is_late;
        $data['schedule_today'] = $schedule;
        // --- VALIDATION LOGIC END ---
        
        // Get Attendance for date
        $attendance = $this->model('AttendanceModel')->getByClassSubjectAndDate($id, $data['date']);
        
        // Map attendance by student_id for easier lookup in view
        $data['attendance_map'] = [];
        foreach($attendance as $att) {
            $data['attendance_map'][$att['student_id']] = $att;
        }

        // Get Attendance Summary (Counts)
        $attendanceSummary = $this->model('AttendanceModel')->getAttendanceSummaryByClass($id);
        $data['attendance_summary'] = [];
        foreach($attendanceSummary as $summary) {
            $data['attendance_summary'][$summary['student_id']] = $summary;
        }

        // Get Grades and Map them by student_id and type
        $grades = $this->model('GradeModel')->getByClassSubject($id);
        $data['grades'] = $grades; // Keep original list for safety or debug
        
        $data['student_grades'] = [];
        foreach($grades as $grade) {
            $data['student_grades'][$grade['student_id']][$grade['type']][] = $grade;
        }

        $this->view('Teacher/class_detail', $data);
    }

    public function input_attendance()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $class_subject_id = $_POST['class_subject_id'];
            
            // Verify ownership
            $classSubject = $this->model('AssignmentModel')->getClassSubjectById($class_subject_id);
            if (!$classSubject || $classSubject['teacher_id'] != $_SESSION['user_id']) {
                 header('Location: ' . BASEURL . '/teacher/classes?error=Akses ditolak');
                 exit;
            }

            $date = $_POST['date'];
            
            // --- STRICT VALIDATION START ---
            // 1. Must be Today
            if ($date != date('Y-m-d')) {
                header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?date=' . $date . '&error=Absensi hanya bisa diisi pada hari ini');
                exit;
            }

            // 2. Check Schedule
            $dayMap = ['Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu', 'Sun' => 'Minggu'];
            $todayIndo = $dayMap[date('D')];
            
            // NEW: Fetch all schedules
            $schedules = $this->model('ScheduleModel')->getSchedulesForToday($class_subject_id, $todayIndo);
            
            // NEW: Get times from POST
            $start_time = $_POST['start_time'] ?? null;
            $end_time = $_POST['end_time'] ?? null;
            
            $schedule = null;
            if ($start_time && $end_time) {
                foreach($schedules as $s) {
                    if ($s['start_time'] == $start_time && $s['end_time'] == $end_time) {
                        $schedule = $s;
                        break;
                    }
                }
            }
            
            // Fallback: If not provided (old form submission), try to find the only one
            if (!$schedule && count($schedules) == 1) {
                $schedule = $schedules[0];
            }

            if (!$schedule) {
                header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?date=' . $date . '&error=Jadwal tidak valid atau tidak ditemukan');
                exit;
            }

            // 3. Check Time (Cutoff: End Time + 1 Hour)
            $currentTime = time();
            $startTime = strtotime($date . ' ' . $schedule['start_time']);
            $endTime = strtotime($date . ' ' . $schedule['end_time']);
            $cutoffTime = $endTime + 3600; // 1 Hour tolerance

            if ($currentTime > $cutoffTime) {
                 header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?date=' . $date . '&error=Absensi ditutup (Melewati batas toleransi 1 jam setelah kelas berakhir)');
                 exit;
            }
            
            // 4. Check Start Time
            if ($currentTime < $startTime) {
                 header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?date=' . $date . '&error=Absensi belum dibuka (Mulai: ' . $schedule['start_time'] . ')');
                 exit;
            }
            
            // 5. Strict Validation: Removed "Check End Time" check to allow input during class
            // --- STRICT VALIDATION END ---

            $scheduleModel = $this->model('ScheduleModel');
            
            // 6. Strict Validation: Check Duplicate Journal (Prevent modification/double counting)
            // "Menolak ... Memodifikasi jam mengajar setelah disimpan"
            // Check by Class and Date to respect DB unique constraint 'teaching_journals.unique_journal' (class_subject_id, date)
            $existingJournal = $scheduleModel->getJournalByClassAndDate($class_subject_id, $date);
            if ($existingJournal) {
                 header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?date=' . $date . '&error=Data absensi untuk tanggal ini sudah tersimpan.');
                 exit;
            }

            $students = $_POST['students']; // Array of student_ids
            $statuses = $_POST['status']; // Array key=student_id, value=status
            $notes = $_POST['note'] ?? [];

            $attendanceModel = $this->model('AttendanceModel');
            
            try {
                // 1. Save Teaching Journal First (To record hours permanently)
                $journalData = [
                    'teacher_id' => $_SESSION['user_id'],
                    'class_subject_id' => $class_subject_id,
                    'date' => $date,
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time']
                ];
                $scheduleModel->createTeachingJournal($journalData);

                // 2. Save Attendance Records
                foreach($students as $student_id) {
                    $data = [
                        'class_subject_id' => $class_subject_id,
                        'student_id' => $student_id,
                        'date' => $date,
                        'status' => $statuses[$student_id] ?? 'Hadir',
                        'note' => $notes[$student_id] ?? ''
                    ];
                    $attendanceModel->addOrUpdate($data);
                }

                // Calculate and update teaching hours
                $teacher_id = $_SESSION['user_id'];
                $userModel = $this->model('UserModel');
                
                // Get updated hours
                $totalHours = $scheduleModel->getRealizedHoursThisWeek($teacher_id);
                
                // Update profile
                $userModel->updateTeachingHours($teacher_id, $totalHours);
                
                // Log activity
                $userModel->logActivity(
                    $teacher_id, 
                    'input_attendance', 
                    "Input absensi kelas $class_subject_id ($date). Total jam mengajar: $totalHours"
                );
                
                $msg = "Absensi berhasil disimpan. Total jam mengajar minggu ini: $totalHours jam";
                header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?date=' . $date . '&success=' . urlencode($msg));
            } catch (Exception $e) {
                // If duplicates or errors, it might fail here
                header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?date=' . $date . '&error=Gagal menyimpan absensi: ' . $e->getMessage());
            }
            exit;
        }
    }

    public function save_grades_bulk()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $class_subject_id = $_POST['class_subject_id'];
            
            // Verify ownership
            $classSubject = $this->model('AssignmentModel')->getClassSubjectById($class_subject_id);
            if (!$classSubject || $classSubject['teacher_id'] != $_SESSION['user_id']) {
                 header('Location: ' . BASEURL . '/teacher/classes?error=Akses ditolak');
                 exit;
            }

            $grades = $_POST['grades'] ?? []; // Array [student_id][type] = score
            $gradeModel = $this->model('GradeModel');

            try {
                foreach ($grades as $student_id => $types) {
                    foreach ($types as $type => $score) {
                        $data = [
                            'class_subject_id' => $class_subject_id,
                            'student_id' => $student_id,
                            'type' => $type,
                            'score' => $score
                        ];
                        $gradeModel->upsert($data);
                    }
                }
                
                header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?tab=grades&success=Nilai berhasil disimpan');
            } catch (Exception $e) {
                header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?tab=grades&error=Gagal menyimpan nilai');
            }
        }
    }

    public function input_grade()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $class_subject_id = $_POST['class_subject_id'];
            $student_id = $_POST['student_id'];
            $type = $_POST['type'];
            $score = $_POST['score'];
            $description = $_POST['description'];
            $grade_id = $_POST['grade_id'] ?? null; // If editing

            // Server-side validation
            if ($score < 0 || $score > 100) {
                 header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?tab=grades&error=Nilai harus antara 0-100');
                 exit;
            }

            $gradeModel = $this->model('GradeModel');

            try {
                $data = [
                    'class_subject_id' => $class_subject_id,
                    'student_id' => $student_id,
                    'type' => $type,
                    'score' => $score,
                    'description' => $description,
                    'id' => $grade_id
                ];

                if($grade_id) {
                    $gradeModel->update($data);
                    $msg = 'Nilai berhasil diperbarui';
                } else {
                    $gradeModel->add($data);
                    $msg = 'Nilai berhasil ditambahkan';
                }
                
                header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?tab=grades&success=' . $msg);
            } catch (Exception $e) {
                header('Location: ' . BASEURL . '/teacher/class_detail/' . $class_subject_id . '?tab=grades&error=Gagal menyimpan nilai');
            }
            exit;
        }
    }
    
    public function delete_grade($id)
    {
        $grade = $this->model('GradeModel')->getById($id);
        if(!$grade) {
             header('Location: ' . BASEURL . '/teacher/classes?error=Data nilai tidak ditemukan');
             exit;
        }

        // Verify ownership
        $classSubject = $this->model('AssignmentModel')->getClassSubjectById($grade['class_subject_id']);
        if(!$classSubject || $classSubject['teacher_id'] != $_SESSION['user_id']) {
             header('Location: ' . BASEURL . '/teacher/classes?error=Akses ditolak');
             exit;
        }
        
        if($this->model('GradeModel')->delete($id)) {
             header('Location: ' . BASEURL . '/teacher/class_detail/' . $grade['class_subject_id'] . '?tab=grades&success=Nilai berhasil dihapus');
        } else {
             header('Location: ' . BASEURL . '/teacher/class_detail/' . $grade['class_subject_id'] . '?tab=grades&error=Gagal menghapus nilai');
        }
        exit;
    }
}
