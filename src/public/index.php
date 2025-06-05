<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';
require_once __DIR__ . '/../controllers/CatalogController.php';
require_once __DIR__ . '/../controllers/SearchController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$router = new Router();

$router->add('/', 'HomeController@index');
$router->add('/category', 'CategoryController@show');
$router->add('/catalog', 'CatalogController@index'); 
$router->add('/search', 'SearchController@search');

$router->add('/cart', 'CartController@showCart');
$router->add('/cart/add', 'CartController@add', 'POST');
$router->add('/cart/clear', 'CartController@clear');
$router->add('/cart/update', 'CartController@updateQuantity', 'POST');

$router->add('/auth', 'AuthController@showForm');
$router->add('/login', 'AuthController@login', 'POST');
$router->add('/register', 'AuthController@register', 'POST');
$router->add('/logout', 'AuthController@logout');

$router->add('/account', 'AuthController@account');

$router->dispatch($_SERVER['REQUEST_URI']);
