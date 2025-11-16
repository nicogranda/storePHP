<?php
namespace App\Controllers;

use App\Models\Cart;

class HeaderController
{
    public $cartItems = [];
    public $totalQty = 0;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->cartItems = Cart::getItems();
        $this->totalQty = array_sum($this->cartItems);
    }

    public function render()
    {
        include 'app/views/partials/header.php';
    }
}
