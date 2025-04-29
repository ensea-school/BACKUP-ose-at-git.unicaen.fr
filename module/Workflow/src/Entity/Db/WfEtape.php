<?php

namespace Workflow\Entity\Db;

use Agrement\Entity\Db\TypeAgrement;

/**
 * WfEtape
 */
class WfEtape
{
    const CANDIDATURE_SAISIE                  = 'CANDIDATURE_SAISIE';
    const CANDIDATURE_VALIDATION              = 'CANDIDATURE_VALIDATION';

    const CODE_CLOTURE_REALISE                = 'CLOTURE_REALISE';

    const CODE_CONSEIL_ACADEMIQUE             = TypeAgrement::CODE_CONSEIL_ACADEMIQUE;

    const CODE_CONSEIL_RESTREINT              = TypeAgrement::CODE_CONSEIL_RESTREINT;

    const CODE_CONTRAT                        = 'CONTRAT';

    const CODE_DEMANDE_MEP                    = 'DEMANDE_MEP';

    const CODE_DONNEES_PERSO_SAISIE           = 'DONNEES_PERSO_SAISIE';

    const CODE_DONNEES_PERSO_VALIDATION       = 'DONNEES_PERSO_VALIDATION';

    const CODE_MISSION_PRIME                  = 'MISSION_PRIME';

    const CODE_MISSION_SAISIE                 = 'MISSION_SAISIE';

    const CODE_MISSION_SAISIE_REALISE         = 'MISSION_SAISIE_REALISE';

    const CODE_MISSION_VALIDATION             = 'MISSION_VALIDATION';

    const CODE_MISSION_VALIDATION_REALISE     = 'MISSION_VALIDATION_REALISE';

    const CODE_PJ_SAISIE                      = 'PJ_SAISIE';  // NB: c'est texto le code du type d'agrément

    const CODE_PJ_VALIDATION                  = 'PJ_VALIDATION'; // NB: c'est texto le code du type d'agrément

    const CODE_REFERENTIEL_VALIDATION         = 'REFERENTIEL_VALIDATION';

    const CODE_REFERENTIEL_VALIDATION_REALISE = 'REFERENTIEL_VALIDATION_REALISE';

    const CODE_SAISIE_MEP                     = 'SAISIE_MEP';

    const CODE_SERVICE_SAISIE                 = 'SERVICE_SAISIE';

    const CODE_SERVICE_SAISIE_REALISE         = 'SERVICE_SAISIE_REALISE';

    const CODE_SERVICE_VALIDATION             = 'SERVICE_VALIDATION';

    const CODE_SERVICE_VALIDATION_REALISE     = 'SERVICE_VALIDATION_REALISE';

    const CURRENT = 'current-etape';
    const NEXT    = 'next-etape';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelleIntervenant;

    /**
     * @var string
     */
    private $libelleAutres;

    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $routeIntervenant;

    /**
     * @var boolean
     */
    private $obligatoire;

    /**
     * @var string
     */
    private $descNonFranchie;

    /**
     * @var string
     */
    private $descSansObjectif;



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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param \Application\Acl\Role $role
     *
     * @return string
     */
    public function getLibelle(\Application\Acl\Role $role)
    {
        if ($role->getIntervenant()) {
            return $this->getLibelleIntervenant();
        } else {
            return $this->getLibelleAutres();
        }
    }



    /**
     * Get libelleIntervenant
     *
     * @return string
     */
    public function getLibelleIntervenant()
    {
        return $this->libelleIntervenant;
    }



    /**
     * Get libelleAutres
     *
     * @return string
     */
    public function getLibelleAutres()
    {
        return $this->libelleAutres;
    }



    public function getOrdre()
    {
        return $this->ordre;
    }



    public function getRoute()
    {
        return $this->route;
    }



    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }



    /**
     * @param string $routeIntervenant
     */
    public function getRouteIntervenant()
    {
        return $this->routeIntervenant;
    }



    /**
     * @return boolean
     */
    public function getObligatoire()
    {
        return $this->obligatoire;
    }



    /**
     * @param boolean $obligatoire
     *
     * @return WfEtape
     */
    public function setObligatoire($obligatoire)
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }



    /**
     * @return string
     */
    public function getDescNonFranchie()
    {
        return $this->descNonFranchie;
    }



    /**
     * @param string $descNonFranchie
     *
     * @return WfEtape
     */
    public function setDescNonFranchie($descNonFranchie)
    {
        $this->descNonFranchie = $descNonFranchie;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getDescSansObjectif()
    {
        return $this->descSansObjectif;
    }



    /**
     * @param mixed $descSansObjectif
     *
     * @return WfEtape
     */
    public function setDescSansObjectif($descSansObjectif)
    {
        $this->descSansObjectif = $descSansObjectif;

        return $this;
    }



    public function __toString()
    {
        return $this->getLibelleAutres();
    }
}
