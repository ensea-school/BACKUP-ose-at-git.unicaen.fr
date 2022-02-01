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
    protected ?Fichier $fichier;



    /**
     * @param Fichier|null $fichier
     *
     * @return self
     */
    public function setFichier( ?Fichier $fichier )
    {
        $this->fichier = $fichier;

        return $this;
    }



    public function getFichier(): ?Fichier
    {
        return $this->fichier;
    }
}