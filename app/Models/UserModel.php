<?php

class UserModel {
    private $table = 'users';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getUserByUsername($username)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    public function getUserRole($role_id)
    {
        $this->db->query('SELECT name FROM roles WHERE id = :id');
        $this->db->bind('id', $role_id);
        $role = $this->db->single();
        return $role ? $role['name'] : null;
    }

    public function getAllByRole($role_id, $keyword = null, $limit = 0, $offset = 0, $status = null)
    {
        // Base Query
        $query = "SELECT u.*";
        
        if ($role_id == 2) { // Teacher
            $query .= ", CONCAT(COALESCE(CONCAT(tp.front_title, ' '), ''), tp.full_name, COALESCE(CONCAT(', ', tp.back_title), '')) as full_name, tp.nip, tp.nuptk, COALESCE(NULLIF(tp.nip, ''), tp.nuptk, '-') as identification_number, tp.is_certified";
        } elseif ($role_id == 3) { // Student
            $query .= ", sp.full_name, sp.nis, sp.nis as identification_number, sp.is_graduated";
        } elseif ($role_id == 4) { // Parent
            $query .= ", pp.full_name, pp.phone";
        }
        
        $query .= " FROM " . $this->table . " u";

        if ($role_id == 2) {
            $query .= " LEFT JOIN teacher_profiles tp ON u.id = tp.user_id";
        } elseif ($role_id == 3) {
            $query .= " LEFT JOIN student_profiles sp ON u.id = sp.user_id";
        } elseif ($role_id == 4) {
            $query .= " LEFT JOIN parent_profiles pp ON u.id = pp.user_id";
        }

        $query .= " WHERE u.role_id = :role_id";

        if ($keyword) {
             if ($role_id == 2) {
                $query .= " AND (u.username LIKE :keyword OR u.email LIKE :keyword OR tp.full_name LIKE :keyword OR tp.nip LIKE :keyword)";
             } elseif ($role_id == 3) {
                $query .= " AND (u.username LIKE :keyword OR u.email LIKE :keyword OR sp.full_name LIKE :keyword OR sp.nis LIKE :keyword)";
             } elseif ($role_id == 4) {
                $query .= " AND (u.username LIKE :keyword OR u.email LIKE :keyword OR pp.full_name LIKE :keyword)";
             } else {
                $query .= " AND (u.username LIKE :keyword OR u.email LIKE :keyword)";
             }
        }

        if ($status !== null && $role_id == 3) {
            $query .= " AND sp.is_graduated = :status";
        }

        // Add ordering
        $query .= " ORDER BY u.id DESC";

        if ($limit > 0) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $this->db->query($query);
        $this->db->bind('role_id', $role_id);
        
        if ($keyword) {
            $this->db->bind('keyword', "%$keyword%");
        }
        
        if ($status !== null && $role_id == 3) {
            $this->db->bind('status', $status);
        }

        if ($limit > 0) {
            $this->db->bind('limit', $limit);
            $this->db->bind('offset', $offset);
        }

        return $this->db->resultSet();
    }

    public function getStudentsForClassSelection($class_id, $keyword = null)
    {
        $query = "SELECT u.*, sp.full_name, sp.nis, sp.nis as identification_number, sp.is_graduated,
                  (SELECT COUNT(*) FROM class_students cs WHERE cs.student_id = u.id AND cs.class_id = :class_id) as is_registered
                  FROM users u
                  LEFT JOIN student_profiles sp ON u.id = sp.user_id
                  WHERE u.role_id = 3";

        if ($keyword) {
             $query .= " AND (u.username LIKE :keyword OR u.email LIKE :keyword OR sp.full_name LIKE :keyword OR sp.nis LIKE :keyword)";
        }
        
        $query .= " ORDER BY is_registered ASC, sp.full_name ASC";

        $this->db->query($query);
        $this->db->bind('class_id', $class_id);
        
        if ($keyword) {
            $this->db->bind('keyword', "%$keyword%");
        }

        return $this->db->resultSet();
    }

    public function countAllByRole($role_id, $keyword = null, $graduation_status = null)
    {
        $query = "SELECT COUNT(*) as total
                  FROM users u
                  JOIN roles r ON u.role_id = r.id
                  LEFT JOIN teacher_profiles tp ON u.id = tp.user_id
                  LEFT JOIN student_profiles sp ON u.id = sp.user_id
                  WHERE u.role_id = :role_id";

        if ($keyword) {
            $query .= " AND (
                u.username LIKE :keyword OR 
                u.email LIKE :keyword OR
                tp.full_name LIKE :keyword OR
                tp.nip LIKE :keyword OR
                sp.full_name LIKE :keyword OR
                sp.nis LIKE :keyword
            )";
        }

        if ($graduation_status !== null && $role_id == 3) {
            $query .= " AND sp.is_graduated = :graduation_status";
        }
                  
        $this->db->query($query);
        $this->db->bind('role_id', $role_id);
        
        if ($keyword) {
            $this->db->bind('keyword', "%$keyword%");
        }

        if ($graduation_status !== null && $role_id == 3) {
            $this->db->bind('graduation_status', $graduation_status);
        }

        $result = $this->db->single();
        return $result['total'];
    }

    private function validateUsername($username)
    {
        // Unique, alphanumeric, min 6 chars
        if (!preg_match('/^[a-zA-Z0-9]{6,}$/', $username)) {
            throw new Exception("Username harus alfanumerik dan minimal 6 karakter", 400);
        }

        // Check uniqueness
        $this->db->query('SELECT id FROM ' . $this->table . ' WHERE username = :username');
        $this->db->bind('username', $username);
        if ($this->db->single()) {
            throw new Exception("Username sudah digunakan", 409);
        }
    }

    public function updateLastLogin($user_id)
    {
        $this->db->query("UPDATE " . $this->table . " SET last_login = NOW() WHERE id = :id");
        $this->db->bind('id', $user_id);
        return $this->db->execute();
    }

    public function addTeacher($data)
    {
        // Validate Username
        $this->validateUsername($data['username']);

        // 1. Insert User
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->query('INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, 2)'); // 2 = Teacher
        $this->db->bind('username', $data['username']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $password);
        
        if($this->db->execute()) {
            // Get inserted ID
            $this->db->query('SELECT id FROM users WHERE username = :username');
            $this->db->bind('username', $data['username']);
            $user = $this->db->single();
            $user_id = $user['id'];

            // 2. Insert Profile
            $this->db->query('INSERT INTO teacher_profiles (user_id, nip, full_name) VALUES (:user_id, :nip, :full_name)');
            $this->db->bind('user_id', $user_id);
            $this->db->bind('nip', $data['nip']);
            $this->db->bind('full_name', $data['full_name']);
            return $this->db->execute();
        }
        return false;
    }

    public function addAdmin($data)
    {
        // Validate Username
        $this->validateUsername($data['username']);

        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->query('INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, 1)'); // 1 = Admin
        $this->db->bind('username', $data['username']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $password);
        return $this->db->execute();
    }

    public function addStudent($data)
    {
        // Validate Username
        $this->validateUsername($data['username']);

        // 1. Insert User
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->query('INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, 3)'); // 3 = Student
        $this->db->bind('username', $data['username']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $password);
        
        if($this->db->execute()) {
            // Get inserted ID
            $this->db->query('SELECT id FROM users WHERE username = :username');
            $this->db->bind('username', $data['username']);
            $user = $this->db->single();
            $user_id = $user['id'];

            // 2. Insert Profile
            $query = 'INSERT INTO student_profiles 
                (user_id, nis, nisn, full_name, gender, place_of_birth, date_of_birth, religion, citizenship, address, province, city, district, postal_code, phone, entry_date, graduation_date) 
                VALUES 
                (:user_id, :nis, :nisn, :full_name, :gender, :place_of_birth, :date_of_birth, :religion, :citizenship, :address, :province, :city, :district, :postal_code, :phone, :entry_date, :graduation_date)';
            
            $this->db->query($query);
            $this->db->bind('user_id', $user_id);
            $this->db->bind('nis', $data['nis']);
            $this->db->bind('nisn', $data['nisn']);
            $this->db->bind('full_name', $data['full_name']);
            $this->db->bind('gender', !empty($data['gender']) ? $data['gender'] : null);
            $this->db->bind('place_of_birth', !empty($data['place_of_birth']) ? $data['place_of_birth'] : null);
            $this->db->bind('date_of_birth', !empty($data['date_of_birth']) ? $data['date_of_birth'] : null);
            $this->db->bind('religion', !empty($data['religion']) ? $data['religion'] : null);
            $this->db->bind('citizenship', !empty($data['citizenship']) ? $data['citizenship'] : 'Indonesia');
            $this->db->bind('address', !empty($data['address']) ? $data['address'] : null);
            $this->db->bind('province', !empty($data['province']) ? $data['province'] : null);
            $this->db->bind('city', !empty($data['city']) ? $data['city'] : null);
            $this->db->bind('district', !empty($data['district']) ? $data['district'] : null);
            $this->db->bind('postal_code', !empty($data['postal_code']) ? $data['postal_code'] : null);
            $this->db->bind('phone', !empty($data['phone']) ? $data['phone'] : null);
            $this->db->bind('entry_date', !empty($data['entry_date']) ? $data['entry_date'] : null);
            $this->db->bind('graduation_date', !empty($data['graduation_date']) ? $data['graduation_date'] : null);
            return $this->db->execute();
        }
        return false;
    }

    // Temporary method to seed admin
    public function seedAdmin()
    {
        // Check if admin exists
        if($this->getUserByUsername('admin')) return false;

        try {
            $password = password_hash('admin123', PASSWORD_DEFAULT);
            
            $this->db->query('INSERT INTO ' . $this->table . ' (username, email, password, role_id) VALUES (:username, :email, :password, :role_id)');
            $this->db->bind('username', 'admin');
            $this->db->bind('email', 'admin@school.com');
            $this->db->bind('password', $password);
            $this->db->bind('role_id', 1); // 1 is Admin
            
            return $this->db->execute();
        } catch (PDOException $e) {
            return false; // Ignore if duplicate
        }
    }

    private function parseDuplicateError($message)
    {
        if (strpos($message, 'users.username') !== false) {
            throw new Exception("Username sudah digunakan", 409);
        }
        if (strpos($message, 'users.email') !== false) {
            throw new Exception("Email sudah terdaftar", 409);
        }
        throw new Exception("Terjadi kesalahan duplikasi data", 409);
    }

    public function updateAdmin($data)
    {
        try {
            $this->db->query("UPDATE " . $this->table . " SET username = :username, email = :email WHERE id = :id");
            $this->db->bind('username', $data['username']);
            $this->db->bind('email', $data['email']);
            $this->db->bind('id', $data['id']);
            return $this->db->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->parseDuplicateError($e->getMessage());
            }
            return false;
        }
    }

    // CRUD Methods
    public function getUserById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function deleteUser($id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function toggleActive($id)
    {
        // Get current status
        $user = $this->getUserById($id);
        $newStatus = $user['is_active'] ? 0 : 1;

        $this->db->query("UPDATE " . $this->table . " SET is_active = :status WHERE id = :id");
        $this->db->bind('status', $newStatus);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function toggleGraduation($user_id)
    {
        // Get current graduation status
        $this->db->query("SELECT is_graduated FROM student_profiles WHERE user_id = :user_id");
        $this->db->bind('user_id', $user_id);
        $profile = $this->db->single();

        if ($profile) {
            $newStatus = $profile['is_graduated'] ? 0 : 1;
            $this->db->query("UPDATE student_profiles SET is_graduated = :status WHERE user_id = :user_id");
            $this->db->bind('status', $newStatus);
            $this->db->bind('user_id', $user_id);
            return $this->db->execute();
        }
        return false;
    }

    public function resetPassword($id, $newPassword)
    {
        $password = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->db->query("UPDATE " . $this->table . " SET password = :password WHERE id = :id");
        $this->db->bind('password', $password);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function updateTeacher($data)
    {
        try {
            $this->db->beginTransaction();

            // Log attempt
            $logData = json_encode($data);
            $this->logActivity($data['id'], 'update_teacher_attempt', 'Mencoba update data guru. Data: ' . $logData);

            // Update User table
            $query = "UPDATE " . $this->table . " SET username = :username, email = :email";
            if (isset($data['is_active'])) {
                $query .= ", is_active = :is_active";
            }
            $query .= " WHERE id = :id";
            
            $this->db->query($query);
            $this->db->bind('username', $data['username']);
            $this->db->bind('email', $data['email']);
            if (isset($data['is_active'])) {
                $this->db->bind('is_active', $data['is_active']);
            }
            $this->db->bind('id', $data['id']);
            
            $this->db->execute();

            // Update Profile
            $query = "UPDATE teacher_profiles SET 
                full_name = :full_name, 
                nip = :nip,
                nuptk = :nuptk,
                front_title = :front_title,
                back_title = :back_title,
                gender = :gender,
                place_of_birth = :place_of_birth,
                date_of_birth = :date_of_birth,
                religion = :religion,
                marital_status = :marital_status,
                employment_status = :employment_status,
                position = :position,
                subjects = :subjects,
                start_teaching_date = :start_teaching_date,
                teaching_hours_per_week = :teaching_hours_per_week,
                status_detail = :status_detail,
                last_education = :last_education,
                study_program = :study_program,
                university = :university,
                graduation_year = :graduation_year,
                is_certified = :is_certified,
                certificate_number = :certificate_number
                WHERE user_id = :id";

            $this->db->query($query);
            
            // Identity
            $this->db->bind('full_name', $data['full_name']);
            $this->db->bind('nip', !empty($data['nip']) ? $data['nip'] : null);
            $this->db->bind('nuptk', !empty($data['nuptk']) ? $data['nuptk'] : null);
            $this->db->bind('front_title', !empty($data['front_title']) ? $data['front_title'] : null);
            $this->db->bind('back_title', !empty($data['back_title']) ? $data['back_title'] : null);
            $this->db->bind('gender', !empty($data['gender']) ? $data['gender'] : null);
            $this->db->bind('place_of_birth', !empty($data['place_of_birth']) ? $data['place_of_birth'] : null);
            $this->db->bind('date_of_birth', !empty($data['date_of_birth']) ? $data['date_of_birth'] : null);
            $this->db->bind('religion', !empty($data['religion']) ? $data['religion'] : null);
            $this->db->bind('marital_status', !empty($data['marital_status']) ? $data['marital_status'] : null);
            
            // Employment
            $this->db->bind('employment_status', !empty($data['employment_status']) ? $data['employment_status'] : null);
            $this->db->bind('position', !empty($data['position']) ? $data['position'] : null);
            $this->db->bind('subjects', !empty($data['subjects']) ? $data['subjects'] : null);
            $this->db->bind('start_teaching_date', !empty($data['start_teaching_date']) ? $data['start_teaching_date'] : null);
            $this->db->bind('teaching_hours_per_week', !empty($data['teaching_hours_per_week']) ? $data['teaching_hours_per_week'] : null);
            $this->db->bind('status_detail', !empty($data['status_detail']) ? $data['status_detail'] : 'Aktif');
            
            // Academic
            $this->db->bind('last_education', !empty($data['last_education']) ? $data['last_education'] : null);
            $this->db->bind('study_program', !empty($data['study_program']) ? $data['study_program'] : null);
            $this->db->bind('university', !empty($data['university']) ? $data['university'] : null);
            $this->db->bind('graduation_year', !empty($data['graduation_year']) ? $data['graduation_year'] : null);
            $this->db->bind('is_certified', isset($data['is_certified']) ? $data['is_certified'] : 0);
            $this->db->bind('certificate_number', !empty($data['certificate_number']) ? $data['certificate_number'] : null);
            
            $this->db->bind('id', $data['id']);
            
            $this->db->execute();

            $this->logActivity($data['id'], 'update_teacher_success', 'Berhasil update data guru: ' . $data['full_name']);
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logActivity($data['id'], 'update_teacher_failed', 'Gagal update data guru. Error: ' . $e->getMessage());
            
            if ($e->getCode() == 23000) {
                $this->parseDuplicateError($e->getMessage());
            }
            return false;
        }
    }

    public function updateStudent($data)
    {
        try {
            $this->db->beginTransaction();

            // Log attempt
            $logData = json_encode($data);
            $this->logActivity($data['id'], 'update_student_attempt', 'Mencoba update data siswa. Data: ' . $logData);

            // Update User table
            $query = "UPDATE " . $this->table . " SET username = :username, email = :email";
            if (isset($data['is_active'])) {
                $query .= ", is_active = :is_active";
            }
            $query .= " WHERE id = :id";

            $this->db->query($query);
            $this->db->bind('username', $data['username']);
            $this->db->bind('email', $data['email']);
            if (isset($data['is_active'])) {
                $this->db->bind('is_active', $data['is_active']);
            }
            $this->db->bind('id', $data['id']);
            
            $this->db->execute();

            // Update Profile
            $query = "UPDATE student_profiles SET 
                full_name = :full_name, 
                nis = :nis, 
                nisn = :nisn, 
                gender = :gender, 
                place_of_birth = :place_of_birth, 
                date_of_birth = :date_of_birth, 
                religion = :religion, 
                citizenship = :citizenship, 
                address = :address, 
                province = :province, 
                city = :city, 
                district = :district, 
                postal_code = :postal_code, 
                phone = :phone, 
                parent_name = :parent_name,
                parent_relationship = :parent_relationship,
                parent_phone = :parent_phone,
                parent_address = :parent_address,
                entry_date = :entry_date, 
                graduation_date = :graduation_date 
                WHERE user_id = :id";
            
            $this->db->query($query);
            $this->db->bind('full_name', $data['full_name']);
            $this->db->bind('nis', $data['nis']);
            $this->db->bind('nisn', $data['nisn']);
            $this->db->bind('gender', !empty($data['gender']) ? $data['gender'] : null);
            $this->db->bind('place_of_birth', !empty($data['place_of_birth']) ? $data['place_of_birth'] : null);
            $this->db->bind('date_of_birth', !empty($data['date_of_birth']) ? $data['date_of_birth'] : null);
            $this->db->bind('religion', !empty($data['religion']) ? $data['religion'] : null);
            $this->db->bind('citizenship', !empty($data['citizenship']) ? $data['citizenship'] : 'Indonesia');
            $this->db->bind('address', !empty($data['address']) ? $data['address'] : null);
            $this->db->bind('province', !empty($data['province']) ? $data['province'] : null);
            $this->db->bind('city', !empty($data['city']) ? $data['city'] : null);
            $this->db->bind('district', !empty($data['district']) ? $data['district'] : null);
            $this->db->bind('postal_code', !empty($data['postal_code']) ? $data['postal_code'] : null);
            $this->db->bind('phone', !empty($data['phone']) ? $data['phone'] : null);
            $this->db->bind('parent_name', !empty($data['parent_name']) ? $data['parent_name'] : null);
            $this->db->bind('parent_relationship', !empty($data['parent_relationship']) ? $data['parent_relationship'] : null);
            $this->db->bind('parent_phone', !empty($data['parent_phone']) ? $data['parent_phone'] : null);
            $this->db->bind('parent_address', !empty($data['parent_address']) ? $data['parent_address'] : null);
            $this->db->bind('entry_date', !empty($data['entry_date']) ? $data['entry_date'] : null);
            $this->db->bind('graduation_date', !empty($data['graduation_date']) ? $data['graduation_date'] : null);
            $this->db->bind('id', $data['id']);
            
            $this->db->execute();

            $this->logActivity($data['id'], 'update_student_success', 'Berhasil update data siswa: ' . $data['full_name']);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logActivity($data['id'], 'update_student_failed', 'Gagal update data siswa. Error: ' . $e->getMessage());
            
            if ($e->getCode() == 23000) {
                $this->parseDuplicateError($e->getMessage());
            }
            return false;
        }
    }

    public function getTeacherById($id)
    {
        $this->db->query("SELECT u.id, u.username, u.email, u.is_active, 
            tp.full_name, tp.nip, tp.nuptk, tp.front_title, tp.back_title, 
            tp.gender, tp.place_of_birth, tp.date_of_birth, tp.religion, tp.marital_status,
            tp.employment_status, tp.position, tp.subjects, tp.start_teaching_date, 
            tp.teaching_hours_per_week, tp.status_detail, 
            tp.last_education, tp.study_program, tp.university, tp.graduation_year, 
            tp.is_certified, tp.certificate_number
            FROM users u JOIN teacher_profiles tp ON u.id = tp.user_id WHERE u.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getStudentById($id)
    {
        $this->db->query("SELECT u.*, 
            sp.full_name, sp.nis, sp.nisn, 
            sp.gender, sp.place_of_birth, sp.date_of_birth, sp.religion, sp.citizenship, sp.photo,
            sp.address, sp.province, sp.city, sp.district, sp.postal_code, sp.phone,
            sp.parent_name, sp.parent_relationship, sp.parent_phone, sp.parent_address,
            sp.entry_date, sp.graduation_date, sp.is_graduated 
            FROM users u JOIN student_profiles sp ON u.id = sp.user_id WHERE u.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function countByRole($role_id)
    {
        $this->db->query("SELECT COUNT(*) as total FROM " . $this->table . " WHERE role_id = :role_id");
        $this->db->bind('role_id', $role_id);
        $result = $this->db->single();
        return $result['total'];
    }

    public function countStudentByStatus($is_graduated)
    {
        $this->db->query("SELECT COUNT(*) as total FROM users u JOIN student_profiles sp ON u.id = sp.user_id WHERE u.role_id = 3 AND sp.is_graduated = :is_graduated");
        $this->db->bind('is_graduated', $is_graduated);
        $result = $this->db->single();
        return $result['total'];
    }

    public function getStudentGrowthStats()
    {
        // Count students per academic year via class enrollments
        $query = "SELECT ay.name as year, COUNT(DISTINCT cs.student_id) as total 
                  FROM academic_years ay
                  LEFT JOIN classes c ON ay.id = c.academic_year_id
                  LEFT JOIN class_students cs ON c.id = cs.class_id
                  GROUP BY ay.id, ay.name
                  ORDER BY ay.name ASC";
        
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function addParent($data)
    {
        try {
            // Validate Username
            $this->validateUsername($data['username']);

            // 1. Insert User
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->db->query('INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, 4)'); // 4 = Parent
            $this->db->bind('username', $data['username']);
            $this->db->bind('email', $data['email']);
            $this->db->bind('password', $password);
            
            if($this->db->execute()) {
                // Get inserted ID
                $this->db->query('SELECT id FROM users WHERE username = :username');
                $this->db->bind('username', $data['username']);
                $user = $this->db->single();
                $user_id = $user['id'];

                // 2. Insert Profile
                $this->db->query('INSERT INTO parent_profiles (user_id, full_name, phone) VALUES (:user_id, :full_name, :phone)');
                $this->db->bind('user_id', $user_id);
                $this->db->bind('full_name', $data['full_name']);
                $this->db->bind('phone', $data['phone']);
                return $this->db->execute();
            }
            return false;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->parseDuplicateError($e->getMessage());
            }
            return false;
        }
    }

    public function updateParent($data)
    {
        try {
            // Update User table
            $this->db->query("UPDATE " . $this->table . " SET username = :username, email = :email WHERE id = :id");
            $this->db->bind('username', $data['username']);
            $this->db->bind('email', $data['email']);
            $this->db->bind('id', $data['id']);
            
            $this->db->execute();

            // Update Profile
            $this->db->query("UPDATE parent_profiles SET full_name = :full_name, phone = :phone WHERE user_id = :id");
            $this->db->bind('full_name', $data['full_name']);
            $this->db->bind('phone', $data['phone']);
            $this->db->bind('id', $data['id']);
            
            return $this->db->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->parseDuplicateError($e->getMessage());
            }
            return false;
        }
    }

    public function updateTeachingHours($user_id, $hours)
    {
        $this->db->query("UPDATE teacher_profiles SET teaching_hours_per_week = :hours WHERE user_id = :user_id");
        $this->db->bind('hours', $hours);
        $this->db->bind('user_id', $user_id);
        return $this->db->execute();
    }

    public function logActivity($user_id, $action, $description)
    {
        $this->db->query("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (:user_id, :action, :description, :ip_address)");
        $this->db->bind('user_id', $user_id);
        $this->db->bind('action', $action);
        $this->db->bind('description', $description);
        $this->db->bind('ip_address', $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
        return $this->db->execute();
    }

    public function getParentById($id)
    {
        $this->db->query("SELECT u.*, pp.full_name, pp.phone FROM users u JOIN parent_profiles pp ON u.id = pp.user_id WHERE u.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
}
