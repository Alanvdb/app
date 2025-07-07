<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MainController extends AbstractController
{
    protected const TEMPLATE_HOME = 'home.twig';

    public function index() : ResponseInterface
    {
        $params = $this->getCommonTemplateParams();
        $document = $this->render(self::TEMPLATE_HOME, $params);
        return $this->view($document);
    }
}
