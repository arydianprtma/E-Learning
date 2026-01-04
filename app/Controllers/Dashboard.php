<?php

class Dashboard extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index()
    {
        // Redirect Admin to Admin Dashboard View
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
            header('Location: ' . BASEURL . '/admin');
            exit;
        }

        // Redirect Teacher to Teacher Dashboard
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2) {
            header('Location: ' . BASEURL . '/teacher');
            exit;
        }

        // Redirect Student to Student Dashboard
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3) {
            header('Location: ' . BASEURL . '/student');
            exit;
        }

        $data = [
            'title' => 'Dashboard  E-Learning',
            'role' => $_SESSION['role'],
            'username' => $_SESSION['username']
        ];
        
        $this->view('Dashboard/index', $data);
    }
}
