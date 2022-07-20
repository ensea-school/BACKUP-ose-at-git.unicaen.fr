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
     * @param RechercheHydrator|null $hydratorServiceRecherche
     *
     * @return self
     */
    public function setHydratorServiceRecherche( ?RechercheHydrator $hydratorServiceRecherche );



    public function getHydratorServiceRecherche(): ?RechercheHydrator;
}