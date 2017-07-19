<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\GroupeTypeFormation;

/**
 * Description of GroupeTypeFormationAwareInterface
 *
 * @author UnicaenCode
 */
interface GroupeTypeFormationAwareInterface
{
    /**
     * @param GroupeTypeFormation $groupeTypeFormation
     * @return self
     */
    public function setGroupeTypeFormation( GroupeTypeFormation $groupeTypeFormation = null );



    /**
     * @return GroupeTypeFormation
     */
    public function getGroupeTypeFormation();
}