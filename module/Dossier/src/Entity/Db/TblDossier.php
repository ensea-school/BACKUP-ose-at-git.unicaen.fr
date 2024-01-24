<?php

namespace Dossier\Entity\Db;


use Application\Entity\Db\Annee;
use Application\Entity\Db\Validation;
use Intervenant\Entity\Db\Intervenant;

class TblDossier
{
    private int                 $id;

    private Annee               $annee;

    private Intervenant         $intervenant;

    private bool                $actif;

    private ?IntervenantDossier $dossier;

    private ?Validation         $validation;

    private bool                $completudeIdentite;

    private bool                $completudeIdentiteComp;

    private bool                $completudeStatut;

    private bool                $completudeContact;

    private bool                $completudeAdresse;

    private bool                $completudeInsee;

    private bool                $completudeBanque;

    private bool                $completudeEmployeur;

    private bool                $completudeAutres;



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
    public function getCompletudeAutres(): bool
    {
        return $this->completudeAutres;
    }



    public function getCompletude(): bool
    {
        return $this->getCompletudeIdentite() &&
            $this->getCompletudeIdentiteComp() &&
            $this->getCompletudeAdresse() &&
            $this->getCompletudeContact() &&
            $this->getCompletudeInsee() &&
            $this->getCompletudeBanque() &&
            $this->getCompletudeEmployeur() &&
            $this->getCompletudeAutres() &&
            $this->getCompletudeStatut();
    }
}