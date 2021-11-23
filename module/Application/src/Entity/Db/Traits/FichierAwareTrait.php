<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Fichier;

/**
 * Description of FichierAwareTrait
 *
 * @author UnicaenCode
 */
trait FichierAwareTrait
{
    /**
     * @var Fichier
     */
    private $fichier;





    /**
     * @param Fichier $fichier
     * @return self
     */
    public function setFichier( Fichier $fichier = null )
    {
        $this->fichier = $fichier;
        return $this;
    }



    /**
     * @return Fichier
     */
    public function getFichier()
    {
        return $this->fichier;
    }
}