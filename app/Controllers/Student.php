<?php

class Student extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index()
    {
        $student_id = $_SESSION['user_id'];
        $class = $this->model('AssignmentModel')->getClassByStudent($student_id);

        $data = [
            'title' => 'Dashboard Siswa',
            'role' => $_SESSION['role'],
            'username' => $_SESSION['username'],
            'class' => $class
        ];
        
        // Get today's schedule if class exists
        if ($class) {
             $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
             $today = $days[date('w')];
             
             $fullSchedule = $this->model('ScheduleModel')->getFullScheduleByClass($class['id']);
             $data['today_schedule'] = array_filter($fullSchedule, function($s) use ($today) {
                 return $s['day'] == $today;
             });
        } else {
             $data['today_schedule'] = [];
        }

        $data['announcements'] = $this->model('AnnouncementModel')->getAll(5);

        $this->view('Student/index', $data);
    }

    public function schedule()
    {
        $student_id = $_SESSION['user_id'];
        $class = $this->model('AssignmentModel')->getClassByStudent($student_id);
        
        $data = [
            'title' => 'Jadwal Pelajaran',
            'role' => $_SESSION['role'],
            'username' => $_SESSION['username'],
            'class' => $class
        ];

        if ($class) {
            $data['schedule'] = $this->model('ScheduleModel')->getFullScheduleByClass($class['id']);
        } else {
            $data['schedule'] = [];
        }
        
        $this->view('Student/schedule', $data);
    }

    public function grades()
    {
        $student_id = $_SESSION['user_id'];
        
        $grades = $this->model('GradeModel')->getGradesByStudent($student_id);
        
        // Group grades by Subject
        $subjectGrades = [];
        foreach($grades as $grade) {
            $subjectGrades[$grade['subject_name']]['teacher_name'] = $grade['teacher_name'];
            $subjectGrades[$grade['subject_name']]['grades'][$grade['type']][] = $grade;
        }

        $data = [
            'title' => 'Daftar Nilai',
            'role' => $_SESSION['role'],
            'username' => $_SESSION['username'],
            'subject_grades' => $subjectGrades
        ];
        
        $this->view('Student/grades', $data);
    }
}
