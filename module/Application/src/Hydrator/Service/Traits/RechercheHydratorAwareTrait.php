<?php

namespace Application\Hydrator\Service\Traits;

use Application\Hydrator\Service\RechercheHydrator;

/**
 * Description of RechercheHydratorAwareTrait
 *
 * @author UnicaenCode
 */
trait RechercheHydratorAwareTrait
{
    protected ?RechercheHydrator $hydratorServiceRecherche = null;



    /**
     * @param RechercheHydrator $hydratorServiceRecherche
     *
     * @return self
     */
    public function setHydratorServiceRecherche( RechercheHydrator $hydratorServiceRecherche )
    {
        $this->hydratorServiceRecherche = $hydratorServiceRecherche;

        return $this;
    }



    public function getHydratorServiceRecherche(): ?RechercheHydrator
    {
        if (empty($this->hydratorServiceRecherche)){
            $this->hydratorServiceRecherche = \Application::$container->get(RechercheHydrator::class);
        }

        return $this->hydratorServiceRecherche;
    }
}