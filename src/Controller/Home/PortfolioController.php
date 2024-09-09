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
        $album = $id ? $this->albumRepository->find($id) : null;
        $user = $this->userRepository->findOneBy(["roles" => "ROLE_ADMIN"]);

        $medias = $album
            ? $this->mediaRepository->findByAlbum($album)
            : $this->mediaRepository->findByUser($user);
        
        $content = $this->twig->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);

        return new Response($content);
    }
}