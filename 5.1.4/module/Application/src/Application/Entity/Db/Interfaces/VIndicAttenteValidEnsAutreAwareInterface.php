<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicAttenteValidEnsAutre;

/**
 * Description of VIndicAttenteValidEnsAutreAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicAttenteValidEnsAutreAwareInterface
{
    /**
     * @param VIndicAttenteValidEnsAutre $vIndicAttenteValidEnsAutre
     * @return self
     */
    public function setVIndicAttenteValidEnsAutre( VIndicAttenteValidEnsAutre $vIndicAttenteValidEnsAutre = null );



    /**
     * @return VIndicAttenteValidEnsAutre
     */
    public function getVIndicAttenteValidEnsAutre();
}