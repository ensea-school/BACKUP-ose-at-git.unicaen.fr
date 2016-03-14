<?php

namespace Application\Entity\Db;

use Application\Acl\Role;

/**
 * WfEtape
 */
class WfEtape
{
    const CODE_DONNEES_PERSO_SAISIE           = 'DONNEES_PERSO_SAISIE';
    const CODE_SERVICE_SAISIE                 = 'SERVICE_SAISIE';
    const CODE_PJ_SAISIE                      = 'PJ_SAISIE';
    const CODE_PJ_VALIDATION                  = 'PJ_VALIDATION';
    const CODE_DONNEES_PERSO_VALIDATION       = 'DONNEES_PERSO_VALIDATION';
    const CODE_SERVICE_VALIDATION             = 'SERVICE_VALIDATION';
    const CODE_REFERENTIEL_VALIDATION         = 'REFERENTIEL_VALIDATION';
    const CODE_CONSEIL_RESTREINT              = TypeAgrement::CODE_CONSEIL_RESTREINT;  // NB: c'est texto le code du type d'agrément
    const CODE_CONSEIL_ACADEMIQUE             = TypeAgrement::CODE_CONSEIL_ACADEMIQUE; // NB: c'est texto le code du type d'agrément
    const CODE_CONTRAT                        = 'CONTRAT';
    const CODE_SERVICE_SAISIE_REALISE         = 'SERVICE_SAISIE_REALISE';
    const CODE_SERVICE_VALIDATION_REALISE     = 'SERVICE_VALIDATION_REALISE';
    const CODE_REFERENTIEL_VALIDATION_REALISE = 'REFERENTIEL_VALIDATION_REALISE';

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



    /**
     * @param Role $role
     *
     * @return string
     */
    public function getLibelle(Role $role)
    {
        if ($role->getIntervenant()) {
            return $this->getLibelleIntervenant();
        } else {
            return $this->getLibelleAutres();
        }
    }



    public function getOrdre()
    {
        return $this->ordre;
    }



    public function getRoute()
    {
        return $this->route;
    }



    public function __toString()
    {
        return $this->getLibelleAutres();
    }
}
