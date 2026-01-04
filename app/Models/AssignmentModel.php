<?php

class AssignmentModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function assignStudentToClass($class_id, $student_id)
    {
        // Check if already exists to prevent duplicates
        $this->db->query("SELECT COUNT(*) as count FROM class_students WHERE class_id = :class_id AND student_id = :student_id");
        $this->db->bind('class_id', $class_id);
        $this->db->bind('student_id', $student_id);
        $result = $this->db->single();

        if ($result && $result['count'] > 0) {
            // Throw exception to be caught by controller
            throw new PDOException("Duplicate entry", 23000);
        }

        $this->db->query("INSERT INTO class_students (class_id, student_id) VALUES (:class_id, :student_id)");
        $this->db->bind('class_id', $class_id);
        $this->db->bind('student_id', $student_id);
        return $this->db->execute();
    }

    public function getStudentsInClass($class_id, $keyword = null)
    {
        $query = "SELECT cs.*, sp.full_name, sp.nis FROM class_students cs JOIN student_profiles sp ON cs.student_id = sp.user_id WHERE cs.class_id = :class_id";
        
        if ($keyword) {
            $query .= " AND (sp.full_name LIKE :keyword OR sp.nis LIKE :keyword)";
        }

        $this->db->query($query);
        $this->db->bind('class_id', $class_id);
        
        if ($keyword) {
            $this->db->bind('keyword', "%$keyword%");
        }

        return $this->db->resultSet();
    }

    public function assignSubjectToClass($class_id, $subject_id, $teacher_id)
    {
        try {
            $this->db->query("INSERT INTO class_subjects (class_id, subject_id, teacher_id) VALUES (:class_id, :subject_id, :teacher_id)");
            $this->db->bind('class_id', $class_id);
            $this->db->bind('subject_id', $subject_id);
            $this->db->bind('teacher_id', $teacher_id);
            return $this->db->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->parseDuplicateError($e->getMessage());
            }
            return false;
        }
    }

    public function getSubjectsInClass($class_id)
    {
        $this->db->query("SELECT cs.*, s.name as subject_name, 
                          CONCAT(COALESCE(CONCAT(tp.front_title, ' '), ''), tp.full_name, COALESCE(CONCAT(', ', tp.back_title), '')) as teacher_name 
                          FROM class_subjects cs 
                          JOIN subjects s ON cs.subject_id = s.id 
                          JOIN teacher_profiles tp ON cs.teacher_id = tp.user_id 
                          WHERE cs.class_id = :class_id");
        $this->db->bind('class_id', $class_id);
        return $this->db->resultSet();
    }

    public function getClassSubjectById($id)
    {
        $this->db->query("SELECT cs.*, c.name as class_name, c.level, c.major, s.name as subject_name 
                          FROM class_subjects cs 
                          JOIN classes c ON cs.class_id = c.id
                          JOIN subjects s ON cs.subject_id = s.id
                          WHERE cs.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getClassesByTeacher($teacher_id)
    {
        $this->db->query("SELECT cs.*, c.name as class_name, c.level, c.major, s.name as subject_name 
                          FROM class_subjects cs 
                          JOIN classes c ON cs.class_id = c.id
                          JOIN subjects s ON cs.subject_id = s.id
                          WHERE cs.teacher_id = :teacher_id");
        $this->db->bind('teacher_id', $teacher_id);
        return $this->db->resultSet();
    }

    public function getClassByStudent($student_id)
    {
        $this->db->query("SELECT c.* FROM classes c 
                          JOIN class_students cs ON c.id = cs.class_id 
                          WHERE cs.student_id = :student_id");
        $this->db->bind('student_id', $student_id);
        return $this->db->single();
    }

    public function removeStudentFromClass($id)
    {
        $this->db->query("DELETE FROM class_students WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function removeSubjectFromClass($id)
    {
        try {
            $this->db->beginTransaction();

            // 1. Delete Teaching Journals
            $this->db->query("DELETE FROM teaching_journals WHERE class_subject_id = :id");
            $this->db->bind('id', $id);
            $this->db->execute();

            // 2. Delete Attendances
            $this->db->query("DELETE FROM attendances WHERE class_subject_id = :id");
            $this->db->bind('id', $id);
            $this->db->execute();

            // 3. Delete Grades
            $this->db->query("DELETE FROM grades WHERE class_subject_id = :id");
            $this->db->bind('id', $id);
            $this->db->execute();

            // 4. Delete Schedules
            $this->db->query("DELETE FROM schedules WHERE class_subject_id = :id");
            $this->db->bind('id', $id);
            $this->db->execute();

            // 5. Delete Class Subject
            $this->db->query("DELETE FROM class_subjects WHERE id = :id");
            $this->db->bind('id', $id);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
