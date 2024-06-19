<?php
namespace Signature;

use Signature\Controller\Admin\EntiteController;
use Signature\Controller\Admin\UserController;
use Signature\Controller\ErrorController;
use Signature\Controller\SecurityController;
use Signature\Controller\Marketing\BannerController;
use Signature\Controller\Profile\ProfileController;
use Signature\Controller\SignatureController;
use Signature\Model\User;
use Signature\Service\Container;
use Signature\Service\Router;
use Signature\Service\Security;

require '../vendor/autoload.php';

$container = new Container();

// if (!isset($_SESSION['login']) && !isset($_SESSION['password'])) {
//     $controller = new SecurityController();
//     return $controller->login();
// }

$firewallDefintions = [
    '/admin' => [User::ROLE_ADMIN],
    '/marketing' => [User::ROLE_ADMIN, User::ROLE_MARKETING],
    '/' => [User::ROLE_USER],
];

$uri = $_SERVER['REQUEST_URI'];

$security = new Security($container, $firewallDefintions);
$security->check($uri);

$routeDefintions = [
    // 'path/to/find' => ['ControllerClass', 'controllerMethod'],
    '' => [SignatureController::class, 'create'],
    '/register' => [SecurityController::class, 'register'],
    '/login' => [SecurityController::class, 'login'],
    '/logout' => [SecurityController::class, 'logout'],

    '/profile' => [ProfileController::class, 'show'],
    '/profile/edit' => [ProfileController::class, 'edit'],

    '/signature' => [SignatureController::class, 'create'],

    '/admin' => [UserController::class, 'listUser'],
    '/admin/user' => [UserController::class, 'listUser'],
    '/admin/user/add' => [UserController::class, 'addUser'],
    '/admin/user/edit' => [UserController::class, 'editUser'],
    '/admin/user/delete' => [UserController::class, 'deleteUser'],

    '/admin/entite' => [EntiteController::class, 'listEntite'],
    '/admin/entite/add' => [EntiteController::class, 'addEntite'],
    '/admin/entite/edit' => [EntiteController::class, 'editEntite'],
    '/admin/entite/delete' => [EntiteController::class, 'deleteEntite'],

    '/marketing' => [BannerController::class, 'listBanner'],
    '/marketing/banner' => [BannerController::class, 'listBanner'],
    '/marketing/banner/add' => [BannerController::class, 'addBanner'],
    '/marketing/banner/edit' => [BannerController::class, 'editBanner'],
    '/marketing/banner/delete' => [BannerController::class, 'deleteBanner'],
    
];

$router = new Router($container, $routeDefintions);

if ($callable = $router->find($uri)) {
    $callable();
} else {
    $controller = new ErrorController($container);
    return $controller->notFound();
}
exit;