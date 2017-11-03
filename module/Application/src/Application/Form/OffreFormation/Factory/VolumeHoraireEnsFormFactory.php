<?php

namespace Application\Form\OffreFormation\Factory;

use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;
use Application\Form\OffreFormation\VolumeHoraireEnsForm;



/**
 * Description of VolumeHoraireEnsFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireEnsFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return VolumeHoraireEnsForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formOffreFormationVolumeHoraireEns = new VolumeHoraireEnsForm;
        /* Injectez vos dépendances ICI */

        return $formOffreFormationVolumeHoraireEns;
    }
}