<?php 
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class CatalogController extends Controller {
    public function index() {
        $products = Product::getAll();
        $this->view('catalog', ['products' => $products]);
    }
}
?>