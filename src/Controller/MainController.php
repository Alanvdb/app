<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MainController extends AbstractController
{
    protected const DEFAULT_HEAD_VARS = ['stylesheets' => ['css/main.css']];
    protected const DEFAULT_HEADER_VARS = ['darkMode' => true];
    protected const DEFAULT_HOME_VARS = null;
    protected const DEFAULT_FOOTER_VARS = null;

    protected function setup()
    {
        $this->renderer->addNamespace('main', __DIR__.'/../../assets/templates');
        $this->renderer->addNamespace('layout', __DIR__.'/../../assets/templates/layout');
    }

    public function home() : ResponseInterface
    {
        $headerVars = ['uriGenerator' => $this->uriGenerator];

        $templates = [
            'layout.head'   => self::DEFAULT_HEAD_VARS,
            'layout.header' => array_merge(self::DEFAULT_HEADER_VARS, $headerVars),
            'main.home'     => self::DEFAULT_HOME_VARS,
            'layout.footer' => self::DEFAULT_FOOTER_VARS
        ];
        return $this->createResponse($this->renderTemplates($templates));
    }

    public function login() : ResponseInterface
    {
        $stream = $this->streamFactory->createStream('');
        return $this->createResponse($this->render('main.home'));
    }
}
