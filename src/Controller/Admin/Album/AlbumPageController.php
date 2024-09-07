<?php

namespace App\Controller\Admin\Album;

use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class AlbumPageController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Environment $twig,
    )
    {}

    #[Route("/admin/album", name: "app_admin_album_index")]
    public function __invoke(): Response
    {
        $albums = $this->entityManager->getRepository(Album::class)->findAll();
        $content = $this->twig->render('admin/album/index.html.twig', ['albums' => $albums]);

        return new Response($content);
    }
}
