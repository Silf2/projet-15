<?php

namespace App\Controller\Home;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class GuestByIdController
{
    public function __construct(
        private Environment $twig,
        private UserRepository $userRepository,
    )
    {}

    #[Route("/guest/{id}", name:"app_one_guest")]
    public function __invoke(int $id): Response
    {
        $guest = $this->userRepository->find($id);

        if(!$guest || in_array('ROLE_BLOCKED', $guest->getRoles())) {
            throw new NotFoundHttpException("L'utilisateur que vous essayez de consulter n'existe pas.");
        }

        $content = $this->twig->render('front/guest.html.twig', [
            'guest' => $guest
        ]);

        return new Response($content);
    }
}