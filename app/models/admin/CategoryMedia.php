<?php
namespace App\Models\Admin;

use App\Models\Model;

class CategoryMedia extends Model
{
    protected $table = 'category_media';

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (category_id, file_path, file_type, language, is_primary, user_id)
                VALUES (:category_id, :file_path, :file_type, :language, :is_primary, :user_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':file_path', $data['file_path']);
        $stmt->bindParam(':file_type', $data['file_type']);
        $stmt->bindParam(':language', $data['language']);
        $stmt->bindParam(':is_primary', $data['is_primary']);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->execute();
    }
}
