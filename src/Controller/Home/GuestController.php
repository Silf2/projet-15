<?php

namespace App\Controller\Home;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class GuestController
{
    private const GUESTS_PER_PAGE = 10;

    public function __construct(
        private Environment $twig,
        private UserRepository $userRepository
    )
    {}

    #[Route("/guests", name:"app_guests")]
    public function __invoke(Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        
        $offset = ($page - 1) * self::GUESTS_PER_PAGE;

        $guests = $this->userRepository->findBy([], null, self::GUESTS_PER_PAGE, $offset);

        $totalGuests = $this->userRepository->count([]);
        $totalPages = ceil($totalGuests / self::GUESTS_PER_PAGE);

        $content = $this->twig->render('front/guests.html.twig', [
            'guests' => $guests,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);

        return new Response($content);
    }
}