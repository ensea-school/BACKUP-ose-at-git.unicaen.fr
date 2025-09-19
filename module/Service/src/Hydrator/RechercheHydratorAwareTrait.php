<?php

namespace Service\Hydrator;

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
    public function setHydratorServiceRecherche(?RechercheHydrator $hydratorServiceRecherche)
    {
        $this->hydratorServiceRecherche = $hydratorServiceRecherche;

        return $this;
    }



    public function getHydratorServiceRecherche(): ?RechercheHydrator
    {
        if (empty($this->hydratorServiceRecherche)) {
            $this->hydratorServiceRecherche = \Framework\Application\Application::getInstance()->container()->get(RechercheHydrator::class);
        }

        return $this->hydratorServiceRecherche;
    }
}