<?php

namespace Af\ProgressiveBundle;

use Af\ProgressiveBundle\DependencyInjection\Compiler\RulePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AfProgressiveBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RulePass());
    }
}
