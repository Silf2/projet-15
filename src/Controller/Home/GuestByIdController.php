<?php

namespace App\Controller\Home;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class GuestByIdController
{
    public function __construct(
        private Environment $twig,
        private EntityManagerInterface $entityManager,
    )
    {}

    #[Route("/guest/{id}", name:"app_one_guest")]
    public function __invoke(int $id): Response
    {
        $guest = $this->entityManager->getRepository(User::class)->find($id);
        $content = $this->twig->render('front/guests.html.twig', [
            'guest' => $guest
        ]);

        return new Response($content);
    }
}