<?php declare(strict_types=1);

namespace AlanVdb\App\Controller;

use Psr\Http\Message\ResponseInterface;

class MainController extends AbstractController
{
    public function showHome() : ResponseInterface
    {
        return $this->createResponse('Hello World !');
    }
}
