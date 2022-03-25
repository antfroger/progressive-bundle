<?php

namespace Af\ProgressiveBundle\Tests\Rule;

use Af\ProgressiveBundle\Rule\Roles;
use PHPUnit\Framework\TestCase;
use Progressive\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class RolesTest extends TestCase
{
    public function testNotAuthenticated()
    {
        $security = $this->createMock(Security::class);
        $context = $this->createMock(ParameterBagInterface::class);

        $rule = new Roles($security);
        $this->assertFalse($rule->decide($context, []));
    }

    /**
     * @dataProvider rolesProvider
     */
    public function testAuthenticatedUser(array $roles, bool $expected)
    {
        $user = $this->createMock(UserInterface::class);
        $user->expects($this->once())
            ->method('getRoles')
            ->willReturn(['ROLE_DEV', 'ROLE_ADMIN'])
        ;

        $security = $this->createMock(Security::class);
        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;
        $context = $this->createMock(ParameterBagInterface::class);

        $rule = new Roles($security);
        $response = $rule->decide($context, $roles);

        $this->assertSame($response, $expected);
    }

    public function rolesProvider(): array
    {
        return [
            [[], false],
            [['ROLE_SUPERADMIN', 'ROLE_TRANSLATOR'], false],
            [['ROLE_ADMIN', 'ROLE_DEV', 'ROLE_SUPERADMIN'], true],
            [['ROLE_DEV', 'ROLE_ADMIN'], true],
            [['ROLE_ADMIN'], true],
            [['ROLE_DEV'], true],
        ];
    }
}
