<?php declare(strict_types=1);

namespace App;

use AlanVdb\Controller\MainController;

return [
    ['home', 'GET', '/', [MainController::class, 'home']],
    ['login', 'GET|POST', '/login', [MainController::class, 'login']],
];