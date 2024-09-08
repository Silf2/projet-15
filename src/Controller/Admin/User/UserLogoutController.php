<?php

namespace App\Controller\Admin\User;

use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
final class UserLogoutController
{
    #[Route("/logout", name:"app_logout")]
    public function __invoke()
    {
        //Symfony handle logout itself as long as the path exist.
    }
}