<?php
namespace App\Models\Admin;

use App\Libraries\Admin\Model;
use App\Models\Admin\QuoteDetail; // Agrega esta línea

class QuoteDetail extends Model
{
    protected $table = 'quotes_details';

    public function __construct()
    {
        parent::__construct();
    }
     public function store($data)
    {
        if (!is_array($data)) {
            die("Error: store() esperaba un array, pero recibió: " . gettype($data));
        }
    
        foreach ($data as $item) {
            if (!is_array($item)) {
                die("Error: cada elemento en store() debe ser un array, pero recibió: " . gettype($item));
            }
    
            $quote_id = $item['quote_id'] ?? null;
            $product_id = $item['product_id'] ?? null;
            $quantity = $item['quantity'] ?? null;
            $unit_value = $item['unit_value'] ?? null;
            $discount = $item['discount'] ?? null;
            $vat_rate = $item['vat_rate'] ?? null;
            $note = $item['note'] ?? null;
            $created_at = $item['created_at'] ?? null;
            $updated_at = $item['updated_at'] ?? null;
    
            $stmt = $this->mysqli->prepare(
                "INSERT INTO $this->table 
                (quote_id, product_id, quantity, unit_value, discount, vat_rate, note, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
    
            $stmt->bind_param(
                "iisiiisss",
                $quote_id,
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

    
    public function create($data)
        {
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
    
            $stmt = $this->mysqli->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
    
            // Determina el tipo de los datos y los vincula
            $types = str_repeat("s", count($data)); // Ajustar según los tipos de datos
            $stmt->bind_param($types, ...array_values($data));
    
            if ($stmt->execute()) {
                return $this->mysqli->insert_id; // Retorna el ID del nuevo detalle
            }
    
            return false;
        }
        
    // Método para actualizar los detalles de la operación
    public function updateOperationDetailsByOperationId($operationId, $operationDetailData)
    {
        // Comenzamos la transacciónhttps://github.com/ajaxorg/ace/wiki/Default-Keyboard-Shortcuts
        $this->mysqli->begin_transaction();

        try {
            // Instanciamos el modelo OperationsDetails para actualizar los detalles
            $operationDetails = new QuoteDetail(); // Ahora la clase está correctamente importada
            
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
    
    public function updateById($id, $data) {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $setClause = implode(' = ?, ', $columns) . ' = ?';
        
        $sql = "UPDATE {$this->table} SET $setClause, updated_at = NOW() WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        
        $types = str_repeat('s', count($values)) . 'i'; // Asume strings, ajusta según necesites
        $values[] = $id; // Agrega el ID al final
        
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    public function getAmount($quoteId)
    {
        // Inicializamos las variables
        $total = 0;
        $vat = 0;

        // Obtenemos los detalles de la cotización
        $query = "SELECT * FROM {$this->table} WHERE quote_id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $quoteId); // Suponiendo que el parámetro es un entero
        $stmt->execute();
        $result = $stmt->get_result();

        // Recorremos los detalles de la cotización y calculamos vat_item y balance
        while ($operation_detail = $result->fetch_assoc()) {
            $quantity = (float) $operation_detail['quantity'];
            $unit_value = (float) $operation_detail['unit_value'];
            $discount = (float) $operation_detail['discount'];
            $vat_rate = (float) $operation_detail['vat_rate'];

            // Cálculos de vat_item y balance
            $vat_item = $operation_detail['quantity'] * $operation_detail['unit_value'] * (1 - $operation_detail['discount'] / 100) * ($operation_detail['vat_rate'] / 100);
            $balance = $operation_detail['quantity'] * $operation_detail['unit_value'] * (1 - $operation_detail['discount'] / 100) + $vat_item;

            // Acumulamos los valores
            $vat += $vat_item;
            $total += $balance;
        }

        // Devolvemos el total y el vat
        return [
            'total' => $total,
            'vat' => $vat
        ];
    }
    
    //Desde el show
    // public function create($data)
    // {
    //     return parent::create($data); // Usa la función de inserción de `Model`
    // }

    public function delete($id)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
}
