<?php

class AnnouncementModel {
    private $table = 'announcements';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll($limit = 0, $offset = 0)
    {
        $query = "SELECT a.*, u.username as author_name 
                  FROM " . $this->table . " a 
                  JOIN users u ON a.created_by = u.id 
                  ORDER BY a.created_at DESC";
        
        if ($limit > 0) {
            $query .= " LIMIT :limit";
            if ($offset > 0) {
                $query .= " OFFSET :offset";
            }
        }

        $this->db->query($query);
        
        if ($limit > 0) {
            $this->db->bind('limit', $limit);
            if ($offset > 0) {
                $this->db->bind('offset', $offset);
            }
        }

        return $this->db->resultSet();
    }

    public function countAll()
    {
        $this->db->query("SELECT COUNT(*) as total FROM " . $this->table);
        $result = $this->db->single();
        return $result['total'];
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function add($data)
    {
        $query = "INSERT INTO " . $this->table . " (title, content, created_by, created_at) 
                  VALUES (:title, :content, :created_by, NOW())";
        
        $this->db->query($query);
        $this->db->bind('title', $data['title']);
        $this->db->bind('content', $data['content']);
        $this->db->bind('created_by', $_SESSION['user_id']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function update($data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, 
                      content = :content, 
                      updated_at = NOW() 
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('title', $data['title']);
        $this->db->bind('content', $data['content']);
        $this->db->bind('id', $data['id']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount();
    }
}