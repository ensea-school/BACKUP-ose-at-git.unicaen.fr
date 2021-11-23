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
    /**
     * @var RechercheHydrator
     */
    private $hydratorServiceRecherche;



    /**
     * @param RechercheHydrator $hydratorServiceRecherche
     *
     * @return self
     */
    public function setHydratorServiceRecherche(RechercheHydrator $hydratorServiceRecherche)
    {
        $this->hydratorServiceRecherche = $hydratorServiceRecherche;

        return $this;
    }



    /**
     * @return RechercheHydrator
     */
    public function getHydratorServiceRecherche()
    {
        if (empty($this->hydratorServiceRecherche)) {
            $this->hydratorServiceRecherche = \Application::$container->get('HydratorManager')->get(RechercheHydrator::class);
        }

        return $this->hydratorServiceRecherche;
    }
}