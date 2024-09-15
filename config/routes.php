<?php declare(strict_types=1);

namespace App;

use AlanVdb\App\Controller\MainController;

return [
    ['home', 'GET', '/', MainController::class, 'showHome']
];