<?php

namespace Application\View;

use Laminas\ServiceManager\Initializer\InitializerInterface;
use Laminas\Form\View\Helper\AbstractHelper;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class VueJsInitializer implements InitializerInterface
{
    public function __invoke(ContainerInterface $container, $instance)
    {
        if ($instance instanceof AbstractHelper) {
            $instance->addValidAttributePrefix('@');
            $instance->addValidAttributePrefix('v-');
            $instance->addValidAttributePrefix(':');
        }
    }
}
