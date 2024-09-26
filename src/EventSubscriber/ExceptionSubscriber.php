<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Environment $twig
    )
    {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = null;

        if ($exception instanceof NotFoundHttpException) {
            $content = $this->twig->render('front/error.html.twig', [
                'message' => $exception->getMessage(),
                'code' => "404",
            ]);

            $response = new Response($content, Response::HTTP_NOT_FOUND);
        } elseif ($exception instanceof AccessDeniedHttpException) {
            $content = $this->twig->render('front/error.html.twig', [
                'message' => $exception->getMessage(),
                'code' => "403",
            ]);

            $response = new Response($content, status: Response::HTTP_FORBIDDEN);
        }

        if ($response) {
            $event->setResponse($response);
        }    
    }
}