<?php

namespace UnicaenSiham\Entity;

use UnicaenSiham\Service\Siham;

class Agent
{

    protected $libelleLongCivilite;

    protected $nomPatronymique;

    protected $nomUsuel;

    protected $prenom;

    protected $numeroInseeDefinitif;

    protected $telephonePro;

    protected $telephoneProDateDebut;

    protected $telephoneProDateFin;

    protected $telephonePerso;

    protected $telephonePersoDateDebut;

    protected $telephonePersoDateFin;

    protected $faxPro;

    protected $emailPro;

    protected $emailProDateDebut;

    protected $emailProDateFin;

    protected $emailPerso;

    protected $emailPersoDateDebut;

    protected $emailPersoDateFin;

    protected $matricule;

    protected $numDossierHarpege;

    protected $dateNaissance;

    protected $paysNaissance;

    protected $villeNaissance;

    protected $bisTerAdresse;

    protected $natureVoieAdresse;

    protected $noVoieAdresse;

    protected $nomVoieAdresse;

    protected $complementAdresse;

    protected $codePostalAdresse;

    protected $bureauDistributeurAdresse;

    protected $ligneAdresseVoie;

    protected $codeTypologieAdresse;

    protected $codePaysISOAdresse;

    protected $codeDepartementAdresse;

    protected $codePaysINSEEAdresse;

    protected $dateDebutAdresse;

    protected $dateFinAdresse;

    protected $libLongPaysAdresse;

    protected $iban;

    protected $bic;



    /**
     * @return mixed
     */
    public function getLibelleLongCivilite()
    {
        return $this->libelleLongCivilite;
    }



    /**
     * @param mixed $libelleLongCivilite
     */
    public function setLibelleLongCivilite($libelleLongCivilite): self
    {
        $this->libelleLongCivilite = $libelleLongCivilite;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getNomPatronymique()
    {
        return $this->nomPatronymique;
    }



    /**
     * @param mixed $nomPatronymique
     */
    public function setNomPatronymique($nomPatronymique): self
    {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getNomUsuel()
    {
        return $this->nomUsuel;
    }



    /**
     * @param mixed $nomUsuel
     */
    public function setNomUsuel($nomUsuel): self
    {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }



    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getNumeroInseeDefinitif()
    {
        return $this->numeroInseeDefinitif;
    }



    /**
     * @param mixed $numeroInseeDefinitif
     */
    public function setNumeroInseeDefinitif($numeroInseeDefinitif): self
    {
        $this->numeroInseeDefinitif = $numeroInseeDefinitif;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getTelephonePro()
    {
        return $this->telephonePro;
    }



    /**
     * @param mixed $telephonePro
     */
    public function setTelephonePro($telephonePro): self
    {
        $this->telephonePro = $telephonePro;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getTelephoneProDateDebut()
    {
        return $this->telephoneProDateDebut;
    }



    /**
     * @param mixed $telephoneProDateDebut
     *
     * @return Agent
     */
    public function setTelephoneProDateDebut($telephoneProDateDebut)
    {
        $this->telephoneProDateDebut = $telephoneProDateDebut;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getTelephoneProDateFin()
    {
        return $this->telephoneProDateFin;
    }



    /**
     * @param mixed $telephoneProDateFin
     *
     * @return Agent
     */
    public function setTelephoneProDateFin($telephoneProDateFin)
    {
        $this->telephoneProDateFin = $telephoneProDateFin;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getTelephonePerso()
    {
        return $this->telephonePerso;
    }



    /**
     * @param mixed $telephonePerso
     *
     * @return Agent
     */
    public function setTelephonePerso($telephonePerso)
    {
        $this->telephonePerso = $telephonePerso;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getTelephonePersoDateDebut()
    {
        return $this->telephonePersoDateDebut;
    }



    /**
     * @param mixed $telephonePersoDateDebut
     *
     * @return Agent
     */
    public function setTelephonePersoDateDebut($telephonePersoDateDebut)
    {
        $this->telephonePersoDateDebut = $telephonePersoDateDebut;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getTelephonePersoDateFin()
    {
        return $this->telephonePersoDateFin;
    }



    /**
     * @param mixed $telephonePersoDateFin
     *
     * @return Agent
     */
    public function setTelephonePersoDateFin($telephonePersoDateFin)
    {
        $this->telephonePersoDateFin = $telephonePersoDateFin;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getFaxPro()
    {
        return $this->faxPro;
    }



    /**
     * @param mixed $faxPro
     */
    public function setFaxPro($faxPro): self
    {
        $this->faxPro = $faxPro;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getEmailPro()
    {
        return $this->emailPro;
    }



    /**
     * @param mixed $emailPro
     *
     * @return Agent
     */
    public function setEmailPro($emailPro)
    {
        $this->emailPro = $emailPro;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getEmailProDateDebut()
    {
        return $this->emailProDateDebut;
    }



    /**
     * @param mixed $emailProDateDebut
     *
     * @return Agent
     */
    public function setEmailProDateDebut($emailProDateDebut)
    {
        $this->emailProDateDebut = $emailProDateDebut;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getEmailProDateFin()
    {
        return $this->emailProDateFin;
    }



    /**
     * @param mixed $emailProDateFin
     *
     * @return Agent
     */
    public function setEmailProDateFin($emailProDateFin)
    {
        $this->emailProDateFin = $emailProDateFin;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getEmailPerso()
    {
        return $this->emailPerso;
    }



    /**
     * @param mixed $emailPerso
     *
     * @return Agent
     */
    public function setEmailPerso($emailPerso)
    {
        $this->emailPerso = $emailPerso;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getEmailPersoDateDebut()
    {
        return $this->emailPersoDateDebut;
    }



    /**
     * @param mixed $emailPersoDateDebut
     *
     * @return Agent
     */
    public function setEmailPersoDateDebut($emailPersoDateDebut)
    {
        $this->emailPersoDateDebut = $emailPersoDateDebut;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getEmailPersoDateFin()
    {
        return $this->emailPersoDateFin;
    }



    /**
     * @param mixed $emailPersoDateFin
     *
     * @return Agent
     */
    public function setEmailPersoDateFin($emailPersoDateFin)
    {
        $this->emailPersoDateFin = $emailPersoDateFin;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getMatricule()
    {
        return $this->matricule;
    }



    /**
     * @param mixed $matricule
     */
    public function setMatricule($matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getNumDossierHarpege()
    {
        return $this->numDossierHarpege;
    }



    /**
     * @param mixed $numDossierHarpege
     */
    public function setNumDossierHarpege($numDossierHarpege): self
    {
        $this->numDossierHarpege = $numDossierHarpege;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }



    /**
     * @param mixed $dateNaissance
     */
    public function setDateNaissance($dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getPaysNaissance()
    {
        return $this->paysNaissance;
    }



    /**
     * @param mixed $paysNaissance
     */
    public function setPaysNaissance($paysNaissance): self
    {
        $this->paysNaissance = $paysNaissance;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getVilleNaissance()
    {
        return $this->villeNaissance;
    }



    /**
     * @param mixed $villeNaissance
     */
    public function setVilleNaissance($villeNaissance): self
    {
        $this->villeNaissance = $villeNaissance;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getBisTerAdresse()
    {
        return $this->bisTerAdresse;
    }



    /**
     * @param mixed $bisTerAdresse
     *
     * @return Agent
     */
    public function setBisTerAdresse($bisTerAdresse)
    {
        $this->bisTerAdresse = $bisTerAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getNatureVoieAdresse()
    {
        return $this->natureVoieAdresse;
    }



    /**
     * @param mixed $natureVoieAdresse
     *
     * @return Agent
     */
    public function setNatureVoieAdresse($natureVoieAdresse)
    {
        $this->natureVoieAdresse = $natureVoieAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getNoVoieAdresse()
    {
        return $this->noVoieAdresse;
    }



    /**
     * @param mixed $noVoieAdresse
     *
     * @return Agent
     */
    public function setNoVoieAdresse($noVoieAdresse)
    {
        $this->noVoieAdresse = $noVoieAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getNomVoieAdresse()
    {
        return $this->nomVoieAdresse;
    }



    /**
     * @param mixed $nomVoieAdresse
     *
     * @return Agent
     */
    public function setNomVoieAdresse($nomVoieAdresse)
    {
        $this->nomVoieAdresse = $nomVoieAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getComplementAdresse()
    {
        return $this->complementAdresse;
    }



    /**
     * @param mixed $complemetAdresse
     *
     * @return Agent
     */
    public function setComplementAdresse($complementAdresse)
    {
        $this->complementAdresse = $complementAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getCodePostalAdresse()
    {
        return $this->codePostalAdresse;
    }



    /**
     * @param mixed $codePostalAdresse
     *
     * @return Agent
     */
    public function setCodePostalAdresse($codePostalAdresse)
    {
        $this->codePostalAdresse = $codePostalAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getBureauDistributeurAdresse()
    {
        return $this->bureauDistributeurAdresse;
    }



    /**
     * @param mixed $bureauDistributeurAdresse
     *
     * @return Agent
     */
    public function setBureauDistributeurAdresse($bureauDistributeurAdresse)
    {
        $this->bureauDistributeurAdresse = $bureauDistributeurAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getLigneAdresseVoie()
    {
        return $this->ligneAdresseVoie;
    }



    /**
     * @param mixed $ligneAdresseVoie
     *
     * @return Agent
     */
    public function setLigneAdresseVoie($ligneAdresseVoie)
    {
        $this->ligneAdresseVoie = $ligneAdresseVoie;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getCodeTypologieAdresse()
    {
        return $this->codeTypologieAdresse;
    }



    /**
     * @param mixed $codeTypologieAdresse
     *
     * @return Agent
     */
    public function setCodeTypologieAdresse($codeTypologieAdresse)
    {
        $this->codeTypologieAdresse = $codeTypologieAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getCodePaysISOAdresse()
    {
        return $this->codePaysISOAdresse;
    }



    /**
     * @param mixed $codePaysISOAdresse
     *
     * @return Agent
     */
    public function setCodePaysISOAdresse($codePaysISOAdresse)
    {
        $this->codePaysISOAdresse = $codePaysISOAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getCodeDepartementAdresse()
    {
        return $this->codeDepartementAdresse;
    }



    /**
     * @param mixed $codeDepartementAdresse
     *
     * @return Agent
     */
    public function setCodeDepartementAdresse($codeDepartementAdresse)
    {
        $this->codeDepartementAdresse = $codeDepartementAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getCodePaysINSEEAdresse()
    {
        return $this->codePaysINSEEAdresse;
    }



    /**
     * @param mixed $codePaysINSEEAdresse
     *
     * @return Agent
     */
    public function setCodePaysINSEEAdresse($codePaysINSEEAdresse)
    {
        $this->codePaysINSEEAdresse = $codePaysINSEEAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getDateDebutAdresse()
    {
        return $this->dateDebutAdresse;
    }



    /**
     * @param mixed $dateDebutAdresse
     *
     * @return Agent
     */
    public function setDateDebutAdresse($dateDebutAdresse)
    {
        $this->dateDebutAdresse = $dateDebutAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getDateFinAdresse()
    {
        return $this->dateFinAdresse;
    }



    /**
     * @param mixed $dateFinAdresse
     *
     * @return Agent
     */
    public function setDateFinAdresse($dateFinAdresse)
    {
        $this->dateFinAdresse = $dateFinAdresse;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getLibLongPaysAdresse()
    {
        return $this->libLongPaysAdresse;
    }



    /**
     * @param mixed $libLongPaysAdresse
     *
     * @return Agent
     */
    public function setLibLongPaysAdresse($libLongPaysAdresse)
    {
        $this->libLongPaysAdresse = $libLongPaysAdresse;

        return $this;
    }



    /**
     * @param mixed $iban
     *
     * @return Agent
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getIban()
    {
        return $this->iban;
    }



    /**
     * @param mixed $bic
     *
     * @return Agent
     */
    public function setBic($bic)
    {
        $this->bic = $bic;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getBic()
    {
        return $this->bic;
    }



    public function mapper($values): Agent
    {

        foreach ($values as $property => $value) {
            if ($property == 'donneesPersonnelles') {
                foreach ($value as $k => $v) {
                    if (method_exists($this, $setter = 'set' . ucFirst($k))) {
                        $this->$setter($v);
                    }
                }
            }
            if ($property == 'listeAdresses') {
                $adresses = [];
                if (is_array($value)) {
                    $adresses = $value;
                } else {
                    $adresses[] = $value;
                }

                //On traiter uniquement l'adresse principale de l'agent pour le moment
                foreach ($adresses as $adresse) {
                    if ($adresse->codeTypologieAdresse == Siham::SIHAM_CODE_TYPOLOGIE_ADRESSE_PRINCIPALE) {
                        foreach ($adresse as $k => $v) {
                            if (method_exists($this, $setter = 'set' . ucFirst($k))) {
                                $this->$setter($v);
                            }
                        }
                    }
                }
            }

            if ($property == 'listeNumerosMails') {
                $numeros = [];
                if (is_array($value)) {
                    $numeros = $value;
                } else {
                    $numeros[] = $value;
                }


                foreach ($numeros as $numero) {
                    if ($numero->codeTypologieNumeroMail == Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO) {
                        $this->setTelephonePro($numero->numeroMail);
                        $this->setTelephoneProDateDebut($numero->dateDebutTelephone);
                    }
                    if ($numero->codeTypologieNumeroMail == Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO) {
                        $this->setTelephonePerso($numero->numeroMail);
                        $this->setTelephonePersoDateDebut($numero->dateDebutTelephone);
                    }
                    if ($numero->codeTypologieNumeroMail == Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO) {
                        $this->setEmailPro($numero->numeroMail);
                        $this->setEmailProDateDebut($numero->dateDebutTelephone);
                    }
                    if ($numero->codeTypologieNumeroMail == Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO) {
                        $this->setEmailPerso($numero->numeroMail);
                        $this->setEmailPersoDateDebut($numero->dateDebutTelephone);
                    }
                }
            }


            if (method_exists($this, $setter = 'set' . ucFirst($property))) {
                $this->$setter($value);
            }
        }

        //Traitement de l'IBAN et BIC
        if (isset($values->donneesPersonnelles)) {
            $dp   = $values->donneesPersonnelles;
            $iban = '';
            $iban .= isset($dp->IBAN1CoordBanc) ? $dp->IBAN1CoordBanc : '';
            $iban .= isset($dp->IBAN2CoordBanc) ? $dp->IBAN2CoordBanc : '';
            $iban .= isset($dp->IBAN3CoordBanc) ? $dp->IBAN3CoordBanc : '';
            $iban .= isset($dp->IBAN4CoordBanc) ? $dp->IBAN4CoordBanc : '';
            $iban .= isset($dp->IBAN5CoordBanc) ? $dp->IBAN5CoordBanc : '';
            $iban .= isset($dp->IBAN6CoordBanc) ? $dp->IBAN6CoordBanc : '';
            $iban .= isset($dp->IBAN7CoordBanc) ? $dp->IBAN7CoordBanc : '';
            $iban .= isset($dp->IBAN8CoordBanc) ? $dp->IBAN8CoordBanc : '';
            $iban .= isset($dp->IBAN9CoordBanc) ? $dp->IBAN9CoordBanc : '';

            $this->setIban($iban);

            $bic = '';
            $bic .= isset($dp->typeBanqueCoordBanc) ? $dp->typeBanqueCoordBanc : '';
            $bic .= isset($dp->codePaysCoordBanc) ? $dp->codePaysCoordBanc : '';
            $bic .= isset($dp->localisationCoordBanc) ? $dp->localisationCoordBanc : '';
            $bic .= isset($dp->agenceCoordBanc) ? $dp->agenceCoordBanc : '';

            $this->setBic($bic);
        };


        return $this;
    }
}