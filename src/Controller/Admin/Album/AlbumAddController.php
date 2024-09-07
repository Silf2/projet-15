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
final class AlbumAddController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Environment $twig,
        private FormFactoryInterface $formFactory,
        private RouterInterface $router
    )
    {}

    #[Route("/admin/album/add", name: "app_admin_album_add")]
    public function __invoke(Request $request): Response
    {
        $album = new Album();
        $form = $this->formFactory->create(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($album);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('admin_album_index'));
        }

        $content = $this->twig->render('admin/album/add.html.twig', ['form' => $form->createView()]);
        return new Response($content);
    }
}