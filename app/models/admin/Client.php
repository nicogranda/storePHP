<?php
namespace App\Models\Admin;

use App\Libraries\Admin\Model;

class Client extends Model
{
    protected $table = 'clients';

    public function __construct()
    {
        parent::__construct();
        
    }

    // Método para obtener un cliente por su id
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $id);  // "i" para entero (id es un entero)
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    //     public function getByAlias($alias) {
    //     $sql = "SELECT * FROM clients WHERE alias = ?";
    //     if ($stmt = $this->mysqli->prepare($sql)) {
    //         $stmt->bind_param("s", $alias);
    //         $stmt->execute();
    //         $result = $stmt->get_result();
    //         return $result->fetch_assoc();
    //     }
    //     return null;
    // }
    public function updateOperationByBusinessId($id, $clientId) {
    $sql = "UPDATE {$this->table} SET client_id = ?, updated_at = NOW() WHERE id = ?";
    
    $stmt = $this->mysqli->prepare($sql);
    
    if (!$stmt) {
        return false; // O manejar el error de preparación
    }

    $stmt->bind_param('ii', $clientId, $id); // Ambos son enteros (i)
    
    return $stmt->execute();
}

}
