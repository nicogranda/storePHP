<?php
require_once __DIR__ . '/../models/Portfolio.php';

use App\Models\Portfolio;

class CardController
{
    private $portfolio;
    private $mysqli;

    public function __construct()
    {
        global $mysqli; // Tomamos la conexión global
        $this->mysqli = $mysqli;
        $this->portfolio = new Portfolio($this->mysqli);
    }

    public function index($lang)
    {
        // Opcional: lista de productos o redirect
        header("Location: index.php?page=portfolio&action=index");
        exit;
    }

    public function show($lang, $product_id)
    {
        $product = $this->portfolio->getById($product_id);

        if (!$product) {
            include __DIR__ . '/../views/404.php';
            return;
        }

        // Cargamos la vista del producto
        include __DIR__ . '/../views/card.php';
    }
}