<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Fichier;

/**
 * Description of FichierAwareInterface
 *
 * @author UnicaenCode
 */
interface FichierAwareInterface
{
    /**
     * @param Fichier $fichier
     * @return self
     */
    public function setFichier( Fichier $fichier = null );



    /**
     * @return Fichier
     */
    public function getFichier();
}