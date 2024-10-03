<?php

namespace App\Controller\Home;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class GuestController
{
    public function __construct(
        private Environment $twig,
        private UserRepository $userRepository,
        private PaginatorInterface $paginator
    )
    {}

    #[Route("/guests", name:"app_guests")]
    public function __invoke(Request $request): Response
    {
        $queryBuilder = $this->userRepository->createQueryBuilder('u')
            ->where('NOT u.roles LIKE :blockedRole')
            ->setParameter('blockedRole', '%ROLE_BLOCKED%');

        $pagination = $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), 
            10 
        );

        $content = $this->twig->render('front/guests.html.twig', [
            'pagination' => $pagination
        ]);

        return new Response($content);
    }
}