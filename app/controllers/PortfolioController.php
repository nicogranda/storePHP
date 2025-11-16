<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_status() === PHP_SESSION_NONE && session_start();

// Rutas de archivos (sensible a Linux, minúscula)
require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../models/admin/Category.php';
require_once __DIR__ . '/../models/Portfolio.php';

// Namespaces de las clases
use App\Models\Portfolio;
use App\Models\Admin\Category;

class PortfolioController
{
    private $portfolio;
    private $category;
    private $mysqli;

    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;

        $this->category = new Category();
        $this->portfolio = new Portfolio($this->mysqli);
    }

    public function index($lang)
    {
        // Traer todas las categorías
        $categories = $this->category->getAll();

        // Traer todos los productos del portfolio (asegura array)
        $products = $this->portfolio->getAll($lang) ?: [];

    //  var_dump($categories);
    //     exit;
        // Cargar vista
        include __DIR__ . '/../views/portfolio.php';
    }

    public function show($lang, $categoryName)
    {
        // Traer productos por categoría
        $products = $this->portfolio->getByCategory($lang, $categoryName) ?: [];

        // Traer todas las categorías
        $categories = $this->category->getAll();

        // Cargar vista
        include __DIR__ . '/../views/portfolio.php';
    }
}
