<?php declare(strict_types=1);

namespace AlanVdb\App\Controller;

use Psr\Http\Message\ResponseInterface;

class MainController extends AbstractController
{
    protected function setup()
    {
        $this->renderer->addNamespace('main', __DIR__.'/../../assets/templates');
        $this->renderer->addNamespace('layout', __DIR__.'/../../assets/templates/layout');
    }

    public function showHome() : ResponseInterface
    {
        $stream = $this->streamFactory->createStream('');
        return $this->createResponse($this->render('main.home'));
    }
}
