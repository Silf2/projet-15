<?php

namespace App\Controller\Admin\Media;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[AsController]
final class MediaDeleteController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RouterInterface $router
    )
    {}

    #[Route("/admin/media/delete/{id}", name:"app_admin_media_delete")]
    public function __invoke(int $id): RedirectResponse
    {
        $media = $this->entityManager->getRepository(Media::class)->find($id);
        $this->entityManager->remove($media);
        $this->entityManager->flush();
        unlink($media->getPath());

        return new RedirectResponse($this->router->generate('admin_media_index'));
    }
}