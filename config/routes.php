<?php declare(strict_types=1);

namespace App;

use AlanVdb\Controller\MainController;
use AlanVdb\Controller\AuthController;

return [
    ['home', 'GET', '/', [MainController::class, 'index']],
    ['login', 'GET|POST', '/login', [AuthController::class, 'login']],
    ['logout', 'GET', '/logout', [AuthController::class, 'logout']],
    ['profile', 'GET', '/profile', [AuthController::class, 'profile']],
    // ['about', 'GET', '/about', [MainController::class, 'about']],
    // ['contact', 'GET', '/contact', [MainController::class, 'contact']]
];