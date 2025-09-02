<?php

namespace Workflow\Entity\Db;

use Administration\Interfaces\ParametreEntityInterface;
use Administration\Traits\ParametreEntityTrait;
use Application\Acl\Role;
use Application\Entity\Db\Perimetre;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class WorkflowEtape implements ParametreEntityInterface
{
    use ParametreEntityTrait;

    const CANDIDATURE_SAISIE              = 'candidature_saisie';
    const DONNEES_PERSO_SAISIE            = 'donnees_perso_saisie';
    const PJ_SAISIE                       = 'pj_saisie';
    const DONNEES_PERSO_VALIDATION        = 'donnees_perso_validation';
    const PJ_VALIDATION                   = 'pj_validation';
    const CANDIDATURE_VALIDATION          = 'candidature_validation';
    const DONNEES_PERSO_COMPL_SAISIE      = 'donnees_perso_compl_saisie';
    const PJ_COMPL_SAISIE                 = 'pj_compl_saisie';
    const DONNEES_PERSO_COMPL_VALIDATION  = 'donnees_perso_compl_validation';
    const PJ_COMPL_VALIDATION             = 'pj_compl_validation';
    const ENSEIGNEMENT_SAISIE             = 'enseignement_saisie';
    const REFERENTIEL_SAISIE              = 'referentiel_saisie';
    const MISSION_SAISIE                  = 'mission_saisie';
    const ENSEIGNEMENT_VALIDATION         = 'enseignement_validation';
    const REFERENTIEL_VALIDATION          = 'referentiel_validation';
    const MISSION_VALIDATION              = 'mission_validation';
    const CONSEIL_RESTREINT               = 'conseil_restreint';
    const CONSEIL_ACADEMIQUE              = 'conseil_academique';
    const CONTRAT                         = 'contrat';
    const EXPORT_RH                       = 'export_rh';
    const ENSEIGNEMENT_SAISIE_REALISE     = 'enseignement_saisie_realise';
    const REFERENTIEL_SAISIE_REALISE      = 'referentiel_saisie_realise';
    const MISSION_SAISIE_REALISE          = 'mission_saisie_realise';
    const CLOTURE_REALISE                 = 'cloture_realise';
    const ENSEIGNEMENT_VALIDATION_REALISE = 'enseignement_validation_realise';
    const REFERENTIEL_VALIDATION_REALISE  = 'referentiel_validation_realise';
    const MISSION_VALIDATION_REALISE      = 'mission_validation_realise';
    const MISSION_PRIME                   = 'mission_prime';
    const DEMANDE_MEP                     = 'demande_mep';
    const SAISIE_MEP                      = 'saisie_mep';

    const CURRENT = 'current-etape';
    const NEXT    = 'next-etape';

    private string    $code;
    private Perimetre $perimetre;
    private string    $route;
    private ?string   $routeIntervenant;
    private string    $libelleIntervenant;
    private string    $libelleAutres;
    private string    $descNonFranchie;
    private ?string   $descSansObjectif;
    private int       $ordre;

    /**
     * @var array|WorkflowEtape[]
     */
    private array $contraintes = [];

    /**
     * @var array|string[]
     */
    private array $avancements = [];

    private Collection $dependances;



    public function __construct()
    {
        $this->dependances = new ArrayCollection();
    }



    public function __toString()
    {
        return $this->getLibelleAutres();
    }



    public function getLibelle(?Role $role = null): string
    {
        if ($role && $role->getIntervenant()) {
            return $this->getLibelleIntervenant();
        } else {
            return $this->getLibelleAutres();
        }
    }



    public function getCode(): string
    {
        return $this->code;
    }



    /**
     * @return array|WorkflowEtape[]
     */
    public function getContraintes(): array
    {
        return $this->contraintes;
    }



    public function __addContrainte(WorkflowEtape $etape): void
    {
        $this->contraintes[$etape->getCode()] = $etape;
    }



    public function __addAvancement(string $avancement, string $description): void
    {
        $this->avancements[$avancement] = $description;
    }



    public function getAvancements(): array
    {
        return $this->avancements;
    }



    public function getPerimetre(): Perimetre
    {
        return $this->perimetre;
    }



    public function getRoute(): string
    {
        return $this->route;
    }



    public function getRouteIntervenant(): ?string
    {
        return $this->routeIntervenant;
    }



    public function getLibelleIntervenant(): string
    {
        return $this->libelleIntervenant;
    }



    public function setLibelleIntervenant(string $libelleIntervenant): WorkflowEtape
    {
        $this->libelleIntervenant = $libelleIntervenant;
        return $this;
    }



    public function getLibelleAutres(): string
    {
        return $this->libelleAutres;
    }



    public function setLibelleAutres(string $libelleAutres): WorkflowEtape
    {
        $this->libelleAutres = $libelleAutres;
        return $this;
    }



    public function getDescNonFranchie(): string
    {
        return $this->descNonFranchie;
    }



    public function setDescNonFranchie(string $descNonFranchie): WorkflowEtape
    {
        $this->descNonFranchie = $descNonFranchie;
        return $this;
    }



    public function getDescSansObjectif(): ?string
    {
        return $this->descSansObjectif;
    }



    public function setDescSansObjectif(?string $descSansObjectif): WorkflowEtape
    {
        $this->descSansObjectif = $descSansObjectif;
        return $this;
    }



    public function getOrdre(): int
    {
        return $this->ordre;
    }



    public function setOrdre(int $ordre): WorkflowEtape
    {
        $this->ordre = $ordre;
        return $this;
    }



    /**
     * @return Collection|array|WorkflowEtapeDependance[]
     */
    public function getDependances(): Collection|array
    {
        return $this->dependances;
    }



    public function addDependance(WorkflowEtapeDependance $dependance): WorkflowEtape
    {
        $this->dependances->add($dependance);
        return $this;
    }



    public function removeDependance(WorkflowEtapeDependance $dependance): WorkflowEtape
    {
        $this->dependances->remove($dependance);
        return $this;
    }

}