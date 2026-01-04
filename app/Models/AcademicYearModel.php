<?php

class AcademicYearModel {
    private $table = 'academic_years';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll()
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY id DESC');
        return $this->db->resultSet();
    }

    public function getActive()
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE is_active = 1');
        return $this->db->single();
    }

    private function parseDuplicateError($message)
    {
        if (strpos($message, 'academic_years.name') !== false) {
            throw new Exception("Nama tahun akademik sudah ada", 409);
        }
        throw new Exception("Terjadi kesalahan duplikasi data", 409);
    }

    public function add($data)
    {
        try {
            $query = "INSERT INTO " . $this->table . " (name, semester, is_active) VALUES (:name, :semester, :is_active)";
            $this->db->query($query);
            $this->db->bind('name', $data['name']);
            $this->db->bind('semester', $data['semester']);
            $this->db->bind('is_active', isset($data['is_active']) ? 1 : 0);
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
            $query = "UPDATE " . $this->table . " SET name = :name, semester = :semester, is_active = :is_active WHERE id = :id";
            $this->db->query($query);
            $this->db->bind('name', $data['name']);
            $this->db->bind('semester', $data['semester']);
            $this->db->bind('is_active', isset($data['is_active']) ? 1 : 0);
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

    public function setActive($id)
    {
        // Deactivate all first
        $this->db->query("UPDATE " . $this->table . " SET is_active = 0");
        $this->db->execute();

        // Activate selected
        $this->db->query("UPDATE " . $this->table . " SET is_active = 1 WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }
}
