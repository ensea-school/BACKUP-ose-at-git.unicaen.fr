<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\SituationFamiliale;

/**
 * Description of SituationFamilialeAwareInterface
 *
 * @author UnicaenCode
 */
interface SituationFamilialeAwareInterface
{
    /**
     * @param SituationFamiliale $situationFamiliale
     * @return self
     */
    public function setSituationFamiliale( SituationFamiliale $situationFamiliale = null );



    /**
     * @return SituationFamiliale
     */
    public function getSituationFamiliale();
}