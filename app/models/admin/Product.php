<?php
namespace App\Models\Admin;

use App\Models\Model;

class Product extends Model
{
    protected $table = 'products';

    public function __construct()
    {
        parent::__construct();
    }
}
