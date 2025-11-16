<?php
namespace App\Models\Admin;

use App\Libraries\Admin\Model;

class Quote extends Model
{
    protected $table = 'quotes';

    // Retorna el total de cotizaciones (opcionalmente filtrado por búsqueda)
    public function getTotalQuotes($search = '') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (!empty($search)) {
            $sql .= " WHERE id LIKE ?";
            $stmt = $this->mysqli->prepare($sql);
            $like = "%{$search}%";
            $stmt->bind_param("s", $like);
        } else {
            $stmt = $this->mysqli->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    // Retorna las cotizaciones paginadas
    public function getAllPaginated($limit, $offset) {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC LIMIT ? OFFSET ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
