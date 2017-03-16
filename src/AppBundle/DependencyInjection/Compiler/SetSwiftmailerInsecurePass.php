<?php

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Mailer\InsecureTransport;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * RegisterPluginsPass registers Swiftmailer plugins.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SetSwiftmailerInsecurePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($transport = $container->findDefinition('swiftmailer.mailer.default.transport')) {
            $transport->setClass(InsecureTransport::class);
            $transport->addMethodCall('setInsecure');
        }
    }
}
