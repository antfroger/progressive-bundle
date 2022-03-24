<?php

namespace Af\ProgressiveBundle\Rule;

use Progressive\ParameterBagInterface;
use Progressive\Rule\RuleInterface;
use Symfony\Component\Security\Core\Security;

class Users implements RuleInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function decide(ParameterBagInterface $bag, array $usernames = []): bool
    {
        if (null === $user = $this->security->getUser()) {
            return false;
        }

        return in_array($user->getUsername(), $usernames);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'users';
    }
}
