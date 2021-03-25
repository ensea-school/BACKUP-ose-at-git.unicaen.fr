<?php

namespace ExportRh\Entity;

use Application\Entity\Db\AdresseNumeroCompl;
use Application\Entity\Db\Civilite;
use Application\Entity\Db\Departement;
use Application\Entity\Db\Discipline;
use Application\Entity\Db\Grade;
use Application\Entity\Db\Pays;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Voirie;

class Intervenant
{
    public string              $code;

    public ?string             $codeRh;

    public ?string             $utilisateurCode;

    public ?Structure          $structure;

    public StatutIntervenant   $statut;

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

    public ?string             $telPerso;

    public ?string             $emailPro;

    public ?string             $emailPerso;

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

    public ?Employeur          $employeur;

    public ?\DateTime          $validiteDebut;

    public ?\DateTime          $validiteFin;

    public ?string             $sourceCode;



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return strtoupper($this->nomUsuel) . ' ' . ucfirst($this->prenom);
    }



    public function toArray(): array
    {
        $array = [];
        $vars  = get_class_vars(__CLASS__);
        foreach ($vars as $var => $null) {
            $array[$var] = $this->$var;
        }

        return $array;
    }



    public function fromArray(array $array): self
    {
        $vars = get_class_vars(__CLASS__);
        foreach ($vars as $var => $default) {
            if (array_key_exists($var, $array)) {
                $this->$var = $array[$var];
            } else {
                $this->$var = $default;
            }
        }

        return $this;
    }



    public function fromIntervenant(\Application\Entity\Db\Intervenant $intervenant): self
    {
        $vars = get_class_vars(__CLASS__);
        foreach ($vars as $var => $null) {
            $this->$var = $intervenant->{'get' . ucfirst($var)}();
        }

        return $this;
    }
}