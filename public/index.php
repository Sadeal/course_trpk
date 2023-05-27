<?php
require_once '../vendor/autoload.php';
require_once '../framework/autoload.php';
require_once '../controllers/MainController.php';
require_once "../controllers/Controller404.php";
require_once "../controllers/ObjectController.php";
require_once "../controllers/PrincipalController.php";


$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
    "debug" => false
]);
session_set_cookie_params(60 * 60 * 10);
session_start();

$twig->addExtension(new \Twig\Extension\DebugExtension());

$pdo = new PDO("mysql:host=localhost;dbname=course;charset=utf8", "root", "");
$router = new Router($twig, $pdo);
$router->add("/", MainController::class);
$router->add("/project/make/(?P<id>\d+)", ObjectController::class);
$router->add("/project/ans/(?P<id>\d+)", ObjectController::class);
$router->add("/principal", PrincipalController::class);


$router->get_or_default(Controller404::class);
