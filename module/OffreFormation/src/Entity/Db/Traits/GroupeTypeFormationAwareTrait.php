<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\GroupeTypeFormation;

/**
 * Description of GroupeTypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait GroupeTypeFormationAwareTrait
{
    protected ?GroupeTypeFormation $groupeTypeFormation = null;



    /**
     * @param GroupeTypeFormation $groupeTypeFormation
     *
     * @return self
     */
    public function setGroupeTypeFormation( ?GroupeTypeFormation $groupeTypeFormation )
    {
        $this->groupeTypeFormation = $groupeTypeFormation;

        return $this;
    }



    public function getGroupeTypeFormation(): ?GroupeTypeFormation
    {
        return $this->groupeTypeFormation;
    }
}