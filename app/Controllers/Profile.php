<?php

class Profile extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index()
    {
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        $profile = null;
        if ($user['role_id'] == 3) { // Student
            $profile = $userModel->getStudentById($_SESSION['user_id']);
        } elseif ($user['role_id'] == 2) { // Teacher
            $profile = $userModel->getTeacherById($_SESSION['user_id']);
        } elseif ($user['role_id'] == 4) { // Parent
            $profile = $userModel->getParentById($_SESSION['user_id']);
        }

        $data = [
            'title' => 'Profil Saya',
            'user' => $user,
            'profile' => $profile
        ];
        $this->view('Profile/index', $data);
    }

    public function update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('UserModel');
            
            // Handle Password Update
            if(isset($_POST['password']) && !empty($_POST['password'])) {
                 $userModel->resetPassword($_SESSION['user_id'], $_POST['password']);
            }

            // Handle Profile Update for Student
            if ($_SESSION['role_id'] == 3) {
                $data = [
                    'id' => $_SESSION['user_id'],
                    'username' => $_SESSION['username'], // Keep existing username
                    'email' => $_POST['email'] ?? '',
                    'full_name' => $_POST['full_name'] ?? '',
                    'nis' => $_POST['nis'] ?? '',
                    'nisn' => $_POST['nisn'] ?? '',
                    'gender' => $_POST['gender'] ?? '',
                    'place_of_birth' => $_POST['place_of_birth'] ?? '',
                    'date_of_birth' => $_POST['date_of_birth'] ?? '',
                    'religion' => $_POST['religion'] ?? '',
                    'citizenship' => $_POST['citizenship'] ?? 'Indonesia',
                    'address' => $_POST['address'] ?? '',
                    'province' => $_POST['province'] ?? '',
                    'city' => $_POST['city'] ?? '',
                    'district' => $_POST['district'] ?? '',
                    'postal_code' => $_POST['postal_code'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'parent_name' => $_POST['parent_name'] ?? '',
                    'parent_relationship' => $_POST['parent_relationship'] ?? '',
                    'parent_phone' => $_POST['parent_phone'] ?? '',
                    'parent_address' => $_POST['parent_address'] ?? '',
                    // Keep existing academic info
                    'entry_date' => $_POST['entry_date'] ?? null,
                    'graduation_date' => $_POST['graduation_date'] ?? null
                ];
                
                // We need to fetch existing data to preserve fields that might not be in the form
                // But for now, let's assume the form provides all editable fields.
                // However, entry_date and graduation_date should probably not be editable by student.
                
                // Validation
                if (empty($_POST['full_name']) || empty($_POST['email'])) {
                    header('Location: ' . BASEURL . '/profile?error=Nama lengkap dan Email wajib diisi');
                    exit;
                }
                
                // Validate Email format
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    header('Location: ' . BASEURL . '/profile?error=Format email tidak valid');
                    exit;
                }

                // Validate Parent Data
                if (empty($_POST['parent_name']) || empty($_POST['parent_relationship']) || empty($_POST['parent_phone'])) {
                    header('Location: ' . BASEURL . '/profile?error=Data orang tua wajib diisi lengkap');
                    exit;
                }

                // Fetch current profile to get read-only fields
                $currentProfile = $userModel->getStudentById($_SESSION['user_id']);
                if ($currentProfile) {
                    $data['username'] = $currentProfile['username'];
                    $data['entry_date'] = $currentProfile['entry_date'];
                    $data['graduation_date'] = $currentProfile['graduation_date'];
                    $data['nis'] = $currentProfile['nis']; // NIS usually readonly for student
                    $data['nisn'] = $currentProfile['nisn']; // NISN usually readonly for student
                }

                try {
                    if ($userModel->updateStudent($data)) {
                        // Update session email if changed
                        if (isset($_POST['email'])) {
                            // This is a bit risky if email is used for login, but for now ok.
                        }
                        header('Location: ' . BASEURL . '/profile?success=Profil berhasil diperbarui');
                        exit;
                    } else {
                        header('Location: ' . BASEURL . '/profile?error=Gagal memperbarui profil. Silakan coba lagi.');
                        exit;
                    }
                } catch (Exception $e) {
                    header('Location: ' . BASEURL . '/profile?error=' . urlencode($e->getMessage()));
                    exit;
                }
            }
            
            // Handle Profile Update for Teacher
            elseif ($_SESSION['role_id'] == 2) {
                $data = [
                    'id' => $_SESSION['user_id'],
                    'username' => $_SESSION['username'],
                    'email' => $_POST['email'] ?? '',
                    'full_name' => $_POST['full_name'] ?? '',
                    'nip' => $_POST['nip'] ?? '',
                    'nuptk' => $_POST['nuptk'] ?? '',
                    'front_title' => $_POST['front_title'] ?? '',
                    'back_title' => $_POST['back_title'] ?? '',
                    'gender' => $_POST['gender'] ?? '',
                    'place_of_birth' => $_POST['place_of_birth'] ?? '',
                    'date_of_birth' => $_POST['date_of_birth'] ?? '',
                    'religion' => $_POST['religion'] ?? '',
                    'marital_status' => $_POST['marital_status'] ?? '',
                    // Employment data is typically read-only or admin managed, but we'll allow updating if passed,
                    // or better yet, fetch from DB and overwrite if we want to restrict.
                    // For now, let's allow it but we might restrict it in the View.
                    // Actually, let's fetch current profile to protect sensitive employment data if needed.
                    // User said "portal guru" so they might need to update "Sertifikasi" etc.
                    
                    'last_education' => $_POST['last_education'] ?? '',
                    'study_program' => $_POST['study_program'] ?? '',
                    'university' => $_POST['university'] ?? '',
                    'graduation_year' => $_POST['graduation_year'] ?? '',
                    'is_certified' => isset($_POST['is_certified']) ? 1 : 0,
                    'certificate_number' => $_POST['certificate_number'] ?? '',
                ];

                // Validation
                if (empty($_POST['full_name']) || empty($_POST['email'])) {
                    header('Location: ' . BASEURL . '/profile?error=Nama lengkap dan Email wajib diisi');
                    exit;
                }

                // Fetch current profile to preserve protected fields or fill missing ones
                $currentProfile = $userModel->getTeacherById($_SESSION['user_id']);
                if ($currentProfile) {
                     // Preserve Username (Read-only)
                    $data['username'] = $currentProfile['username'];
                    
                    // We can choose to protect Employment Data here if we want to make it Admin-only
                    // For now, I'll allow the view to decide what is sent. 
                    // But if it's not sent, we should keep existing.
                    // Wait, the View will likely have disabled inputs for read-only fields, so they won't be sent?
                    // Or they will be sent if I don't disable them.
                    // If I disable them in view, they won't be in $_POST.
                    
                    // Let's populate employment data from $_POST if available, otherwise keep existing.
                    // But wait, if I don't include them in the form at all, they might be nullified if I bind null.
                    // My model binds them.
                    
                    // Safest approach: Merge existing data with POST data.
                    $data['employment_status'] = $_POST['employment_status'] ?? $currentProfile['employment_status'];
                    $data['position'] = $_POST['position'] ?? $currentProfile['position'];
                    $data['subjects'] = $_POST['subjects'] ?? $currentProfile['subjects'];
                    $data['start_teaching_date'] = $_POST['start_teaching_date'] ?? $currentProfile['start_teaching_date'];
                    $data['teaching_hours_per_week'] = $_POST['teaching_hours_per_week'] ?? $currentProfile['teaching_hours_per_week'];
                    $data['status_detail'] = $_POST['status_detail'] ?? $currentProfile['status_detail'];
                }

                try {
                    if ($userModel->updateTeacher($data)) {
                        header('Location: ' . BASEURL . '/profile?success=Profil berhasil diperbarui');
                        exit;
                    } else {
                        header('Location: ' . BASEURL . '/profile?error=Gagal memperbarui profil. Silakan coba lagi.');
                        exit;
                    }
                } catch (Exception $e) {
                    header('Location: ' . BASEURL . '/profile?error=' . urlencode($e->getMessage()));
                    exit;
                }
            }

            header('Location: ' . BASEURL . '/profile?success=Profil berhasil diperbarui');
            exit;
        }
    }
}