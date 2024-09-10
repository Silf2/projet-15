<?php

namespace App\Controller\Home;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class BlockedController
{
    public function __construct(
        private Environment $twig
    )
    {}

    #[Route("/blocked", name:"app_blocked")]
    public function __invoke(): Response
    {
        $content = $this->twig->render("front/blocked.html.twig");

        return new Response($content);
    }
}