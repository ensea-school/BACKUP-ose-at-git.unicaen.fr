<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Agrement;

/**
 * Description of AgrementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AgrementAwareTrait
{
    /**
     * @var Agrement
     */
    private $agrement;





    /**
     * @param Agrement $agrement
     * @return self
     */
    public function setAgrement( Agrement $agrement = null )
    {
        $this->agrement = $agrement;
        return $this;
    }



    /**
     * @return Agrement
     */
    public function getAgrement()
    {
        return $this->agrement;
    }
}