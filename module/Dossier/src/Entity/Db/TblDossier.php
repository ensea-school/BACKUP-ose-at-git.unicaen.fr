<?php

namespace Dossier\Entity\Db;

use Application\Entity\Db\Annee;
use Intervenant\Entity\Db\Intervenant;
use Workflow\Entity\Db\Validation;

class TblDossier
{
    private int $id;

    private Annee $annee;

    private Intervenant $intervenant;

    private bool $actif;

    private ?IntervenantDossier $dossier;

    private ?Validation $validation;

    private bool $completudeIdentite;

    private bool $completudeIdentiteComp;

    private bool $completudeStatut;

    private bool $completudeContact;

    private bool $completudeAdresse;

    private bool $completudeInsee;

    private bool $completudeBanque;

    private bool $completudeEmployeur;

    private bool $completudeAutre1;

    private bool $completudeAutre2;

    private bool $completudeAutre3;

    private bool $completudeAutre4;

    private bool $completudeAutre5;

    private bool $completudeAvantRecrutement;

    private bool $completudeApresRecrutement;



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return Annee
     */
    public function getAnnee(): Annee
    {
        return $this->annee;
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    /**
     * @return bool
     */
    public function getActif(): bool
    {
        return $this->actif;
    }



    /**
     * @return IntervenantDossier|null
     */
    public function getDossier(): ?IntervenantDossier
    {
        return $this->dossier;
    }



    /**
     * @return Validation|null
     */
    public function getValidation(): ?Validation
    {
        return $this->validation;
    }



    /**
     * @return bool
     */
    public function getCompletudeIdentite(): bool
    {
        return $this->completudeIdentite;
    }



    /**
     * @return bool
     */
    public function getCompletudeIdentiteComp(): bool
    {
        return $this->completudeIdentiteComp;
    }



    /**
     * @return bool
     */
    public function getCompletudeStatut(): bool
    {
        return $this->completudeStatut;
    }



    /**
     * @return bool
     */
    public function getCompletudeContact(): bool
    {
        return $this->completudeContact;
    }



    /**
     * @return bool
     */
    public function getCompletudeAdresse(): bool
    {
        return $this->completudeAdresse;
    }



    /**
     * @return bool
     */
    public function getCompletudeInsee(): bool
    {
        return $this->completudeInsee;
    }



    /**
     * @return bool
     */
    public function getCompletudeBanque(): bool
    {
        return $this->completudeBanque;
    }



    /**
     * @return bool
     */
    public function getCompletudeEmployeur(): bool
    {
        return $this->completudeEmployeur;
    }



    /**
     * @return bool
     */
    public function getCompletudeAutre1(): bool
    {
        return $this->completudeAutre1;
    }



    /**
     * @return bool
     */
    public function getCompletudeAutre2(): bool
    {
        return $this->completudeAutre2;
    }



    /**
     * @return bool
     */
    public function getCompletudeAutre3(): bool
    {
        return $this->completudeAutre3;
    }



    /**
     * @return bool
     */
    public function getCompletudeAutre4(): bool
    {
        return $this->completudeAutre4;
    }



    /**
     * @return bool
     */
    public function getCompletudeAutre5(): bool
    {
        return $this->completudeAutre5;
    }



    public function getCompletudeAvantRecrutement(): bool
    {
        return $this->completudeAvantRecrutement;
    }



    public function getCompletudeApresRecrutement(): bool
    {
        return $this->completudeApresRecrutement;
    }

}
