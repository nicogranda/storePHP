<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// require_once '../../app/models/Order.php';
require_once __DIR__ . "/../../models/Order.php";


use App\Models\Order;

class OrdersController
{
    private $orderModel;
    private $mysqli;

    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;
        $this->orderModel = new Order($mysqli);
    }

    // 📝 INDEX: Listado de órdenes con búsqueda opcional y paginación
    public function index()
    {
        // Obtener búsqueda desde GET
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Configuración de paginación
        $ordersPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $ordersPerPage;

        // Obtener órdenes
        if (!empty($search)) {
            $orders = $this->orderModel->searchById($search); // Devuelve array
            if (!empty($orders) && isset($orders['id'])) {
                $orders = [$orders]; // Si es un solo resultado, lo convertimos en array
            }
        } else {
            $orders = $this->orderModel->getAllPaginated($ordersPerPage, $offset);
        }

        // Total de órdenes (filtradas si hay búsqueda)
        $totalOrders = $this->orderModel->getTotal($search);
        $totalPages = ceil($totalOrders / $ordersPerPage);
        $operations = $orders;
   

        // Cargar vista
        include '../app/views/admin/orders/index.php';
    }

    // 🔍 SEARCH: Manejar búsquedas por POST (formulario o AJAX)
    public function search()
    {
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';

        $ordersPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $ordersPerPage;

        if (!empty($search)) {
            $orders = $this->orderModel->searchById($search);
            if (!empty($orders) && isset($orders['id'])) {
                $orders = [$orders]; // Convertir a array si es un solo resultado
            }
        } else {
            $orders = $this->orderModel->getAllPaginated($ordersPerPage, $offset);
        }

        $totalOrders = $this->orderModel->getTotal($search);
        $totalPages = ceil($totalOrders / $ordersPerPage);

        // Cargar vista
        include '../../app/views/admin/orders/index.php';
    }
}
