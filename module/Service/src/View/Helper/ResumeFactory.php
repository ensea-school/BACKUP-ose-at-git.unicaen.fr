<?php

namespace Service\View\Helper;

use Application\View\Helper\Service\ResumeViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of ResumeFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ResumeFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return HorodatageViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): HorodatageViewHelper
    {
        $viewHelper = new ResumeViewHelper();

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}