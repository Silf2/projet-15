<?php

namespace App\Controller\Home;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class AboutController
{
    public function __construct(
        private Environment $twig,
    )
    {}

    #[Route("/about", name:"app_about")]
    public function __invoke(): Response
    {
        $content = $this->twig->render("front/about.html.twig");

        return new Response($content);
    }
}