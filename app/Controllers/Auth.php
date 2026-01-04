<?php

class Auth extends Controller {
    public function index()
    {
        // Seed admin if not exists (Auto-setup for dev convenience)
        $userModel = $this->model('UserModel');
        $userModel->seedAdmin();

        $data = [
            'title' => 'Login - E-Learning',
            'error' => isset($_SESSION['error']) ? $_SESSION['error'] : ''
        ];
        
        // Clear error after displaying
        if(isset($_SESSION['error'])) unset($_SESSION['error']);

        // Check if already logged in
        if(isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $this->view('Auth/login', $data);
    }

    public function login()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByUsername($username);

            if($user) {
                if(password_verify($password, $user['password'])) {
                    if($user['is_active'] == 1) {
                        // Set Session
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role_id'] = $user['role_id'];
                        
                        // Get Role Name
                        $role = $userModel->getUserRole($user['role_id']);
                        $_SESSION['role'] = $role;

                        // Update Last Login
                        $userModel->updateLastLogin($user['id']);

                        header('Location: ' . BASEURL . '/dashboard');
                        exit;
                    } else {
                        $_SESSION['error'] = 'Akun anda dinonaktifkan. Hubungi TU.';
                    }
                } else {
                    // echo "Password Verify Failed. Input: $password. Hash: " . $user['password']; die();
                    $_SESSION['error'] = 'Password salah.';
                }
            } else {
                // echo "User Not Found: $username"; die();
                $_SESSION['error'] = 'Username tidak ditemukan.';
            }

            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASEURL . '/auth');
        exit;
    }
}
