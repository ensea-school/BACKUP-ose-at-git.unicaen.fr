<?php

namespace UnicaenSiham\Entity;

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



    public function mapper($values): Agent
    {
        foreach ($values as $property => $value) {
            if (method_exists($this, $setter = 'set' . ucFirst($property))) {
                $this->$setter($value);
            }
        }

        return $this;
    }

}