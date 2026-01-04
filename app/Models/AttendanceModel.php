<?php

class AttendanceModel {
    private $table = 'attendances';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getByClassSubjectAndDate($class_subject_id, $date)
    {
        $this->db->query("SELECT a.*, s.full_name as student_name, s.nis 
                          FROM " . $this->table . " a 
                          JOIN users u ON a.student_id = u.id
                          JOIN student_profiles s ON u.id = s.user_id
                          WHERE a.class_subject_id = :class_subject_id AND a.date = :date
                          ORDER BY s.full_name ASC");
        $this->db->bind('class_subject_id', $class_subject_id);
        $this->db->bind('date', $date);
        return $this->db->resultSet();
    }

    public function getAttendanceSummaryByClass($class_subject_id)
    {
        $this->db->query("SELECT 
                            student_id, 
                            SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir,
                            SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) as total_sakit,
                            SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) as total_izin,
                            SUM(CASE WHEN status = 'Alpa' THEN 1 ELSE 0 END) as total_alpa
                          FROM " . $this->table . " 
                          WHERE class_subject_id = :class_subject_id
                          GROUP BY student_id");
        $this->db->bind('class_subject_id', $class_subject_id);
        return $this->db->resultSet();
    }

    public function addOrUpdate($data)
    {
        // Check if exists
        $this->db->query("SELECT id FROM " . $this->table . " WHERE class_subject_id = :class_subject_id AND student_id = :student_id AND date = :date");
        $this->db->bind('class_subject_id', $data['class_subject_id']);
        $this->db->bind('student_id', $data['student_id']);
        $this->db->bind('date', $data['date']);
        $exists = $this->db->single();

        if ($exists) {
            $this->db->query("UPDATE " . $this->table . " SET status = :status, note = :note WHERE id = :id");
            $this->db->bind('status', $data['status']);
            $this->db->bind('note', $data['note']);
            $this->db->bind('id', $exists['id']);
        } else {
            $this->db->query("INSERT INTO " . $this->table . " (class_subject_id, student_id, date, status, note) 
                              VALUES (:class_subject_id, :student_id, :date, :status, :note)");
            $this->db->bind('class_subject_id', $data['class_subject_id']);
            $this->db->bind('student_id', $data['student_id']);
            $this->db->bind('date', $data['date']);
            $this->db->bind('status', $data['status']);
            $this->db->bind('note', $data['note']);
        }
        return $this->db->execute();
    }
}
