<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class SearchController extends Controller {
    public function search() {
        $query = trim($_GET['q'] ?? '');
        $products = [];

        if ($query !== '') {
            $products = Product::search($query);
        }

        $this->view('search_results', [
            'query' => $query,
            'products' => $products
        ]);
    }
}
