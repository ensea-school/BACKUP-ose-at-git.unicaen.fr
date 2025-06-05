<?php

namespace Service\Entity\Db;

use Application\Provider\Privilege\Privileges;
use Workflow\Entity\Db\WfEtape;

class TypeVolumeHoraire
{

    const CODE_PREVU   = 'PREVU';
    const CODE_REALISE = 'REALISE';

    static public  $codes = [
        self::CODE_PREVU,
        self::CODE_REALISE,
    ];

    private int    $id;

    private string $code;

    private string $libelle;

    private int    $ordre;



    public function getId(): int
    {
        return $this->id;
    }



    public function getCode(): string
    {
        return $this->code;
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }



    public function getOrdre(): int
    {
        return $this->ordre;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }



    public function isPrevu(): bool
    {
        return self::CODE_PREVU === $this->getCode();
    }



    public function isRealise(): bool
    {
        return self::CODE_REALISE === $this->getCode();
    }



    public function getPrivilegeEnseignementVisualisation(): string
    {
        if ($this->isPrevu()) {
            return Privileges::ENSEIGNEMENT_PREVU_VISUALISATION;
        }
        if ($this->isRealise()) {
            return Privileges::ENSEIGNEMENT_REALISE_VISUALISATION;
        }
    }



    public function getPrivilegeEnseignementEdition(): string
    {
        if ($this->isPrevu()) {
            return Privileges::ENSEIGNEMENT_PREVU_EDITION;
        }
        if ($this->isRealise()) {
            return Privileges::ENSEIGNEMENT_REALISE_EDITION;
        }
    }



    public function getPrivilegeReferentielVisualisation(): string
    {
        if ($this->isPrevu()) {
            return Privileges::REFERENTIEL_PREVU_VISUALISATION;
        }
        if ($this->isRealise()) {
            return Privileges::REFERENTIEL_REALISE_VISUALISATION;
        }
    }



    public function getPrivilegeReferentielEdition(): string
    {
        if ($this->isPrevu()) {
            return Privileges::REFERENTIEL_PREVU_EDITION;
        }
        if ($this->isRealise()) {
            return Privileges::REFERENTIEL_REALISE_EDITION;
        }
    }



    public function getPrivilegeEnseignementValidation(): string
    {
        if ($this->isPrevu()) {
            return Privileges::ENSEIGNEMENT_PREVU_VALIDATION;
        }
        if ($this->isRealise()) {
            return Privileges::ENSEIGNEMENT_REALISE_VALIDATION;
        }
    }



    public function getPrivilegeEnseignementAutoValidation(): string
    {
        if ($this->isPrevu()) {
            return Privileges::ENSEIGNEMENT_PREVU_AUTOVALIDATION;
        }
        if ($this->isRealise()) {
            return Privileges::ENSEIGNEMENT_REALISE_AUTOVALIDATION;
        }
    }



    public function getPrivilegeReferentielValidation(): string
    {
        if ($this->isPrevu()) {
            return Privileges::REFERENTIEL_PREVU_VALIDATION;
        }
        if ($this->isRealise()) {
            return Privileges::REFERENTIEL_REALISE_VALIDATION;
        }
    }



    public function getPrivilegeReferentielAutoValidation(): string
    {
        if ($this->isPrevu()) {
            return Privileges::REFERENTIEL_PREVU_AUTOVALIDATION;
        }
        if ($this->isRealise()) {
            return Privileges::REFERENTIEL_REALISE_AUTOVALIDATION;
        }
    }



    public function getWfEtapeServiceSaisie(): string
    {
        if ($this->isPrevu()) {
            return WfEtape::CODE_SERVICE_SAISIE;
        }
        if ($this->isRealise()) {
            return WfEtape::CODE_SERVICE_SAISIE_REALISE;
        }
    }



    public function getWfEtapeEnseignementValidation(): string
    {
        if ($this->isPrevu()) {
            return WfEtape::CODE_SERVICE_VALIDATION;
        }
        if ($this->isRealise()) {
            return WfEtape::CODE_SERVICE_VALIDATION_REALISE;
        }
    }



    public function getWfEtapeReferentielValidation(): string
    {
        if ($this->isPrevu()) {
            return WfEtape::CODE_REFERENTIEL_VALIDATION;
        }
        if ($this->isRealise()) {
            return WfEtape::CODE_REFERENTIEL_VALIDATION_REALISE;
        }
    }
}
