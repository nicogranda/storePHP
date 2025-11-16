<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../app/libraries/admin/Model.php';
require_once '../../app/models/admin/Supply.php';

use App\Models\Admin\Supply;

class SuppliesController
{
    private $supply;
    private $mysqli;
    
    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;
        $this->supply = new Supply(); // ✅ corrección
    }

    // 📦 INDEX: Lista de insumos (paginado + búsqueda opcional)
    public function index()
    {
        // Obtener el valor de búsqueda (si existe)
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Configuración de paginación
        $suppliesPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $suppliesPerPage;

        // Si hay búsqueda, usamos searchByName(); sino, getAllPaginated()
        if (!empty($search)) {
            $products = $this->supply->searchByName($search);
        } else {
            $products = $this->supply->getAllPaginated($suppliesPerPage, $offset);
        }

        // Obtener total de registros (filtrados si hay búsqueda)
        $totalProducts = $this->supply->getTotal($search);
        $totalPages = ceil($totalProducts / $suppliesPerPage);

        // Pasar datos a la vista
        include '../../app/views/admin/supplies/index.php';
    }

    // 🔍 SEARCH: para manejar búsquedas por POST (formulario o AJAX)
    public function search()
    {
        // Capturar el texto de búsqueda
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    
        // Configuración de paginación
        $productsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $productsPerPage;
    
        if (!empty($search)) {
            // Si hay un término de búsqueda, usar el nuevo método searchByName()
            $products = $this->supply->searchByName($search);
        } else {
            // Si no se busca nada, obtener todos los productos paginados
            $products = $this->supply->getAllPaginated($productsPerPage, $offset);
        }
    
        // Obtener total de productos (filtrados si hay búsqueda)
        $totalProducts = $this->supply->getTotal($search);
        $totalPages = ceil($totalProducts / $productsPerPage);
    
        // Agregar nombre de categoría a cada producto
        foreach ($products as &$product) {
            // $category = $this->category->getById($product['category_id']);
            $product['category_name'] = "";
            // $category ? $category['name'] : 'Category: not found';
        }
        unset($product);
    
        // Cargar vista
        include '../../app/views/admin/supplies/index.php';
    }
}
