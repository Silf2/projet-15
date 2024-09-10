<?php

// src/EventListener/BlockedUserListener.php
namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class BlockedUserListener
{

    public function __construct(
        private Security $security, 
        private RouterInterface $router, 
        private RequestStack $requestStack)
    {}

    public function onKernelRequest(): void
    {
        // Si l'utilisateur est connecté et possède le rôle ROLE_BLOCKED
        if ($this->security->getUser() && $this->security->isGranted('ROLE_BLOCKED')) {
            $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');
            // Empêche la redirection en boucle sur la page bloquée
            if ($currentRoute !== 'app_blocked') {
                $response = new RedirectResponse($this->router->generate('app_blocked'));
                $response->send();
                exit;
            }
        }
    }
}