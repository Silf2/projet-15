<?php

namespace App\Controller\Home;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    )
    {}

    #[Route("/guests", name:"app_guests")]
    public function __invoke(Request $request): Response
    {
        // Récupérer le numéro de page depuis la requête, par défaut page 1
        $page = max(1, $request->query->getInt('page', 1));
        
        // Calculer l'offset (décalage) basé sur le numéro de page
        $offset = ($page - 1) * self::GUESTS_PER_PAGE;

        // Récupérer les invités avec une limite et un offset
        $guests = $this->userRepository->findBy([], null, self::GUESTS_PER_PAGE, $offset);

        // Récupérer le nombre total d'invités pour calculer le nombre total de pages
        $totalGuests = $this->userRepository->count([]);
        $totalPages = ceil($totalGuests / self::GUESTS_PER_PAGE);

        // Rendre la vue avec les invités paginés et les informations de pagination
        $content = $this->twig->render('front/guests.html.twig', [
            'guests' => $guests,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);

        return new Response($content);
    }
}