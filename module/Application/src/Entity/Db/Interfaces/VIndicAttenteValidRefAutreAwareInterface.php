<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicAttenteValidRefAutre;

/**
 * Description of VIndicAttenteValidRefAutreAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicAttenteValidRefAutreAwareInterface
{
    /**
     * @param VIndicAttenteValidRefAutre $vIndicAttenteValidRefAutre
     * @return self
     */
    public function setVIndicAttenteValidRefAutre( VIndicAttenteValidRefAutre $vIndicAttenteValidRefAutre = null );



    /**
     * @return VIndicAttenteValidRefAutre
     */
    public function getVIndicAttenteValidRefAutre();
}