<?php

namespace App\Controller\Home;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class GuestController
{
    public function __construct(
        private Environment $twig,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    )
    {}

    #[Route("/guests", name:"app_guests")]
    public function __invoke(): Response
    {
        $guests = $this->userRepository->findAll();
        $content = $this->twig->render('front/guests.html.twig', [
            'guests' => $guests
        ]);

        return new Response($content);
    }
}