<?php

namespace Contrat\Entity\Db;


/**
 * Description of ContratServiceListeAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratServiceListeAwareTrait
{
    protected ?ContratServiceListe $contratServiceListe = null;



    /**
     * @param ContratServiceListe $contratServiceListe
     *
     * @return self
     */
    public function setContratServiceListe(?ContratServiceListe $contratServiceListe)
    {
        $this->contratServiceListe = $contratServiceListe;

        return $this;
    }



    public function getContratServiceListe(): ?ContratServiceListe
    {
        return $this->contratServiceListe;
    }
}

