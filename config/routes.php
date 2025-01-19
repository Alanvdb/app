<?php declare(strict_types=1);

namespace App;

use AlanVdb\Controller\MainController;
use AlanVdb\Controller\BootstrapController;
use AlanVdb\Controller\PicoController;

return [
    ['home', 'GET', '/', [MainController::class, 'home']],
    ['pico.examples', 'GET', '/pico/examples', [PicoController::class, 'index']],
    ['bootstrap.examples', 'GET', '/bootstrap/examples', [BootstrapController::class, 'index']],
    ['bootstrap.example', 'GET', '/bootstrap/example/{slug}', [BootstrapController::class, 'show']],
    ['boostrap.svg', 'GET', '/boostrap/svg', [BootstrapController::class, 'svgCollection']]
];