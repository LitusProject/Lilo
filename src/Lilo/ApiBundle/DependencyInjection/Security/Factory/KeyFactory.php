<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene and @krmarien.
 *
 * @author Pieter Maene <pieter.maene@vtk.be>
 * @author Kristof Mariën <kristof.marien@vtk.be>
 */

namespace Lilo\ApiBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface,
    Symfony\Component\Config\Definition\Builder\NodeDefinition,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\DefinitionDecorator;

class KeyFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security_provider.key.' . $id;
        $container->setDefinition($providerId, new DefinitionDecorator('security_provider.key'));
        $listenerId = 'security_listener.key.' . $id;
        $container->setDefinition($listenerId, new DefinitionDecorator('security_listener.key'));

        return array(
            $providerId, $listenerId, $defaultEntryPoint
        );
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'key';
    }

    public function addConfiguration(NodeDefinition $node) {}
}
