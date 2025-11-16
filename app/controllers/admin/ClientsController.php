<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../app/libraries/admin/Model.php';
require_once '../../app/models/admin/Quote.php';
require_once '../../app/models/admin/Client.php';
require_once '../../app/models/admin/QuoteDetail.php';
require_once '../../app/models/admin/Product.php';
require_once '../../app/models/admin/OperationData.php'; // Agregar esta línea

use App\Models\Admin\OperationData; // Si usas namespaces, agrégalo aquí

use App\Models\Admin\Quote;
use App\Models\Admin\Client;
use App\Models\Admin\QuoteDetail;
use App\Models\Admin\Product;
// ClientsController.php

class ClientsController {
    private Quote $quote;
    private Client $business; // Alias genérico para clientes/proveedores
    private QuoteDetail $operation_details;
    private Product $product;
    private OperationData $operationData;
    private $mysqli;

    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;

        // Inicializamos todo antes de pasarlo a OperationData
        $this->quote = new Quote();
        $this->business = new Client(); // Aquí puedes cambiarlo a Provider si quieres
        $this->operation_details = new QuoteDetail();
        $this->product = new Product();

        // Ahora sí inicializamos OperationData
        $this->operationData = new OperationData(
            $this->quote,
            $this->business,
            $this->operation_details,
            $this->product
        );
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $businessPerPage = 10;
        $currentPage = (int)($_GET['currentPage'] ?? 1);
        $offset = ($currentPage - 1) * $businessPerPage;

        // Aquí podrías pasar $search si tu método lo soporta
        $businessList = $this->business->getAllPaginated($businessPerPage, $offset);

        $totalBusiness = $this->business->getTotal($search);
        $totalPages = ceil($totalBusiness / $businessPerPage);

        include '../../app/views/admin/clients/index.php';
    }

    public function search()
    {
        header('Content-Type: application/json');

        if (!isset($_GET['alias'])) {
            echo json_encode(['success' => false, 'message' => 'Se requiere un alias']);
            return;
        }

        $alias = $_GET['alias'];
        $clientModel = $this->business->getByAlias($alias);

        if ($clientModel) {
            echo json_encode(['success' => true, 'data' => $clientModel]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron resultados']);
        }
    }
}

