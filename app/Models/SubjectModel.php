<?php

class SubjectModel {
    private $table = 'subjects';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll($keyword = null, $limit = 0, $offset = 0)
    {
        $query = 'SELECT * FROM ' . $this->table;
        
        if ($keyword) {
            $query .= " WHERE name LIKE :keyword OR code LIKE :keyword";
        }

        $query .= ' ORDER BY name ASC';

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
        $query = 'SELECT COUNT(*) as total FROM ' . $this->table;
        
        if ($keyword) {
            $query .= " WHERE name LIKE :keyword OR code LIKE :keyword";
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
        if (strpos($message, 'subjects.code') !== false) {
            throw new Exception("Kode mata pelajaran sudah ada", 409);
        }
        throw new Exception("Terjadi kesalahan duplikasi data", 409);
    }

    public function add($data)
    {
        try {
            $query = "INSERT INTO " . $this->table . " (code, name) VALUES (:code, :name)";
            $this->db->query($query);
            $this->db->bind('code', $data['code']);
            $this->db->bind('name', $data['name']);
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
            $query = "UPDATE " . $this->table . " SET code = :code, name = :name WHERE id = :id";
            $this->db->query($query);
            $this->db->bind('code', $data['code']);
            $this->db->bind('name', $data['name']);
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
