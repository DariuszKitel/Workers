<?php

namespace App\Tests\EventSubscriber;


use App\Entity\User;
use App\Entity\WorkPost;
use App\EventSubscriber\AuthoredEntitySubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class AuthoredEntitySubscriberTest extends TestCase
{
    public function testConfig()
    {
        $result = AuthoredEntitySubscriber::getSubscribedEvents();
        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
        $this->assertEquals(
            ['getAuthenticatedUser', EventPriorities::PRE_VALIDATE],
            $result[KernelEvents::VIEW]
        );
    }

    /**
     * @dataProvider providerForAuthorCall
     */
    public function testForAuthorCall(string $className, bool $shouldCallSetAuthor, string $method)
    {
        $entityMock = $this->getEntityMock($className, $shouldCallSetAuthor);
        $mock = $this->getTokenStorageMock();
        $eventMock = $this->getEventMock($method, $entityMock);

        (new AuthoredEntitySubscriber($mock))->getAuthenticatedUser($eventMock);

    }

    public function providerForAuthorCall(): array
    {
        return [
            [WorkPost::class, true, 'POST'],
            [WorkPost::class, false, 'GET'],
            ['NonExisting', false, 'POST']
        ];
    }

    /**
     * @return MockObject | TokenStorageInterface
     */
    private function getTokenStorageMock(): MockObject
    {
        $tokenMock = $this->getMockBuilder(TokenInterface::class)
            ->getMockForAbstractClass();
        $tokenMock->expects($this->once())
            ->method('getUser')->willReturn(new User());

        $mock = $this->getMockBuilder(TokenStorageInterface::class)
            ->getMockForAbstractClass();
        $mock->expects($this->once())->method('getToken')->willReturn($tokenMock);
        return $mock;
    }

    /**
     * @return MockObject|ViewEvent
     */
    private function getEventMock(string $method, $controllerResult): MockObject
    {
        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);

        $eventMock = $this->getMockBuilder(ViewEvent::class)->disableOriginalConstructor()->getMock();
        $eventMock->expects($this->once())
            ->method('getControllerResult')
            ->willReturn($controllerResult);
        $eventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($requestMock);

        return $eventMock;
    }

    /**
     * @return MockObject
     */
    private function getEntityMock(string $className, bool $shouldCallSetAuthor): MockObject
    {
        $entityMock = $this->getMockBuilder($className)
            ->setMethods(['setAuthor'])
            ->getMock();
        $entityMock->expects($shouldCallSetAuthor ? $this->once() : $this->never())
            ->method('setAuthor');
        return $entityMock;
    }

}