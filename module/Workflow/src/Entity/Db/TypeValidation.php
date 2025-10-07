<?php

namespace Workflow\Entity\Db;

class TypeValidation
{
    const CANDIDATURE        = 'CANDIDATURE';
    const CLOTURE_REALISE    = 'CLOTURE_REALISE';
    const CONTRAT            = 'CONTRAT_PAR_COMP';
    const DECLARATION_PRIME  = 'DECLARATION_PRIME';
    const DONNEES_PERSO      = 'DONNEES_PERSO_PAR_COMP';
    const DONNEES_PERSO_COMP = 'DONNEES_PERSO_COMPLEMENTAIRE_PAR_COMP';
    const ENSEIGNEMENT       = 'SERVICES_PAR_COMP';
    const FICHIER            = 'FICHIER';
    const MISSION            = 'MISSION';
    const MISSION_REALISE    = 'MISSION_REALISE';
    const OFFRE_EMPLOI       = 'OFFRE_EMPLOI';
    const PIECE_JOINTE       = 'PIECE_JOINTE';
    const REFERENTIEL        = 'REFERENTIEL';

    private string $code;
    private string $libelle;
    private int    $id;



    public function getId(): int
    {
        return $this->id;
    }



    public function getCode(): string
    {
        return $this->code;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }

}
