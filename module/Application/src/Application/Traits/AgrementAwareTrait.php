<?php

namespace Application\Traits;

use Application\Entity\Db\Agrement;

/**
 * Description of AgrementAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait AgrementAwareTrait
{
    /**
     * @var Agrement
     */
    protected $agrement;

    /**
     * @param Agrement $agrement
     * @return self
     */
    public function setAgrement(Agrement $agrement = null)
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