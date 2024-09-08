<?php

namespace App\Controller\Admin\Media;

use App\Entity\Media;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

#[AsController]
final class MediaAddController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Environment $twig,
        private RouterInterface $router,
        private AuthorizationCheckerInterface $authorizationChecker,
        private FormFactoryInterface $formFactory,
        private Security $security
    ){}

    #[Route("/admin/media/add", name:"app_admin_media_add")]
    public function __invoke(Request $request): Response
    {
        $media = new Media();
        $form = $this->formFactory->create(MediaType::class, $media, ['is_admin' => $this->authorizationChecker->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                $media->setUser($this->security->getUser());
            }
            $media->setPath('uploads/' . md5(uniqid()) . '.' . $media->getFile()->guessExtension());
            $media->getFile()->move('uploads/', $media->getPath());
            $this->entityManager->persist($media);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('app_admin_media_index'));
        }

        $content = $this->twig->render('admin/media/add.html.twig', ['form' => $form->createView()]);
        return new Response($content);
    }
}