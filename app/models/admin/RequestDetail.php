<?php
namespace App\Models\Admin;

use App\Libraries\Admin\Model;

class RFQDetail extends Model
{
    protected $table = 'requests_details';
    
    public function store($data)
    {
        foreach ($data as $item) { // Recorremos cada item
            $request_id = $item['request_id'];
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $unit_value = $item['unit_value'];
            $discount = $item['discount'];
            $vat_rate = $item['vat_rate'];
            $note = $item['note'];
            $created_at = $item['created_at'];
            $updated_at = $item['updated_at'];
    
            $stmt = $this->mysqli->prepare(
                "INSERT INTO $this->table 
                (request_id, product_id, quantity, unit_value, discount, vat_rate, note, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
    
            $stmt->bind_param("iisiiisss", 
                $request_id, 
                $product_id, 
                $quantity, 
                $unit_value, 
                $discount, 
                $vat_rate, 
                $note, 
                $created_at, 
                $updated_at
            );
    
            $stmt->execute();
        }
    
        return true; 
    }
    
    public function updateOperationDetailsByOperationId($operationId, $operationDetailData)
    {
        // Comenzamos la transacciónhttps://github.com/ajaxorg/ace/wiki/Default-Keyboard-Shortcuts
        $this->mysqli->begin_transaction();

        try {
            // Instanciamos el modelo OperationsDetails para actualizar los detalles
            $operationDetails = new RFQDetail(); // Ahora la clase está correctamente importada
            
            foreach ($operationDetailData as $detailId => $data) {
                $operationDetails->updateById($detailId, $data); // Usamos el método updateById
            }

            // Si todo va bien, hacemos commit de la transacción
            $this->mysqli->commit();
            return true;
        } catch (Exception $e) {
            // En caso de fallo, revertimos la transacción
            $this->mysqli->rollback();
            return false;
        }
    }

}


?>
