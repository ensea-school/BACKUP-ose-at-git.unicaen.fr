<?php

namespace Application\View\Helper;

use Application\View\Helper\VueViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of VueViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class VueViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return VueViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): VueViewHelper
    {
        $viewHelper = new VueViewHelper();

        return $viewHelper;
    }
}