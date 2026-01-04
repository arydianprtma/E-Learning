<?php

class GradeModel {
    private $table = 'grades';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getByClassSubject($class_subject_id)
    {
        $this->db->query("SELECT g.*, s.full_name as student_name, s.nis 
                          FROM " . $this->table . " g 
                          JOIN users u ON g.student_id = u.id
                          JOIN student_profiles s ON u.id = s.user_id
                          WHERE g.class_subject_id = :class_subject_id
                          ORDER BY s.full_name ASC");
        $this->db->bind('class_subject_id', $class_subject_id);
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getGradesByStudent($student_id)
    {
        $this->db->query("SELECT g.*, sub.name as subject_name, 
                          CONCAT(COALESCE(CONCAT(tp.front_title, ' '), ''), tp.full_name, COALESCE(CONCAT(', ', tp.back_title), '')) as teacher_name 
                          FROM " . $this->table . " g
                          JOIN class_subjects cs ON g.class_subject_id = cs.id
                          JOIN subjects sub ON cs.subject_id = sub.id
                          JOIN teacher_profiles tp ON cs.teacher_id = tp.user_id
                          WHERE g.student_id = :student_id
                          ORDER BY sub.name ASC, g.type ASC");
        $this->db->bind('student_id', $student_id);
        return $this->db->resultSet();
    }

    public function add($data)
    {
        $this->db->query("INSERT INTO " . $this->table . " (class_subject_id, student_id, type, score, description) 
                          VALUES (:class_subject_id, :student_id, :type, :score, :description)");
        $this->db->bind('class_subject_id', $data['class_subject_id']);
        $this->db->bind('student_id', $data['student_id']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('score', $data['score']);
        $this->db->bind('description', $data['description']);
        return $this->db->execute();
    }

    public function update($data)
    {
        $this->db->query("UPDATE " . $this->table . " SET score = :score, description = :description WHERE id = :id");
        $this->db->bind('score', $data['score']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('id', $data['id']);
        return $this->db->execute();
    }
    
    public function delete($id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function upsert($data)
    {
        // Check if grade exists
        $this->db->query("SELECT id FROM " . $this->table . " WHERE class_subject_id = :class_subject_id AND student_id = :student_id AND type = :type");
        $this->db->bind('class_subject_id', $data['class_subject_id']);
        $this->db->bind('student_id', $data['student_id']);
        $this->db->bind('type', $data['type']);
        $existing = $this->db->single();

        if ($existing) {
            // Update
            // If score is empty, maybe we should delete it? Or just set to 0/null? 
            // For now, let's update it. If user clears input, it might send empty string.
            
            if ($data['score'] === '' || $data['score'] === null) {
                // Optional: Delete if empty? Or keep as null?
                // Let's assume empty means delete or 0. User input "number" usually allows empty.
                // Let's delete if empty to keep clean
                return $this->delete($existing['id']);
            }

            $this->db->query("UPDATE " . $this->table . " SET score = :score WHERE id = :id");
            $this->db->bind('score', $data['score']);
            $this->db->bind('id', $existing['id']);
            return $this->db->execute();
        } else {
            // Insert
            if ($data['score'] !== '' && $data['score'] !== null) {
                $this->db->query("INSERT INTO " . $this->table . " (class_subject_id, student_id, type, score) 
                                  VALUES (:class_subject_id, :student_id, :type, :score)");
                $this->db->bind('class_subject_id', $data['class_subject_id']);
                $this->db->bind('student_id', $data['student_id']);
                $this->db->bind('type', $data['type']);
                $this->db->bind('score', $data['score']);
                return $this->db->execute();
            }
        }
        return 0;
    }

    public function getGradesReport($class_id = null, $subject_id = null)
    {
        $sql = "SELECT g.*, 
                       sp.full_name as student_name, sp.nis,
                       c.name as class_name,
                       sub.name as subject_name
                FROM " . $this->table . " g 
                JOIN users u ON g.student_id = u.id
                JOIN student_profiles sp ON u.id = sp.user_id
                JOIN class_subjects cs ON g.class_subject_id = cs.id
                JOIN classes c ON cs.class_id = c.id
                JOIN subjects sub ON cs.subject_id = sub.id
                WHERE 1=1";
        
        if ($class_id) {
            $sql .= " AND c.id = :class_id";
        }
        
        if ($subject_id) {
            $sql .= " AND sub.id = :subject_id";
        }
        
        $sql .= " ORDER BY c.name ASC, sub.name ASC, sp.full_name ASC";
        
        $this->db->query($sql);
        
        if ($class_id) {
            $this->db->bind('class_id', $class_id);
        }
        
        if ($subject_id) {
            $this->db->bind('subject_id', $subject_id);
        }
        
        $results = $this->db->resultSet();
        
        // Pivot data
        $report = [];
        foreach ($results as $row) {
            $key = $row['student_id'] . '-' . $row['class_subject_id'];
            
            if (!isset($report[$key])) {
                $report[$key] = [
                    'student_name' => $row['student_name'],
                    'nis' => $row['nis'],
                    'class_name' => $row['class_name'],
                    'subject_name' => $row['subject_name'],
                    'grades' => []
                ];
            }
            
            $report[$key]['grades'][$row['type']] = $row['score'];
        }
        
        return array_values($report);
    }
}
