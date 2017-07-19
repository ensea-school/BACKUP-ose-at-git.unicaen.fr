<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\GroupeTypeFormation;

/**
 * Description of GroupeTypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait GroupeTypeFormationAwareTrait
{
    /**
     * @var GroupeTypeFormation
     */
    private $groupeTypeFormation;





    /**
     * @param GroupeTypeFormation $groupeTypeFormation
     * @return self
     */
    public function setGroupeTypeFormation( GroupeTypeFormation $groupeTypeFormation = null )
    {
        $this->groupeTypeFormation = $groupeTypeFormation;
        return $this;
    }



    /**
     * @return GroupeTypeFormation
     */
    public function getGroupeTypeFormation()
    {
        return $this->groupeTypeFormation;
    }
}