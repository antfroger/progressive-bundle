<?php

namespace Af\ProgressiveBundle\DependencyInjection;

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

        if (null !== $config['context']) {
            $definition = $container->getDefinition('af.progressive.context');
            $definition->addMethodCall('add', [$config['context']]);
        }

        $definition = $container->getDefinition('af.progressive');
        $featuresConfig = Yaml::parseFile($config['config']);
        $definition->replaceArgument(0, $featuresConfig);
    }
}
