<?php

namespace App\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Twig\Environment;

class ExceptionController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function showException(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        return new Response($this->twig->render('404.html.twig'));
    }
}