<?php

namespace Af\ProgressiveBundle\Rule;

use Progressive\ParameterBagInterface;
use Progressive\Rule\RuleInterface;
use Symfony\Component\Security\Core\Security;

class Roles implements RuleInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function decide(ParameterBagInterface $bag, array $roles = []): bool
    {
        if (null === $user = $this->security->getUser()) {
            return false;
        }

        $userRoles = $user->getRoles();
        foreach ($userRoles as $role) {
            if (in_array($role, $roles, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'roles';
    }
}
