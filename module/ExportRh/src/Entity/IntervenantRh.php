<?php

namespace ExportRh\Entity;

use Application\Entity\Db\AdresseNumeroCompl;
use Application\Entity\Db\Civilite;
use Application\Entity\Db\Departement;
use Application\Entity\Db\Discipline;
use Application\Entity\Db\Grade;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Pays;
use Application\Entity\Db\Statut;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Voirie;

class IntervenantRh
{
    public ?string             $code;

    public ?string             $codeRh;

    public ?string             $utilisateurCode;

    public ?Structure          $structure;

    public Statut              $statut;

    public ?Grade              $grade;

    public ?Discipline         $discipline;

    public ?Civilite           $civilite;

    public string              $nomUsuel;

    public string              $prenom;

    public \DateTime           $dateNaissance;

    public ?string             $nomPatronymique;

    public ?string             $communeNaissance;

    public ?Pays               $paysNaissance;

    public ?Departement        $departementNaissance;

    public ?Pays               $paysNationalite;

    public ?string             $telPro;

    public ?string             $telProDateDebut;

    public ?string             $telPerso;

    public ?string             $telPersoDateDebut;

    public ?string             $emailPro;

    public ?string             $emailProDateDebut;

    public ?string             $emailPerso;

    public ?string             $emailPersoDateDebut;

    public ?string             $adresseDateDebut;

    public ?string             $adressePrecisions;

    public ?string             $adresseNumero;

    public ?AdresseNumeroCompl $adresseNumeroCompl;

    public ?Voirie             $adresseVoirie;

    public ?string             $adresseVoie;

    public ?string             $adresseLieuDit;

    public ?string             $adresseCodePostal;

    public ?string             $adresseCommune;

    public ?Pays               $adressePays;

    public ?string             $numeroInsee;

    public bool                $numeroInseeProvisoire = false;

    public ?string             $IBAN;

    public ?string             $BIC;

    public bool                $ribHorsSepa           = false;

    public ?string             $autre1;

    public ?string             $autre2;

    public ?string             $autre3;

    public ?string             $autre4;

    public ?string             $autre5;

    public ?\DateTime          $validiteDebut;

    public ?\DateTime          $validiteFin;

    public ?string             $sourceCode;

    public ?Intervenant        $intervenant;



    /**
     * @return Intervenant|null
     */
    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }



    /**
     * @param Intervenant|null $intervenant
     *
     * @return IntervenantRh
     */
    public function setIntervenant(?Intervenant $intervenant): IntervenantRh
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return strtoupper($this->nomUsuel) . ' ' . ucfirst($this->prenom);
    }



    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }



    /**
     * @param string|null $code
     *
     * @return IntervenantRH
     */
    public function setCode(?string $code): IntervenantRH
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCodeRh(): ?string
    {
        return $this->codeRh;
    }



    /**
     * @param string|null $codeRh
     *
     * @return IntervenantRH
     */
    public function setCodeRh(?string $codeRh): IntervenantRH
    {
        $this->codeRh = $codeRh;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getUtilisateurCode(): ?string
    {
        return $this->utilisateurCode;
    }



    /**
     * @param string|null $utilisateurCode
     *
     * @return IntervenantRH
     */
    public function setUtilisateurCode(?string $utilisateurCode): IntervenantRH
    {
        $this->utilisateurCode = $utilisateurCode;

        return $this;
    }



    /**
     * @return Structure|null
     */
    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    /**
     * @param Structure|null $structure
     *
     * @return IntervenantRH
     */
    public function setStructure(?Structure $structure): IntervenantRH
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * @return Statut
     */
    public function getStatut(): Statut
    {
        return $this->statut;
    }



    /**
     * @param Statut $statut
     *
     * @return IntervenantRH
     */
    public function setStatut(Statut $statut): IntervenantRH
    {
        $this->statut = $statut;

        return $this;
    }



    /**
     * @return Grade|null
     */
    public function getGrade(): ?Grade
    {
        return $this->grade;
    }



    /**
     * @param Grade|null $grade
     *
     * @return IntervenantRH
     */
    public function setGrade(?Grade $grade): IntervenantRH
    {
        $this->grade = $grade;

        return $this;
    }



    /**
     * @return Discipline|null
     */
    public function getDiscipline(): ?Discipline
    {
        return $this->discipline;
    }



    /**
     * @param Discipline|null $discipline
     *
     * @return IntervenantRH
     */
    public function setDiscipline(?Discipline $discipline): IntervenantRH
    {
        $this->discipline = $discipline;

        return $this;
    }



    /**
     * @return Civilite|null
     */
    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }



    /**
     * @param Civilite|null $civilite
     *
     * @return IntervenantRH
     */
    public function setCivilite(?Civilite $civilite): IntervenantRH
    {
        $this->civilite = $civilite;

        return $this;
    }



    /**
     * @return string
     */
    public function getNomUsuel(): string
    {
        return $this->nomUsuel;
    }



    /**
     * @param string $nomUsuel
     *
     * @return IntervenantRH
     */
    public function setNomUsuel(string $nomUsuel): IntervenantRH
    {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }



    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }



    /**
     * @param string $prenom
     *
     * @return IntervenantRH
     */
    public function setPrenom(string $prenom): IntervenantRH
    {
        $this->prenom = $prenom;

        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getDateNaissance(): \DateTime
    {
        return $this->dateNaissance;
    }



    /**
     * @param \DateTime $dateNaissance
     *
     * @return IntervenantRH
     */
    public function setDateNaissance(\DateTime $dateNaissance): IntervenantRH
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getNomPatronymique(): ?string
    {
        return $this->nomPatronymique;
    }



    /**
     * @param string|null $nomPatronymique
     *
     * @return IntervenantRH
     */
    public function setNomPatronymique(?string $nomPatronymique): IntervenantRH
    {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCommuneNaissance(): ?string
    {
        return $this->communeNaissance;
    }



    /**
     * @param string|null $communeNaissance
     *
     * @return IntervenantRH
     */
    public function setCommuneNaissance(?string $communeNaissance): IntervenantRH
    {
        $this->communeNaissance = $communeNaissance;

        return $this;
    }



    /**
     * @return Pays|null
     */
    public function getPaysNaissance(): ?Pays
    {
        return $this->paysNaissance;
    }



    /**
     * @param Pays|null $paysNaissance
     *
     * @return IntervenantRH
     */
    public function setPaysNaissance(?Pays $paysNaissance): IntervenantRH
    {
        $this->paysNaissance = $paysNaissance;

        return $this;
    }



    /**
     * @return Departement|null
     */
    public function getDepartementNaissance(): ?Departement
    {
        return $this->departementNaissance;
    }



    /**
     * @param Departement|null $departementNaissance
     *
     * @return IntervenantRH
     */
    public function setDepartementNaissance(?Departement $departementNaissance): IntervenantRH
    {
        $this->departementNaissance = $departementNaissance;

        return $this;
    }



    /**
     * @return Pays|null
     */
    public function getPaysNationalite(): ?Pays
    {
        return $this->paysNationalite;
    }



    /**
     * @param Pays|null $paysNationalite
     *
     * @return IntervenantRH
     */
    public function setPaysNationalite(?Pays $paysNationalite): IntervenantRH
    {
        $this->paysNationalite = $paysNationalite;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getTelPro(): ?string
    {
        return $this->telPro;
    }



    /**
     * @param string|null $telPro
     *
     * @return IntervenantRH
     */
    public function setTelPro(?string $telPro): IntervenantRH
    {
        $this->telPro = $telPro;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getTelProDateDebut(): ?string
    {
        return $this->telProDateDebut;
    }



    /**
     * @param string|null $telProDateDebut
     *
     * @return IntervenantRH
     */
    public function setTelProDateDebut(?string $telProDateDebut): IntervenantRH
    {
        $this->telProDateDebut = $telProDateDebut;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getTelPerso(): ?string
    {
        return $this->telPerso;
    }



    /**
     * @param string|null $telPerso
     *
     * @return IntervenantRH
     */
    public function setTelPerso(?string $telPerso): IntervenantRH
    {
        $this->telPerso = $telPerso;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getTelPersoDateDebut(): ?string
    {
        return $this->telPersoDateDebut;
    }



    /**
     * @param string|null $telPersoDateDebut
     *
     * @return IntervenantRH
     */
    public function setTelPersoDateDebut(?string $telPersoDateDebut): IntervenantRH
    {
        $this->telPersoDateDebut = $telPersoDateDebut;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getEmailPro(): ?string
    {
        return $this->emailPro;
    }



    /**
     * @param string|null $emailPro
     *
     * @return IntervenantRH
     */
    public function setEmailPro(?string $emailPro): IntervenantRH
    {
        $this->emailPro = $emailPro;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getEmailProDateDebut(): ?string
    {
        return $this->emailProDateDebut;
    }



    /**
     * @param string|null $emailProDateDebut
     *
     * @return IntervenantRH
     */
    public function setEmailProDateDebut(?string $emailProDateDebut): IntervenantRH
    {
        $this->emailProDateDebut = $emailProDateDebut;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getEmailPerso(): ?string
    {
        return $this->emailPerso;
    }



    /**
     * @param string|null $emailPerso
     *
     * @return IntervenantRH
     */
    public function setEmailPerso(?string $emailPerso): IntervenantRH
    {
        $this->emailPerso = $emailPerso;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getEmailPersoDateDebut(): ?string
    {
        return $this->emailPersoDateDebut;
    }



    /**
     * @param string|null $emailPersoDateDebut
     *
     * @return IntervenantRH
     */
    public function setEmailPersoDateDebut(?string $emailPersoDateDebut): IntervenantRH
    {
        $this->emailPersoDateDebut = $emailPersoDateDebut;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseDateDebut(): ?string
    {
        return $this->adresseDateDebut;
    }



    /**
     * @param string|null $adresseDateDebut
     *
     * @return IntervenantRH
     */
    public function setAdresseDateDebut(?string $adresseDateDebut): IntervenantRH
    {
        $this->adresseDateDebut = $adresseDateDebut;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdressePrecisions(): ?string
    {
        return $this->adressePrecisions;
    }



    /**
     * @param string|null $adressePrecisions
     *
     * @return IntervenantRH
     */
    public function setAdressePrecisions(?string $adressePrecisions): IntervenantRH
    {
        $this->adressePrecisions = $adressePrecisions;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseNumero(): ?string
    {
        return $this->adresseNumero;
    }



    /**
     * @param string|null $adresseNumero
     *
     * @return IntervenantRH
     */
    public function setAdresseNumero(?string $adresseNumero): IntervenantRH
    {
        $this->adresseNumero = $adresseNumero;

        return $this;
    }



    /**
     * @return AdresseNumeroCompl|null
     */
    public function getAdresseNumeroCompl(): ?AdresseNumeroCompl
    {
        return $this->adresseNumeroCompl;
    }



    /**
     * @param AdresseNumeroCompl|null $adresseNumeroCompl
     *
     * @return IntervenantRH
     */
    public function setAdresseNumeroCompl(?AdresseNumeroCompl $adresseNumeroCompl): IntervenantRH
    {
        $this->adresseNumeroCompl = $adresseNumeroCompl;

        return $this;
    }



    /**
     * @return Voirie|null
     */
    public function getAdresseVoirie(): ?Voirie
    {
        return $this->adresseVoirie;
    }



    /**
     * @param Voirie|null $adresseVoirie
     *
     * @return IntervenantRH
     */
    public function setAdresseVoirie(?Voirie $adresseVoirie): IntervenantRH
    {
        $this->adresseVoirie = $adresseVoirie;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseVoie(): ?string
    {
        return $this->adresseVoie;
    }



    /**
     * @param string|null $adresseVoie
     *
     * @return IntervenantRH
     */
    public function setAdresseVoie(?string $adresseVoie): IntervenantRH
    {
        $this->adresseVoie = $adresseVoie;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseLieuDit(): ?string
    {
        return $this->adresseLieuDit;
    }



    /**
     * @param string|null $adresseLieuDit
     *
     * @return IntervenantRH
     */
    public function setAdresseLieuDit(?string $adresseLieuDit): IntervenantRH
    {
        $this->adresseLieuDit = $adresseLieuDit;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseCodePostal(): ?string
    {
        return $this->adresseCodePostal;
    }



    /**
     * @param string|null $adresseCodePostal
     *
     * @return IntervenantRH
     */
    public function setAdresseCodePostal(?string $adresseCodePostal): IntervenantRH
    {
        $this->adresseCodePostal = $adresseCodePostal;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseCommune(): ?string
    {
        return $this->adresseCommune;
    }



    /**
     * @param string|null $adresseCommune
     *
     * @return IntervenantRH
     */
    public function setAdresseCommune(?string $adresseCommune): IntervenantRH
    {
        $this->adresseCommune = $adresseCommune;

        return $this;
    }



    /**
     * @return Pays|null
     */
    public function getAdressePays(): ?Pays
    {
        return $this->adressePays;
    }



    /**
     * @param Pays|null $adressePays
     *
     * @return IntervenantRH
     */
    public function setAdressePays(?Pays $adressePays): IntervenantRH
    {
        $this->adressePays = $adressePays;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getNumeroInsee(): ?string
    {
        return $this->numeroInsee;
    }



    /**
     * @param string|null $numeroInsee
     *
     * @return IntervenantRH
     */
    public function setNumeroInsee(?string $numeroInsee): IntervenantRH
    {
        $this->numeroInsee = $numeroInsee;

        return $this;
    }



    /**
     * @return bool
     */
    public function isNumeroInseeProvisoire(): bool
    {
        return $this->numeroInseeProvisoire;
    }



    /**
     * @param bool $numeroInseeProvisoire
     *
     * @return IntervenantRH
     */
    public function setNumeroInseeProvisoire(bool $numeroInseeProvisoire): IntervenantRH
    {
        $this->numeroInseeProvisoire = $numeroInseeProvisoire;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getIBAN(): ?string
    {
        return $this->IBAN;
    }



    /**
     * @param string|null $IBAN
     *
     * @return IntervenantRH
     */
    public function setIBAN(?string $IBAN): IntervenantRH
    {
        $this->IBAN = $IBAN;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getBIC(): ?string
    {
        return $this->BIC;
    }



    /**
     * @param string|null $BIC
     *
     * @return IntervenantRH
     */
    public function setBIC(?string $BIC): IntervenantRH
    {
        $this->BIC = $BIC;

        return $this;
    }



    /**
     * @return bool
     */
    public function isRibHorsSepa(): bool
    {
        return $this->ribHorsSepa;
    }



    /**
     * @param bool $ribHorsSepa
     *
     * @return IntervenantRH
     */
    public function setRibHorsSepa(bool $ribHorsSepa): IntervenantRH
    {
        $this->ribHorsSepa = $ribHorsSepa;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre1(): ?string
    {
        return $this->autre1;
    }



    /**
     * @param string|null $autre1
     *
     * @return IntervenantRH
     */
    public function setAutre1(?string $autre1): IntervenantRH
    {
        $this->autre1 = $autre1;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre2(): ?string
    {
        return $this->autre2;
    }



    /**
     * @param string|null $autre2
     *
     * @return IntervenantRH
     */
    public function setAutre2(?string $autre2): IntervenantRH
    {
        $this->autre2 = $autre2;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre3(): ?string
    {
        return $this->autre3;
    }



    /**
     * @param string|null $autre3
     *
     * @return IntervenantRH
     */
    public function setAutre3(?string $autre3): IntervenantRH
    {
        $this->autre3 = $autre3;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre4(): ?string
    {
        return $this->autre4;
    }



    /**
     * @param string|null $autre4
     *
     * @return IntervenantRH
     */
    public function setAutre4(?string $autre4): IntervenantRH
    {
        $this->autre4 = $autre4;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre5(): ?string
    {
        return $this->autre5;
    }



    /**
     * @param string|null $autre5
     *
     * @return IntervenantRH
     */
    public function setAutre5(?string $autre5): IntervenantRH
    {
        $this->autre5 = $autre5;

        return $this;
    }



    /**
     * @return \DateTime|null
     */
    public function getValiditeDebut(): ?\DateTime
    {
        return $this->validiteDebut;
    }



    /**
     * @param \DateTime|null $validiteDebut
     *
     * @return IntervenantRH
     */
    public function setValiditeDebut(?\DateTime $validiteDebut): IntervenantRH
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }



    /**
     * @return \DateTime|null
     */
    public function getValiditeFin(): ?\DateTime
    {
        return $this->validiteFin;
    }



    /**
     * @param \DateTime|null $validiteFin
     *
     * @return IntervenantRH
     */
    public function setValiditeFin(?\DateTime $validiteFin): IntervenantRH
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getSourceCode(): ?string
    {
        return $this->sourceCode;
    }



    /**
     * @param string|null $sourceCode
     *
     * @return IntervenantRH
     */
    public function setSourceCode(?string $sourceCode): IntervenantRH
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }

}