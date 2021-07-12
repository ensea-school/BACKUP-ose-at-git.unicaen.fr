<?php

namespace Plafond\Service;

/**
 * Description of PlafondEtatServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondEtatServiceAwareTrait
{
    /**
     * @var PlafondEtatService
     */
    protected $servicePlafondEtat;



    /**
     * @param PlafondEtatService $servicePlafondEtat
     *
     * @return self
     */
    public function setServicePlafondEtat(PlafondEtatService $servicePlafondEtat)
    {
        $this->servicePlafondEtat = $servicePlafondEtat;

        return $this;
    }



    /**
     * @return PlafondEtatService
     */
    public function getServicePlafondEtat(): PlafondEtatService
    {
        if (!$this->servicePlafondEtat) {
            $this->servicePlafondEtat = \Application::$container->get(PlafondEtatService::class);
        }

        return $this->servicePlafondEtat;
    }
}