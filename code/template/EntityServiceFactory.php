<?php

namespace <namespace>;

use Application\Constants;
use Psr\Container\ContainerInterface;
<if subDir>use <targetClass>;
<endif subDir>



/**
 * Description of <classname>
 *
 * @author <author>
 */
class <classname>
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return <targetClassname>
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): <targetClassname>
    {
        $<variable> = new <targetClassname>;
        $<variable>->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $<variable>;
    }
}