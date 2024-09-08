<?php

namespace App\Controller\Admin\Album;

use App\Entity\Album;
use App\Form\AlbumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

#[AsController]
final class AlbumUpdateController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Environment $twig,
        private FormFactoryInterface $formFactory,
        private RouterInterface $router
    )
    {}

    #[Route("/admin/album/update/{id}", name: "app_admin_album_update")]
    public function __invoke(Request $request, int $id): Response
    {
        $album = $this->entityManager->getRepository(Album::class)->find($id);
        $form = $this->formFactory->create(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('app_admin_album_index'));
        }

        $content = $this->twig->render('admin/album/update.html.twig', ['form' => $form->createView()]);
        return new Response($content);
    }
}