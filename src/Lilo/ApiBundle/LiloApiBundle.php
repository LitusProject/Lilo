<?php
/**
 * Lilo is a message and exception logging service,
 * built by @pmaene.
 *
 * @author Pieter Maene <pieter.maene@litus.cc>
 */

namespace Lilo\ApiBundle;

use Lilo\ApiBundle\DependencyInjection\Security\Factory\KeyFactory,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\HttpKernel\Bundle\Bundle;

class LiloApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->getExtension('security')
            ->addSecurityListenerFactory(new KeyFactory());
    }
}
