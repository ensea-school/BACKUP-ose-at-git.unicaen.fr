<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicAttenteValidRefAutre;

/**
 * Description of VIndicAttenteValidRefAutreAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicAttenteValidRefAutreAwareTrait
{
    /**
     * @var VIndicAttenteValidRefAutre
     */
    private $vIndicAttenteValidRefAutre;





    /**
     * @param VIndicAttenteValidRefAutre $vIndicAttenteValidRefAutre
     * @return self
     */
    public function setVIndicAttenteValidRefAutre( VIndicAttenteValidRefAutre $vIndicAttenteValidRefAutre = null )
    {
        $this->vIndicAttenteValidRefAutre = $vIndicAttenteValidRefAutre;
        return $this;
    }



    /**
     * @return VIndicAttenteValidRefAutre
     */
    public function getVIndicAttenteValidRefAutre()
    {
        return $this->vIndicAttenteValidRefAutre;
    }
}