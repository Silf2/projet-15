<?php

namespace App\Controller\Admin\Guest;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class GuestPageController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Environment $twig,
    )
    {}

    #[Route("/admin/guest", name: "app_admin_guest_index")]
    public function __invoke(): Response
    {
        $guests = $this->entityManager->getRepository(User::class)->findAll();
        $content = $this->twig->render('admin/guest/index.html.twig', ['guests' => $guests]);

        return new Response($content);
    }
}