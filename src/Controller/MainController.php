<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MainController extends AbstractController
{
    public function index() : ResponseInterface
    {
        $params = $this->getCommonTemplateParams();
        $document = $this->twig->render('home.twig', $params);
        return $this->createResponse($document);
    }

    protected function getCommonTemplateParams() : array
    {
        return [
            'uriGenerator' => $this->request->getAttribute('uriGenerator')
        ];
    }
}
