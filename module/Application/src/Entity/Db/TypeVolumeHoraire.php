<?php

namespace Application\Entity\Db;

use Application\Provider\Privilege\Privileges;

/**
 * TypeVolumeHoraire
 */
class TypeVolumeHoraire
{

    const CODE_PREVU   = 'PREVU';
    const CODE_REALISE = 'REALISE';

    static public $codes = [
        self::CODE_PREVU,
        self::CODE_REALISE,
    ];

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var integer
     */
    private $id;



    /**
     * Retourne <code>true</code> si le code de ce type de volume horaire est PREVU.
     *
     * @return boolean
     */
    public function isPrevu()
    {
        return self::CODE_PREVU === $this->getCode();
    }



    /**
     * Retourne <code>true</code> si le code de ce type de volume horaire est REALISE.
     *
     * @return boolean
     */
    public function isRealise()
    {
        return self::CODE_REALISE === $this->getCode();
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeVolumeHoraire
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeVolumeHoraire
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return TypeVolumeHoraire
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
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
}
