<?php

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class BlockedUserListener
{

    public function __construct(
        private Security $security, 
        private RouterInterface $router, 
        private RequestStack $requestStack
    )
    {}

    public function onKernelRequest(RequestEvent $event): ?RedirectResponse
    {
        $user = $this->security->getUser();
        $request = $this->requestStack->getCurrentRequest();
        $currentRoute = $request->attributes->get('_route');
    
        if ($user && in_array("ROLE_BLOCKED", $user->getRoles())) {
            if ($currentRoute !== 'app_blocked') {
                $url = $this->router->generate('app_blocked');
                $event->setResponse(new RedirectResponse($url));
            }
        }
    
        return null;
    }
}