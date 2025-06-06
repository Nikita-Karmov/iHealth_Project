<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller {
    public function show() {
        $slug = $_GET['category'] ?? '';
        $category = null;
        $products = [];
        $error = null;

        try {
            $category = Category::findBySlug($slug);
            if (!$category) {
                throw new \Exception("Категория не найдена");
            }

            $products = Product::getByCategorySlug($slug);
        } catch (\Exception $e) {
            error_log("Ошибка категории: " . $e->getMessage());
            $error = "Не удалось загрузить категорию. Попробуйте позже.";
        }

        $this->view('category', [
            'category' => $category,
            'products' => $products,
            'error' => $error
        ]);
    }
}
