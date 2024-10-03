<?php

namespace App\Controller\Home;

use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
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
        private Environment $twig,
    )
    {}

    #[Route("/portfolio/{id}", name:"app_portfolio")]
    public function __invoke(?int $id = null): Response
    {
        $albums = $this->albumRepository->findAll();
        if ($id) {
            $album = $this->albumRepository->find($id);
            $medias = $this->mediaRepository->findByAlbum($album);
        } else {
            $medias = $this->mediaRepository->findBy(['album' => null]);
            $album = null;
        }

        $medias = array_filter($medias, function($media) {
            $user = $media->getUser();
            return !in_array('ROLE_BLOCKED', $user->getRoles());
        });
        
        $content = $this->twig->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);

        return new Response($content);
    }
}