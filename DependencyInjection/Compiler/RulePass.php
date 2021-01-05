<?php

namespace Af\ProgressiveBundle\DependencyInjection\Compiler;

use Progressive\Rule\Store;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RulePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(Store::class)) {
            return;
        }

        $definition = $container->findDefinition(Store::class);

        $taggedServices = $container->findTaggedServiceIds('af_progressive.rule');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('add', [new Reference($id)]);
        }
    }
}
