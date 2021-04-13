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

    protected $faxPro;

    protected $mailPro;

    protected $matricule;

    protected $numDossierHarpege;

    protected $dateNaissance;

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

    protected $libLongPaysAdresse;



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
    public function getMailPro()
    {
        return $this->mailPro;
    }



    /**
     * @param mixed $mailPro
     */
    public function setMailPro($mailPro): self
    {
        $this->mailPro = $mailPro;

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

                //On traiter uniquement l'adresse principale de l'agent
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
            if (method_exists($this, $setter = 'set' . ucFirst($property))) {
                $this->$setter($value);
            }
        }

        return $this;
    }
}