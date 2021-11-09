<?php

namespace ExportRh\Entity;

class IntervenantRHExportParams
{
    public bool $code                  = false;

    public bool $codeRh                = false;

    public bool $utilisateurCode       = false;

    public bool $structure             = false;

    public bool $statut                = false;

    public bool $grade                 = false;

    public bool $discipline            = false;

    public bool $civilite              = false;

    public bool $nomUsuel              = false;

    public bool $prenom                = false;

    public bool $dateNaissance         = false;

    public bool $nomPatronymique       = false;

    public bool $communeNaissance      = false;

    public bool $paysNaissance         = false;

    public bool $departementNaissance  = false;

    public bool $paysNationalite       = false;

    public bool $telPro                = false;

    public bool $telPerso              = false;

    public bool $emailPro              = false;

    public bool $emailPerso            = false;

    public bool $adressePrecisions     = false;

    public bool $adresseNumero         = false;

    public bool $adresseNumeroCompl    = false;

    public bool $adresseVoirie         = false;

    public bool $adresseVoie           = false;

    public bool $adresseLieuDit        = false;

    public bool $adresseCodePostal     = false;

    public bool $adresseCommune        = false;

    public bool $adressePays           = false;

    public bool $numeroInsee           = false;

    public bool $numeroInseeProvisoire = false;

    public bool $IBAN                  = false;

    public bool $BIC                   = false;

    public bool $ribHorsSepa           = false;

    public bool $autre1                = false;

    public bool $autre2                = false;

    public bool $autre3                = false;

    public bool $autre4                = false;

    public bool $autre5                = false;

    public bool $employeur             = false;

    public bool $validiteDebut         = false;

    public bool $validiteFin           = false;

    public bool $sourceCode            = false;



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

}