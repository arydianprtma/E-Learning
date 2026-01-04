<?php

class ClassModel {
    private $table = 'classes';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll($keyword = null, $limit = 0, $offset = 0)
    {
        $query = 'SELECT c.*, ay.name as academic_year_name, ay.semester, 
                 (SELECT COUNT(*) FROM class_students cs WHERE cs.class_id = c.id) as student_count 
                 FROM ' . $this->table . ' c 
                 JOIN academic_years ay ON c.academic_year_id = ay.id';
        
        if ($keyword) {
            $query .= " WHERE c.name LIKE :keyword OR c.level LIKE :keyword OR c.major LIKE :keyword";
        }

        $query .= ' ORDER BY c.level ASC, c.name ASC';

        if ($limit > 0) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $this->db->query($query);
        
        if ($keyword) {
            $this->db->bind('keyword', "%$keyword%");
        }

        if ($limit > 0) {
            $this->db->bind('limit', $limit);
            $this->db->bind('offset', $offset);
        }

        return $this->db->resultSet();
    }

    public function countAll($keyword = null)
    {
        $query = 'SELECT COUNT(*) as total FROM ' . $this->table . ' c JOIN academic_years ay ON c.academic_year_id = ay.id';
        
        if ($keyword) {
            $query .= " WHERE c.name LIKE :keyword OR c.level LIKE :keyword OR c.major LIKE :keyword";
        }

        $this->db->query($query);
        
        if ($keyword) {
            $this->db->bind('keyword', "%$keyword%");
        }

        $result = $this->db->single();
        return $result['total'];
    }

    private function parseDuplicateError($message)
    {
        if (strpos($message, 'classes.name') !== false) {
            throw new Exception("Nama kelas sudah ada", 409);
        }
        throw new Exception("Terjadi kesalahan duplikasi data", 409);
    }

    public function add($data)
    {
        try {
            $query = "INSERT INTO " . $this->table . " (name, level, major, academic_year_id) VALUES (:name, :level, :major, :academic_year_id)";
            $this->db->query($query);
            $this->db->bind('name', $data['name']);
            $this->db->bind('level', $data['level']);
            $this->db->bind('major', $data['major']);
            $this->db->bind('academic_year_id', $data['academic_year_id']);
            return $this->db->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->parseDuplicateError($e->getMessage());
            }
            return false;
        }
    }

    public function getById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function update($data)
    {
        try {
            $query = "UPDATE " . $this->table . " SET name = :name, level = :level, major = :major, academic_year_id = :academic_year_id WHERE id = :id";
            $this->db->query($query);
            $this->db->bind('name', $data['name']);
            $this->db->bind('level', $data['level']);
            $this->db->bind('major', $data['major']);
            $this->db->bind('academic_year_id', $data['academic_year_id']);
            $this->db->bind('id', $data['id']);
            return $this->db->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->parseDuplicateError($e->getMessage());
            }
            return false;
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }
}
