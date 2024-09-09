<?php

namespace App\Controller\Admin\Guest;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

#[AsController]
final class GuestAddController
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private EntityManagerInterface $entityManager,
        private Environment $twig,
        private RouterInterface $router,
        private UserPasswordHasherInterface $passwordHasher
    )
    {}

    #[Route("/admin/guest/add", name: "app_admin_guest_add")]
    public function __invoke(Request $request): Response
    {
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  
            $password = $user->getPassword();
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setRoles(['ROLE_USER']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('app_admin_guest_index'));
        }

        $content = $this->twig->render('admin/guest/add.html.twig', ['form' => $form->createView()]);
        return new Response($content);
    }
}