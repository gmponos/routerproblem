<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class CustomExceptionListener
{
    /**
     * @var RouterInterface
     */
    private $router;

    private $requestStack;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     * @param \Twig_Environment $twig
     */
    public function __construct(
        RouterInterface $router,
        RequestStack $requestStack,
        \Twig_Environment $twig
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->twig = $twig;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof NotFoundHttpException) {
            return;
        }

        $event->setResponse(new Response($this->twig->render('404.html.twig'), 200));
    }
}