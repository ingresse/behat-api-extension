<?php

namespace Ingresse\Behat\ApiExtension;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Behat\Behat\Context\ServiceContainer\ContextExtension;


class Extension implements ExtensionInterface
{
    const CLIENT_ID = 'ingresse_api.client';

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'ingresse_api';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()->scalarNode("base_uri")->defaultValue("localhost");
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->setDefinition(
            self::CLIENT_ID,
            new Definition('GuzzleHttp\Client', [$config])
        );

        $definition = new Definition(
            'Ingresse\Behat\ApiExtension\Context\Initializer\ApiClientInitializer',
            [new Reference(self::CLIENT_ID), $config]
        );
        $definition->addTag(ContextExtension::INITIALIZER_TAG);
        $container->setDefinition(
            'ingresse_api.context_initializer',
            $definition
        );
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }
}
