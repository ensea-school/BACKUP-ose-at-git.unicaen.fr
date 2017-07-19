<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\SituationFamiliale;

/**
 * Description of SituationFamilialeAwareTrait
 *
 * @author UnicaenCode
 */
trait SituationFamilialeAwareTrait
{
    /**
     * @var SituationFamiliale
     */
    private $situationFamiliale;





    /**
     * @param SituationFamiliale $situationFamiliale
     * @return self
     */
    public function setSituationFamiliale( SituationFamiliale $situationFamiliale = null )
    {
        $this->situationFamiliale = $situationFamiliale;
        return $this;
    }



    /**
     * @return SituationFamiliale
     */
    public function getSituationFamiliale()
    {
        return $this->situationFamiliale;
    }
}