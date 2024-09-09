<?php

namespace App\Controller\Home;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
final class PortfolioController
{
    public function __construct(
        private AlbumRepository $albumRepository,
        private MediaRepository $mediaRepository,
        private UserRepository $userRepository,
        private Environment $twig,
    )
    {}

    #[Route("/portfolio/{id}", name:"app_portfolio")]
    public function __invoke(?int $id = null): Response
    {
        $albums = $this->albumRepository->findAll();
        if ($id) {
            // Si un album spécifique est sélectionné
            $album = $this->albumRepository->find($id);
            $medias = $this->mediaRepository->findByAlbum($album);
        } else {
            // Cas "Toutes" : récupérer tous les médias, ceux associés à un album et ceux sans album
            $medias = $this->mediaRepository->findAll();
            
            $album = null; // Pas d'album sélectionné dans ce cas
        }
        
        $content = $this->twig->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);

        return new Response($content);
    }
}