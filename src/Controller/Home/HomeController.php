<?php

namespace App\Controller\Home;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class HomeController
{
    public function __construct(
        private Environment $twig,
    )
    {}

    #[Route("/", name:"app_home")]
    public function __invoke(): Response
    {
        $content = $this->twig->render("front/home.html.twig");

        return new Response($content);
    }
}