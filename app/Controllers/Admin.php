<?php

class Admin extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    // Announcement Management
    public function announcements()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $model = $this->model('AnnouncementModel');
        $total_rows = $model->countAll();
        $total_pages = ceil($total_rows / $limit);

        $data = [
            'title' => 'Manajemen Pengumuman',
            'announcements' => $model->getAll($limit, $offset),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_rows' => $total_rows
            ]
        ];
        $this->view('Admin/announcements', $data);
    }

    public function announcements_add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('AnnouncementModel')->add($_POST) > 0) {
                header('Location: ' . BASEURL . '/admin/announcements?success=Pengumuman berhasil ditambahkan');
                exit;
            } else {
                header('Location: ' . BASEURL . '/admin/announcements?error=Gagal menambah pengumuman');
                exit;
            }
        }
    }

    public function announcements_edit($id)
    {
        $data = [
            'title' => 'Edit Pengumuman',
            'announcement' => $this->model('AnnouncementModel')->getById($id)
        ];
        $this->view('Admin/announcements_edit', $data);
    }

    public function announcements_update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('AnnouncementModel')->update($_POST) > 0) {
                header('Location: ' . BASEURL . '/admin/announcements?success=Pengumuman berhasil diupdate');
                exit;
            } else {
                header('Location: ' . BASEURL . '/admin/announcements?error=Gagal update atau tidak ada perubahan');
                exit;
            }
        }
    }

    public function announcements_delete($id)
    {
        if($this->model('AnnouncementModel')->delete($id) > 0) {
            header('Location: ' . BASEURL . '/admin/announcements?success=Pengumuman berhasil dihapus');
        } else {
            header('Location: ' . BASEURL . '/admin/announcements?error=Gagal menghapus pengumuman');
        }
        exit;
    }

    public function index()
    {
        $userModel = $this->model('UserModel');
        
        $data = [
            'title' => 'Admin Dashboard',
            'role' => $_SESSION['role'],
            'username' => $_SESSION['username'],
            'total_admins' => $userModel->countByRole(1),
            'total_teachers' => $userModel->countByRole(2),
            'total_students' => $userModel->countByRole(3),
            'active_students' => $userModel->countStudentByStatus(0),
            'graduated_students' => $userModel->countStudentByStatus(1),
            'total_parents' => $userModel->countByRole(4),
            'student_stats' => $userModel->getStudentGrowthStats(),
            'announcements' => $this->model('AnnouncementModel')->getAll(5) // Get latest 5 announcements
        ];
        $this->view('Admin/index', $data);
    }

    // Academic Year Management
    public function grades()
    {
        $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
        $subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;
        
        $data = [
            'title' => 'Cek Nilai Siswa',
            'classes' => $this->model('ClassModel')->getAll(),
            'subjects' => $this->model('SubjectModel')->getAll(),
            'grades' => [],
            'selected_class' => $class_id,
            'selected_subject' => $subject_id
        ];
        
        if ($class_id || $subject_id) {
            $data['grades'] = $this->model('GradeModel')->getGradesReport($class_id, $subject_id);
        }
        
        $this->view('Admin/grades', $data);
    }

    public function academic_years()
    {
        $model = $this->model('AcademicYearModel');
        $data = [
            'title' => 'Tahun Akademik',
            'years' => $model->getAll()
        ];
        $this->view('Admin/academic_years', $data);
    }

    public function academic_years_add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('AcademicYearModel')->add($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/academic_years?success=Tahun akademik berhasil ditambahkan');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/academic_years?error=Gagal menambah tahun akademik');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DUPLICATE_ENTRY',
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function academic_years_edit($id)
    {
        $data = [
            'title' => 'Edit Tahun Akademik',
            'year' => $this->model('AcademicYearModel')->getById($id)
        ];
        $this->view('Admin/academic_years_edit', $data);
    }

    public function academic_years_update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('AcademicYearModel')->update($_POST) > 0) {
                    
                    // If set to active, ensure others are deactivated
                    if(isset($_POST['is_active'])) {
                        $this->model('AcademicYearModel')->setActive($_POST['id']);
                    }

                    header('Location: ' . BASEURL . '/admin/academic_years?success=Tahun akademik berhasil diupdate');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/academic_years?error=Gagal update atau tidak ada perubahan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DUPLICATE_ENTRY',
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function academic_years_delete($id)
    {
        try {
            if($this->model('AcademicYearModel')->delete($id) > 0) {
                header('Location: ' . BASEURL . '/admin/academic_years?success=Tahun akademik berhasil dihapus');
            } else {
                header('Location: ' . BASEURL . '/admin/academic_years?error=Gagal menghapus tahun akademik');
            }
        } catch (PDOException $e) {
            $msg = "Terjadi kesalahan database";
            if ($e->getCode() == 23000) {
                $msg = "Tahun akademik tidak dapat dihapus karena sedang digunakan oleh data lain";
            }
            header('Location: ' . BASEURL . '/admin/academic_years?error=' . urlencode($msg));
        }
        exit;
    }

    public function academic_years_activate($id)
    {
        $this->model('AcademicYearModel')->setActive($id);
        header('Location: ' . BASEURL . '/admin/academic_years');
        exit;
    }

    // Class Management
    public function classes()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $classModel = $this->model('ClassModel');
        $total_rows = $classModel->countAll($keyword);
        $total_pages = ceil($total_rows / $limit);

        $data = [
            'title' => 'Manajemen Kelas',
            'classes' => $classModel->getAll($keyword, $limit, $offset),
            'years' => $this->model('AcademicYearModel')->getAll(),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_rows' => $total_rows,
                'keyword' => $keyword
            ]
        ];
        $this->view('Admin/classes', $data);
    }

    public function classes_add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validation
            if(empty($_POST['name']) || empty($_POST['level']) || empty($_POST['major']) || empty($_POST['academic_year_id'])) {
                header('Location: ' . BASEURL . '/admin/classes?error=Semua field harus diisi');
                exit;
            }

            try {
                if($this->model('ClassModel')->add($_POST)) {
                    header('Location: ' . BASEURL . '/admin/classes?success=Kelas berhasil ditambahkan');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/classes?error=Gagal menambah kelas ke database');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DUPLICATE_ENTRY',
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function classes_edit($id)
    {
        $data = [
            'title' => 'Edit Kelas',
            'class' => $this->model('ClassModel')->getById($id),
            'years' => $this->model('AcademicYearModel')->getAll()
        ];
        $this->view('Admin/classes_edit', $data);
    }

    public function classes_update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('ClassModel')->update($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/classes?success=Kelas berhasil diupdate');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/classes?error=Gagal update atau tidak ada perubahan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DUPLICATE_ENTRY',
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function classes_delete($id)
    {
        try {
            if($this->model('ClassModel')->delete($id) > 0) {
                header('Location: ' . BASEURL . '/admin/classes?success=Kelas berhasil dihapus');
            } else {
                header('Location: ' . BASEURL . '/admin/classes?error=Gagal menghapus kelas');
            }
        } catch (PDOException $e) {
            $msg = "Terjadi kesalahan database";
            if ($e->getCode() == 23000) {
                $msg = "Kelas tidak dapat dihapus karena masih memiliki siswa atau data terkait lainnya";
            }
            header('Location: ' . BASEURL . '/admin/classes?error=' . urlencode($msg));
        }
        exit;
    }

    public function class_detail($id)
    {
        $classModel = $this->model('ClassModel');
        $assignmentModel = $this->model('AssignmentModel');
        $userModel = $this->model('UserModel');
        $subjectModel = $this->model('SubjectModel');
        $scheduleModel = $this->model('ScheduleModel');

        $student_keyword = isset($_GET['q_student']) ? $_GET['q_student'] : null;

        $subjects = $assignmentModel->getSubjectsInClass($id);
        foreach ($subjects as &$subject) {
            $subject['schedules'] = $scheduleModel->getByClassSubjectId($subject['id']);
        }

        $data = [
            'title' => 'Detail Kelas',
            'class_id' => $id,
            'class' => $classModel->getById($id),
            'students_in_class' => $assignmentModel->getStudentsInClass($id, $student_keyword),
            'subjects_in_class' => $subjects,
            'all_students' => $userModel->getStudentsForClassSelection($id),
            'all_teachers' => $userModel->getAllByRole(2),
            'all_subjects' => $subjectModel->getAll(),      // For dropdown
            'student_keyword' => $student_keyword
        ];
        
        $this->view('Admin/class_detail', $data);
    }

    public function class_add_schedule()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['class_subject_id']) || empty($_POST['day']) || empty($_POST['start_time']) || empty($_POST['end_time'])) {
                $redirect = $_SERVER['HTTP_REFERER'] ?? BASEURL . '/admin/classes';
                header('Location: ' . $redirect . (strpos($redirect, '?') ? '&' : '?') . 'error=Semua field jadwal harus diisi');
                exit;
            }

            try {
                if($this->model('ScheduleModel')->add($_POST)) {
                    $redirect = $_SERVER['HTTP_REFERER'] ?? BASEURL . '/admin/classes';
                    header('Location: ' . $redirect . (strpos($redirect, '?') ? '&' : '?') . 'success=Jadwal berhasil ditambahkan');
                } else {
                    $redirect = $_SERVER['HTTP_REFERER'] ?? BASEURL . '/admin/classes';
                    header('Location: ' . $redirect . (strpos($redirect, '?') ? '&' : '?') . 'error=Gagal menambahkan jadwal');
                }
            } catch (Exception $e) {
                $redirect = $_SERVER['HTTP_REFERER'] ?? BASEURL . '/admin/classes';
                header('Location: ' . $redirect . (strpos($redirect, '?') ? '&' : '?') . 'error=Terjadi kesalahan database');
            }
            exit;
        }
    }

    public function class_delete_schedule($id)
    {
        try {
            if($this->model('ScheduleModel')->delete($id)) {
                $redirect = $_SERVER['HTTP_REFERER'] ?? BASEURL . '/admin/classes';
                header('Location: ' . $redirect . (strpos($redirect, '?') ? '&' : '?') . 'success=Jadwal berhasil dihapus');
            } else {
                $redirect = $_SERVER['HTTP_REFERER'] ?? BASEURL . '/admin/classes';
                header('Location: ' . $redirect . (strpos($redirect, '?') ? '&' : '?') . 'error=Gagal menghapus jadwal');
            }
        } catch (Exception $e) {
            $redirect = $_SERVER['HTTP_REFERER'] ?? BASEURL . '/admin/classes';
            header('Location: ' . $redirect . (strpos($redirect, '?') ? '&' : '?') . 'error=Terjadi kesalahan database');
        }
        exit;
    }

    public function class_add_student()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['student_id'])) {
                header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id']);
                exit;
            }
            try {
                $this->model('AssignmentModel')->assignStudentToClass($_POST['class_id'], $_POST['student_id']);
                header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id'] . '?success=Siswa berhasil ditambahkan');
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                     header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id'] . '?error=Siswa sudah terdaftar di kelas ini');
                } else {
                     header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id'] . '?error=Gagal menambahkan siswa');
                }
            } catch (Exception $e) {
                header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id'] . '?error=Gagal menambahkan siswa');
            }
            exit;
        }
    }

    public function class_remove_student($id)
    {
        if($this->model('AssignmentModel')->removeStudentFromClass($id)) {
             $redirect = BASEURL . '/admin/classes';
             if (isset($_GET['class_id'])) {
                 $redirect = BASEURL . '/admin/class_detail/' . $_GET['class_id'];
             } elseif(isset($_SERVER['HTTP_REFERER'])) {
                 $redirect = $_SERVER['HTTP_REFERER'];
             }
             
             // Append success message cleanly
             $separator = (strpos($redirect, '?') !== false) ? '&' : '?';
             header('Location: ' . $redirect . $separator . 'success=Siswa berhasil dihapus dari kelas');
        } else {
             $redirect = BASEURL . '/admin/classes';
             if (isset($_GET['class_id'])) {
                 $redirect = BASEURL . '/admin/class_detail/' . $_GET['class_id'];
             } elseif(isset($_SERVER['HTTP_REFERER'])) {
                 $redirect = $_SERVER['HTTP_REFERER'];
             }

             $separator = (strpos($redirect, '?') !== false) ? '&' : '?';
             header('Location: ' . $redirect . $separator . 'error=Gagal menghapus siswa');
        }
        exit;
    }

    public function class_add_subject()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['subject_id']) || empty($_POST['teacher_id'])) {
                header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id']);
                exit;
            }
            try {
                if($this->model('AssignmentModel')->assignSubjectToClass($_POST['class_id'], $_POST['subject_id'], $_POST['teacher_id'])) {
                     header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id'] . '?success=Mata pelajaran berhasil ditambahkan');
                } else {
                     header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id'] . '?error=Gagal menambahkan mata pelajaran');
                }
                exit;
            } catch (Exception $e) {
                header('Location: ' . BASEURL . '/admin/class_detail/' . $_POST['class_id'] . '?error=Gagal menambahkan mata pelajaran (Duplikat atau Error)');
                exit;
            }
        }
    }

    public function class_remove_subject($id)
    {
        if($this->model('AssignmentModel')->removeSubjectFromClass($id)) {
             $redirect = BASEURL . '/admin/classes';
             if (isset($_GET['class_id'])) {
                 $redirect = BASEURL . '/admin/class_detail/' . $_GET['class_id'];
             } elseif(isset($_SERVER['HTTP_REFERER'])) {
                 $redirect = $_SERVER['HTTP_REFERER'];
             }
             
             $separator = (strpos($redirect, '?') !== false) ? '&' : '?';
             header('Location: ' . $redirect . $separator . 'success=Mata pelajaran berhasil dihapus dari jadwal');
        } else {
             $redirect = BASEURL . '/admin/classes';
             if (isset($_GET['class_id'])) {
                 $redirect = BASEURL . '/admin/class_detail/' . $_GET['class_id'];
             } elseif(isset($_SERVER['HTTP_REFERER'])) {
                 $redirect = $_SERVER['HTTP_REFERER'];
             }

             $separator = (strpos($redirect, '?') !== false) ? '&' : '?';
             header('Location: ' . $redirect . $separator . 'error=Gagal menghapus mapel');
        }
        exit;
    }

    // Subject Management
    public function subjects()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $subjectModel = $this->model('SubjectModel');
        $total_rows = $subjectModel->countAll($keyword);
        $total_pages = ceil($total_rows / $limit);

        $data = [
            'title' => 'Mata Pelajaran',
            'subjects' => $subjectModel->getAll($keyword, $limit, $offset),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_rows' => $total_rows,
                'keyword' => $keyword
            ]
        ];
        $this->view('Admin/subjects', $data);
    }

    public function subjects_add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('SubjectModel')->add($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/subjects?success=Mata pelajaran berhasil ditambahkan');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/subjects?error=Gagal menambah mata pelajaran');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DUPLICATE_ENTRY',
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function subjects_edit($id)
    {
        $data = [
            'title' => 'Edit Mata Pelajaran',
            'subject' => $this->model('SubjectModel')->getById($id)
        ];
        $this->view('Admin/subjects_edit', $data);
    }

    public function subjects_update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('SubjectModel')->update($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/subjects?success=Mata pelajaran berhasil diupdate');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/subjects?error=Gagal update atau tidak ada perubahan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DUPLICATE_ENTRY',
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function subjects_delete($id)
    {
        try {
            if($this->model('SubjectModel')->delete($id) > 0) {
                header('Location: ' . BASEURL . '/admin/subjects?success=Mata pelajaran berhasil dihapus');
            } else {
                header('Location: ' . BASEURL . '/admin/subjects?error=Gagal menghapus mata pelajaran');
            }
        } catch (PDOException $e) {
            $msg = "Terjadi kesalahan database";
            if ($e->getCode() == 23000) {
                $msg = "Mata pelajaran tidak dapat dihapus karena sedang digunakan dalam jadwal pelajaran";
            }
            header('Location: ' . BASEURL . '/admin/subjects?error=' . urlencode($msg));
        }
        exit;
    }

    // Admin Management
    public function admins()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $userModel = $this->model('UserModel');
        $total_rows = $userModel->countAllByRole(1, $keyword);
        $total_pages = ceil($total_rows / $limit);

        $data = [
            'title' => 'Manajemen Admin',
            'admins' => $userModel->getAllByRole(1, $keyword, $limit, $offset), // 1 = Admin
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_rows' => $total_rows,
                'keyword' => $keyword
            ]
        ];
        $this->view('Admin/admins', $data);
    }

    public function admins_add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('UserModel')->addAdmin($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/admins?success=Admin berhasil ditambahkan');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/admins?error=Gagal menambah admin');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                $errorCode = 'UNKNOWN_ERROR';
                if (strpos($e->getMessage(), 'Username') !== false) $errorCode = 'DUPLICATE_USERNAME';
                if (strpos($e->getMessage(), 'Email') !== false) $errorCode = 'DUPLICATE_EMAIL';
                
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $errorCode,
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function admins_edit($id)
    {
        $data = [
            'title' => 'Edit Admin',
            'admin' => $this->model('UserModel')->getUserById($id)
        ];
        $this->view('Admin/admins_edit', $data);
    }

    public function admins_update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('UserModel')->updateAdmin($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/admins?success=Admin berhasil diupdate');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/admins?error=Gagal update atau tidak ada perubahan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                $errorCode = 'UNKNOWN_ERROR';
                if (strpos($e->getMessage(), 'Username') !== false) $errorCode = 'DUPLICATE_USERNAME';
                if (strpos($e->getMessage(), 'Email') !== false) $errorCode = 'DUPLICATE_EMAIL';
                
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $errorCode,
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function admins_delete($id)
    {
        // Prevent deleting self
        if ($id == $_SESSION['user_id']) {
            header('Location: ' . BASEURL . '/admin/admins?error=Tidak dapat menghapus akun sendiri');
            exit;
        }
        try {
            if ($this->model('UserModel')->deleteUser($id)) {
                header('Location: ' . BASEURL . '/admin/admins?success=Admin berhasil dihapus');
            } else {
                 header('Location: ' . BASEURL . '/admin/admins?error=Gagal menghapus admin');
            }
        } catch (PDOException $e) {
             $msg = "Terjadi kesalahan database";
            if ($e->getCode() == 23000) {
                $msg = "Admin tidak dapat dihapus karena memiliki data terkait";
            }
            header('Location: ' . BASEURL . '/admin/admins?error=' . urlencode($msg));
        }
        exit;
    }

    // Teacher Management
    public function teachers()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $userModel = $this->model('UserModel');
        $total_rows = $userModel->countAllByRole(2, $keyword);
        $total_pages = ceil($total_rows / $limit);

        $data = [
            'title' => 'Manajemen Guru',
            'teachers' => $userModel->getAllByRole(2, $keyword, $limit, $offset), // 2 = Teacher
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_rows' => $total_rows,
                'keyword' => $keyword
            ]
        ];
        $this->view('Admin/teachers', $data);
    }

    public function teachers_add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('UserModel')->addTeacher($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/teachers?success=Guru berhasil ditambahkan');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/teachers?error=Gagal menambah guru');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                $errorCode = 'UNKNOWN_ERROR';
                if (strpos($e->getMessage(), 'Username') !== false) $errorCode = 'DUPLICATE_USERNAME';
                if (strpos($e->getMessage(), 'Email') !== false) $errorCode = 'DUPLICATE_EMAIL';
                
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $errorCode,
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function teachers_edit($id)
    {
        $data = [
            'title' => 'Edit Guru',
            'teacher' => $this->model('UserModel')->getTeacherById($id)
        ];
        $this->view('Admin/teachers_edit', $data);
    }

    public function teachers_update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('UserModel')->updateTeacher($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/teachers?success=Data guru berhasil diupdate');
                    exit;
                } else {
                     // Fallback if no rows affected (e.g. no changes)
                    header('Location: ' . BASEURL . '/admin/teachers?success=Data guru berhasil disimpan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                $errorCode = 'UNKNOWN_ERROR';
                if (strpos($e->getMessage(), 'Username') !== false) $errorCode = 'DUPLICATE_USERNAME';
                if (strpos($e->getMessage(), 'Email') !== false) $errorCode = 'DUPLICATE_EMAIL';
                
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $errorCode,
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function teachers_delete($id)
    {
        $this->model('UserModel')->deleteUser($id);
        header('Location: ' . BASEURL . '/admin/teachers?success=Guru berhasil dihapus');
        exit;
    }

    // Student Management
    public function students()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
        $status = isset($_GET['status']) && $_GET['status'] !== '' ? (int)$_GET['status'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $userModel = $this->model('UserModel');
        $total_rows = $userModel->countAllByRole(3, $keyword, $status);
        $total_pages = ceil($total_rows / $limit);

        $data = [
            'title' => 'Manajemen Siswa',
            'students' => $userModel->getAllByRole(3, $keyword, $limit, $offset, $status), // 3 = Student
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_rows' => $total_rows,
                'keyword' => $keyword,
                'status' => $status
            ]
        ];
        $this->view('Admin/students', $data);
    }

    public function students_add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('UserModel')->addStudent($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/students?success=Siswa berhasil ditambahkan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                $errorCode = 'UNKNOWN_ERROR';
                if (strpos($e->getMessage(), 'Username') !== false) $errorCode = 'DUPLICATE_USERNAME';
                if (strpos($e->getMessage(), 'Email') !== false) $errorCode = 'DUPLICATE_EMAIL';
                
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $errorCode,
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function students_edit($id)
    {
        $data = [
            'title' => 'Edit Siswa',
            'student' => $this->model('UserModel')->getStudentById($id)
        ];
        $this->view('Admin/students_edit', $data);
    }

    public function students_update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('UserModel')->updateStudent($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/students?success=Data siswa berhasil diupdate');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/students?success=Data siswa berhasil disimpan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                $errorCode = 'UNKNOWN_ERROR';
                if (strpos($e->getMessage(), 'Username') !== false) $errorCode = 'DUPLICATE_USERNAME';
                if (strpos($e->getMessage(), 'Email') !== false) $errorCode = 'DUPLICATE_EMAIL';
                
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $errorCode,
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function students_delete($id)
    {
        $this->model('UserModel')->deleteUser($id);
        header('Location: ' . BASEURL . '/admin/students?success=Siswa berhasil dihapus');
        exit;
    }

    // Parent Management
    public function parents()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $userModel = $this->model('UserModel');
        $total_rows = $userModel->countAllByRole(4, $keyword);
        $total_pages = ceil($total_rows / $limit);

        $data = [
            'title' => 'Manajemen Orang Tua',
            'parents' => $userModel->getAllByRole(4, $keyword, $limit, $offset), // 4 = Parent
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_rows' => $total_rows,
                'keyword' => $keyword
            ]
        ];
        $this->view('Admin/parents', $data);
    }

    public function parents_add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('UserModel')->addParent($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/parents?success=Orang tua berhasil ditambahkan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                $errorCode = 'UNKNOWN_ERROR';
                if (strpos($e->getMessage(), 'Username') !== false) $errorCode = 'DUPLICATE_USERNAME';
                if (strpos($e->getMessage(), 'Email') !== false) $errorCode = 'DUPLICATE_EMAIL';
                
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $errorCode,
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function parents_edit($id)
    {
        $data = [
            'title' => 'Edit Orang Tua',
            'parent' => $this->model('UserModel')->getParentById($id)
        ];
        $this->view('Admin/parents_edit', $data);
    }

    public function parents_update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if($this->model('UserModel')->updateParent($_POST) > 0) {
                    header('Location: ' . BASEURL . '/admin/parents?success=Data orang tua berhasil diupdate');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/admin/parents?success=Data orang tua berhasil disimpan');
                    exit;
                }
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                header('Content-Type: application/json');
                $errorCode = 'UNKNOWN_ERROR';
                if (strpos($e->getMessage(), 'Username') !== false) $errorCode = 'DUPLICATE_USERNAME';
                if (strpos($e->getMessage(), 'Email') !== false) $errorCode = 'DUPLICATE_EMAIL';
                
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $errorCode,
                        'message' => $e->getMessage()
                    ]
                ]);
                exit;
            }
        }
    }

    public function parents_delete($id)
    {
        try {
            if ($this->model('UserModel')->deleteUser($id)) {
                header('Location: ' . BASEURL . '/admin/parents?success=Orang tua berhasil dihapus');
            } else {
                header('Location: ' . BASEURL . '/admin/parents?error=Gagal menghapus orang tua');
            }
        } catch (PDOException $e) {
            $msg = "Terjadi kesalahan database";
            if ($e->getCode() == 23000) {
                $msg = "Data orang tua tidak dapat dihapus karena memiliki data terkait";
            }
            header('Location: ' . BASEURL . '/admin/parents?error=' . urlencode($msg));
        }
        exit;
    }

    // Common User Actions
    public function user_toggle_active($id, $redirect_to)
    {
        $this->model('UserModel')->toggleActive($id);
        header('Location: ' . BASEURL . '/admin/' . $redirect_to);
        exit;
    }

    public function student_toggle_graduation($id)
    {
        $this->model('UserModel')->toggleGraduation($id);
        header('Location: ' . BASEURL . '/admin/students?success=Status kelulusan berhasil diubah');
        exit;
    }

    public function user_reset_password($id, $redirect_to)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
            $newPassword = $_POST['password'];
            $this->model('UserModel')->resetPassword($id, $newPassword);
            header('Location: ' . BASEURL . '/admin/' . $redirect_to . '?success=Password berhasil direset');
        } else {
             // Fallback default just in case direct GET access (should be prevented in UI)
            $newPassword = 'password123';
            $this->model('UserModel')->resetPassword($id, $newPassword);
            header('Location: ' . BASEURL . '/admin/' . $redirect_to . '?success=Password direset ke default: password123');
        }
        exit;
    }
}
