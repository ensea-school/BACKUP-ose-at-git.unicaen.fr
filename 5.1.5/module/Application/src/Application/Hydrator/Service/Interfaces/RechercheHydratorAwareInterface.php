<?php

namespace Application\Hydrator\Service\Interfaces;

use Application\Hydrator\Service\RechercheHydrator;

/**
 * Description of RechercheHydratorAwareInterface
 *
 * @author UnicaenCode
 */
interface RechercheHydratorAwareInterface
{
    /**
     * @param RechercheHydrator $hydratorServiceRecherche
     * @return self
     */
    public function setHydratorServiceRecherche( RechercheHydrator $hydratorServiceRecherche = null );



    /**
     * @return RechercheHydrator
     */
    public function getHydratorServiceRecherche();
}