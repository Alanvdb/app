<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MainController extends AbstractController
{
    public function home() : ResponseInterface
    {

        return $this->createResponse('Hello world !');
    }

    public function login() : ResponseInterface
    {
        return $this->createResponse($this->twig->render('home'));
    }
}
