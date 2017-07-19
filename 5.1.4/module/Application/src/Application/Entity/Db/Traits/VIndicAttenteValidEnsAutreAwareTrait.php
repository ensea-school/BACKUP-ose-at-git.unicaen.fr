<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicAttenteValidEnsAutre;

/**
 * Description of VIndicAttenteValidEnsAutreAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicAttenteValidEnsAutreAwareTrait
{
    /**
     * @var VIndicAttenteValidEnsAutre
     */
    private $vIndicAttenteValidEnsAutre;





    /**
     * @param VIndicAttenteValidEnsAutre $vIndicAttenteValidEnsAutre
     * @return self
     */
    public function setVIndicAttenteValidEnsAutre( VIndicAttenteValidEnsAutre $vIndicAttenteValidEnsAutre = null )
    {
        $this->vIndicAttenteValidEnsAutre = $vIndicAttenteValidEnsAutre;
        return $this;
    }



    /**
     * @return VIndicAttenteValidEnsAutre
     */
    public function getVIndicAttenteValidEnsAutre()
    {
        return $this->vIndicAttenteValidEnsAutre;
    }
}