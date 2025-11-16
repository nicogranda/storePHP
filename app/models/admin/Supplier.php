<?php
// models/Client.php
class Client {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function findByBrand($brand) {
        $query = "SELECT * FROM clients WHERE brand = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $brand);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function create($name, $brand, $email, $business_type_id) {
        $query = "INSERT INTO clients (name, brand, email, business_type, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ssss", $name, $brand, $email, $business_type_id);
        $stmt->execute();
        return $this->mysqli->insert_id;
    }
}

?>