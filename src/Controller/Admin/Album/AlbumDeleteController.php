<?php

namespace App\Controller\Admin\Album;

use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[AsController]
final class AlbumDeleteController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RouterInterface $router
    )
    {}

    #[Route("/admin/album/delete/{id}", name: "app_admin_album_delete")]
    public function __invoke(int $id): RedirectResponse
    {
        $media = $this->entityManager->getRepository(Album::class)->find($id);
        $this->entityManager->remove($media);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('app_admin_album_index'));
    }
}