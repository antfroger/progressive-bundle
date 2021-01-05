<?php

namespace Af\ProgressiveBundle\DependencyInjection;

use Progressive\Context;
use Progressive\Progressive;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Yaml\Yaml;

class AfProgressiveExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Add parameters to the Context
        if (null !== $config['context']) {
            $definition = $container->getDefinition(Context::class);
            $definition->addMethodCall('add', [$config['context']]);
        }

        // Features' configuration
        $definition = $container->getDefinition(Progressive::class);
        $featuresConfig = Yaml::parseFile($config['config']);
        $definition->replaceArgument(0, $featuresConfig);
    }
}
