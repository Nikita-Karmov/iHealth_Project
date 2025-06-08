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

            $sort = $_GET['sort'] ?? null;
            $products = Product::getByCategorySlug($slug, $sort);
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

    public function exportXml() {
        $slug = $_GET['category'] ?? '';
        
        try {
            $category = Category::findBySlug($slug);
            if (!$category) {
                throw new \Exception("Категория не найдена");
            }

            $products = Product::getByCategorySlug($slug);

            // Создаём корневой XML-элемент
            $xml = new \SimpleXMLElement('<products/>');

            foreach ($products as $product) {
                $productXml = $xml->addChild('product');
                $productXml->addChild('id', $product['product_id']);
                $productXml->addChild('name', htmlspecialchars($product['name']));
                $productXml->addChild('description', htmlspecialchars($product['description']));
                $productXml->addChild('price', $product['price']);
                $productXml->addChild('image', '/images/products/' . $product['image_url']);
            }

            // Заголовки для загрузки
            header('Content-Type: application/xml; charset=utf-8');
            header('Content-Disposition: attachment; filename="category_' . $slug . '_products.xml"');

            echo $xml->asXML();
            exit;

        } catch (\Exception $e) {
            http_response_code(500);
            echo "Ошибка: " . $e->getMessage();
        }
    }
}
