<?php

namespace Af\ProgressiveBundle\Rule;

use Progressive\ParameterBagInterface;
use Progressive\Rule\RuleInterface;

class Environments implements RuleInterface
{
    private string $env;

    public function __construct($env)
    {
        $this->env = $env;
    }

    public function decide(ParameterBagInterface $bag, array $envs = []): bool
    {
        return in_array($this->env, $envs);
    }

    public function getName(): string
    {
        return 'env';
    }
}
