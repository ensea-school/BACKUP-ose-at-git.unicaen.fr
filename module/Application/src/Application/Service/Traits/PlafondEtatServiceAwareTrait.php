<?php

namespace Application\Service\Traits;

use Application\Service\PlafondEtatService;
use RuntimeException;

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
     * @return self
     */
    public function setServicePlafondEtat( PlafondEtatService $servicePlafondEtat )
    {
        $this->servicePlafondEtat = $servicePlafondEtat;

        return $this;
    }



    /**
     * @return PlafondEtatService
     */
    public function getServicePlafondEtat()
    {
        return $this->servicePlafondEtat;
    }
}