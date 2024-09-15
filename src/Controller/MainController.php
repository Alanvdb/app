<?php declare(strict_types=1);

namespace AlanVdb\App\Controller;

use Psr\Http\Message\ResponseInterface;

class MainController extends AbstractController
{
    protected function setup()
    {
        $this->renderer->addNamespace('main', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'templates');
    }

    public function showHome() : ResponseInterface
    {
        return $this->createResponse($this->render('main.home'));
    }
}
