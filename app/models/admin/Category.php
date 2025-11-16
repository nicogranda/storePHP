<?php
namespace App\Models\Admin;

use App\Models\Model;

class Category extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->setTable('categories');
    }

    public function create($data)
    {
        $stmt = $this->mysqli->prepare("
            INSERT INTO {$this->table} (name, language, description) 
            VALUES (?, ?, ?)
        ");
        $description = $data['description'] ?? '';
        $stmt->bind_param("sss", $data['name'], $data['language'], $description);
        $stmt->execute();
        return $this->mysqli->insert_id;
    }

    public function getAll()
    {
        // Traer todas las categorías junto con su primera imagen de category_media
        $sql = "
            SELECT c.id, c.name, c.description, cm.file_path AS image
            FROM {$this->table} c
            LEFT JOIN category_media cm
                ON cm.category_id = c.id AND cm.is_primary = 1
            ORDER BY c.name ASC
        ";
        $result = $this->mysqli->query($sql);
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'images' => $row['image'] ? [$row['image']] : [] // array para compatibilidad con vista
            ];
        }
        return $categories;
    }

    public function getCategoryById($id)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
