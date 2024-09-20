<?php

namespace App\Controller\Admin\Guest;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[AsController]
final class GuestBlockController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private RouterInterface $router
    )
    {}

    #[Route("/admin/guest/block/{id}", name: "app_admin_guest_block")]
    public function __invoke(int $id): RedirectResponse
    {
        $user = $this->userRepository->findOneBy(["id" => $id]);

        if(!$user){
            throw new NotFoundHttpException("L'utilisateur que vous essayez de bloquer n'existe pas.");
        }

        if (!in_array('ROLE_BLOCKED', $user->getRoles())) {
            $user->setRoles(["ROLE_BLOCKED"]);
        } 
        elseif (in_array('ROLE_BLOCKED', $user->getRoles())) {
            $user->setRoles(["ROLE_USER"]);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('app_admin_guest_index'));
    }
}