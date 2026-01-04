<?php

class ScheduleModel {
    private $table = 'schedules';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getScheduleForToday($class_subject_id, $dayName)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE class_subject_id = :id AND day = :day");
        $this->db->bind('id', $class_subject_id);
        $this->db->bind('day', $dayName);
        return $this->db->single();
    }

    public function getSchedulesForToday($class_subject_id, $dayName)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE class_subject_id = :id AND day = :day ORDER BY start_time ASC");
        $this->db->bind('id', $class_subject_id);
        $this->db->bind('day', $dayName);
        return $this->db->resultSet();
    }

    public function getRealizedHoursThisWeek($teacher_id) {
        // Updated Logic: Use teaching_journals table for accuracy and permanence
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));

        $this->db->query("SELECT SUM(duration_hours) as total_hours 
                          FROM teaching_journals 
                          WHERE teacher_id = :teacher_id 
                          AND date BETWEEN :start_date AND :end_date");
        $this->db->bind('teacher_id', $teacher_id);
        $this->db->bind('start_date', $startOfWeek);
        $this->db->bind('end_date', $endOfWeek);
        
        $result = $this->db->single();
        return $result && isset($result['total_hours']) ? round($result['total_hours'], 1) : 0;
    }

    // New methods for Teaching Journal
    public function getJournalByClassAndDate($class_subject_id, $date)
    {
        $this->db->query("SELECT * FROM teaching_journals WHERE class_subject_id = :class_subject_id AND date = :date");
        $this->db->bind('class_subject_id', $class_subject_id);
        $this->db->bind('date', $date);
        return $this->db->single();
    }

    public function getJournalBySession($class_subject_id, $date, $start_time, $end_time)
    {
        $this->db->query("SELECT * FROM teaching_journals 
                          WHERE class_subject_id = :class_subject_id 
                          AND date = :date 
                          AND start_time = :start_time 
                          AND end_time = :end_time");
        $this->db->bind('class_subject_id', $class_subject_id);
        $this->db->bind('date', $date);
        $this->db->bind('start_time', $start_time);
        $this->db->bind('end_time', $end_time);
        return $this->db->single();
    }

    public function createTeachingJournal($data)
    {
        // Calculate duration in hours
        $start = strtotime($data['start_time']);
        $end = strtotime($data['end_time']);
        $duration = ($end - $start) / 3600;

        $this->db->query("INSERT INTO teaching_journals (teacher_id, class_subject_id, date, start_time, end_time, duration_hours) 
                          VALUES (:teacher_id, :class_subject_id, :date, :start_time, :end_time, :duration_hours)");
        $this->db->bind('teacher_id', $data['teacher_id']);
        $this->db->bind('class_subject_id', $data['class_subject_id']);
        $this->db->bind('date', $data['date']);
        $this->db->bind('start_time', $data['start_time']);
        $this->db->bind('end_time', $data['end_time']);
        $this->db->bind('duration_hours', $duration);
        
        return $this->db->execute();
    }

    public function add($data)
    {
        $this->db->query("INSERT INTO " . $this->table . " (class_subject_id, day, start_time, end_time) VALUES (:class_subject_id, :day, :start_time, :end_time)");
        $this->db->bind('class_subject_id', $data['class_subject_id']);
        $this->db->bind('day', $data['day']);
        $this->db->bind('start_time', $data['start_time']);
        $this->db->bind('end_time', $data['end_time']);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function getByClassSubjectId($class_subject_id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE class_subject_id = :class_subject_id ORDER BY FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), start_time");
        $this->db->bind('class_subject_id', $class_subject_id);
        return $this->db->resultSet();
    }

    public function getFullScheduleByClass($class_id)
    {
        $this->db->query("SELECT s.*, sub.name as subject_name, 
                          CONCAT(COALESCE(CONCAT(tp.front_title, ' '), ''), tp.full_name, COALESCE(CONCAT(', ', tp.back_title), '')) as teacher_name 
                          FROM " . $this->table . " s
                          JOIN class_subjects cs ON s.class_subject_id = cs.id
                          JOIN subjects sub ON cs.subject_id = sub.id
                          JOIN teacher_profiles tp ON cs.teacher_id = tp.user_id
                          WHERE cs.class_id = :class_id
                          ORDER BY FIELD(s.day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), s.start_time");
        $this->db->bind('class_id', $class_id);
        return $this->db->resultSet();
    }

    public function getScheduleByTeacher($teacher_id)
    {
        $this->db->query("SELECT s.*, sub.name as subject_name, c.name as class_name, c.id as class_id, cs.id as class_subject_id
                          FROM " . $this->table . " s
                          JOIN class_subjects cs ON s.class_subject_id = cs.id
                          JOIN subjects sub ON cs.subject_id = sub.id
                          JOIN classes c ON cs.class_id = c.id
                          WHERE cs.teacher_id = :teacher_id
                          ORDER BY FIELD(s.day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), s.start_time");
        $this->db->bind('teacher_id', $teacher_id);
        return $this->db->resultSet();
    }

    public function getTodayScheduleByTeacher($teacher_id, $day)
    {
        $this->db->query("SELECT s.*, sub.name as subject_name, c.name as class_name, c.id as class_id, cs.id as class_subject_id
                          FROM " . $this->table . " s
                          JOIN class_subjects cs ON s.class_subject_id = cs.id
                          JOIN subjects sub ON cs.subject_id = sub.id
                          JOIN classes c ON cs.class_id = c.id
                          WHERE cs.teacher_id = :teacher_id AND s.day = :day
                          ORDER BY s.start_time");
        $this->db->bind('teacher_id', $teacher_id);
        $this->db->bind('day', $day);
        return $this->db->resultSet();
    }
}
