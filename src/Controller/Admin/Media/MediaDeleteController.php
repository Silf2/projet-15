<?php

namespace App\Controller\Admin\Media;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[AsController]
final class MediaDeleteController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RouterInterface $router,
        private Security $security
    )
    {}

    #[Route("/admin/media/delete/{id}", name:"app_admin_media_delete")]
    public function __invoke(int $id): RedirectResponse
    {
        $media = $this->entityManager->getRepository(Media::class)->find($id);

        if (!$media) {
            throw new NotFoundHttpException("Le média que vous essayez de supprimer n'existe pas.");
        }

        $user = $this->security->getUser();

        if ($media->getUser() != $user && !$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'avez pas les droits pour supprimer ce média");
        }

        $this->entityManager->remove($media);
        $this->entityManager->flush();
        unlink($media->getPath());

        return new RedirectResponse($this->router->generate('app_admin_media_index'));
    }
}