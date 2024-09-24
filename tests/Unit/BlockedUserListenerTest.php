<?php

namespace App\Tests\Unit;

use App\EventListener\BlockedUserListener;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BlockedUserListenerTest extends TestCase
{
    /** @var Security&\PHPUnit\Framework\MockObject\MockObject */
    private $security;

    /** @var RouterInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $router;
    private $requestStack;
    private $listener;

    
    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->requestStack = new RequestStack();

        $this->listener = new BlockedUserListener(
            $this->security,
            $this->router,
            $this->requestStack
        );
    }

    public function testRedirectBlockedUser()
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')->willReturn(['ROLE_BLOCKED']);
        $this->security->method('getUser')->willReturn($user);

        $request = new Request();
        $request->attributes->set('_route', 'some_route');
        $this->requestStack->push($request);

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_blocked')
            ->willReturn('/blocked');

        /** @var HttpKernelInterface $kernel */
        $kernel = $this->createMock(HttpKernelInterface::class);

        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());
        $this->assertEquals('/blocked', $event->getResponse()->getTargetUrl());
    }

    public function testNoRedirectForNonBlockedUser()
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')->willReturn(['ROLE_USER']);
        $this->security->method('getUser')->willReturn($user);

        $request = new Request();
        $request->attributes->set('_route', 'some_route');
        $this->requestStack->push($request);

        /** @var HttpKernelInterface $kernel */
        $kernel = $this->createMock(HttpKernelInterface::class);

        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testNoRedirectOnBlockedRoute()
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')->willReturn(['ROLE_BLOCKED']);
        $this->security->method('getUser')->willReturn($user);

        $request = new Request();
        $request->attributes->set('_route', 'app_blocked');
        $this->requestStack->push($request);

        /** @var HttpKernelInterface $kernel */
        $kernel = $this->createMock(HttpKernelInterface::class);

        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $this->listener->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }
}