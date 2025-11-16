<?php
namespace App\Models\Admin;

class OperationData 
    // Código de la clase aquí

{
    private $operation;
    private $business;
    private $operation_details;
    private $product;

    public function __construct($operation, $business, $operation_details, $product)
    {
        $this->operation = $operation;
        $this->business = $business;
        $this->operation_details = $operation_details;
        $this->product = $product;
    }

    public function getOperationData($id, $column)
    {
        $operation = $this->operation->getById($id);
        if (!$operation) {
            return null;
        }

        $id;
        $business = $this->business->getById($operation['business_id']);
    
        $operation['business_alias'] = $business['alias'] ?? 'Cliente no encontrado';
        $operation['business_name'] = $business['name'] ?? 'Cliente no encontrado';
        $operation['business_email'] = $business['email'] ?? 'N/A';
        $operation['business_address'] = $business['address'] ?? 'N/A';

        $operation_details = $this->operation_details->getByItemId($column, $operation['id']);
          
        $vat = 0;
        $total = 0;

        foreach ($operation_details as &$operation_detail) {
            $product = $this->product->getByItemId('id', $operation_detail['product_id']);

            if (!empty($product) && is_array($product)) {
                $product = reset($product); // Acceder al primer elemento si es un array
            }

            $operation_detail['product_name'] = $product['name'] ?? 'Product not found';
            $operation_detail['unit'] = $product['unit'] ?? 'N/A';

            // Cálculo de VAT y total
            $vat_item = $operation_detail['quantity'] * $operation_detail['unit_value'] * (1 - $operation_detail['discount'] / 100) * ($operation_detail['vat_rate'] / 100);
            $operation_detail['vat'] = $vat_item ?? 'N/A';
            
            $balance = $operation_detail['quantity'] * $operation_detail['unit_value'] * (1 - $operation_detail['discount'] / 100) + $vat_item;
            $operation_detail['balance'] = $balance ?? 'N/A';
            
            $vat += $vat_item;
            $total += $balance;
        }

        $operation['details'] = $operation_details;
        $operation['vat'] = $vat;
        $operation['total'] = $total;
      
        return $operation;
    }
}
