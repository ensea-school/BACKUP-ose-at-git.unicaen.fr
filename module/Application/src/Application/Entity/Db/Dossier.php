<?php

namespace Application\Entity\Db;

/**
 * Dossier
 */
class Dossier implements HistoriqueAwareInterface
{
    /**
     * @var string
     */
    private $adresse;

    /**
     * @var string
     */
    private $civilite;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var \DateTime
     */
    private $histoCreation;

    /**
     * @var \DateTime
     */
    private $histoDestruction;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var string
     */
    private $nomPatronymique;

    /**
     * @var string
     */
    private $nomUsuel;

    /**
     * @var string
     */
    private $numeroInsee;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $rib;

    /**
     * @var string
     */
    private $statut;

    /**
     * @var string
     */
    private $typeEmployeur;

    /**
     * @var boolean
     */
    private $premierRecrutement;

    /**
     * @var boolean
     */
    private $perteEmploi;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoCreateur;


    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Dossier
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set civilite
     *
     * @param string $civilite
     * @return Dossier
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return string 
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Dossier
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Dossier
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Dossier
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return Dossier
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return Dossier
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification()
    {
        return $this->histoModification;
    }

    /**
     * Set nomPatronymique
     *
     * @param string $nomPatronymique
     * @return Dossier
     */
    public function setNomPatronymique($nomPatronymique)
    {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }

    /**
     * Get nomPatronymique
     *
     * @return string 
     */
    public function getNomPatronymique()
    {
        return $this->nomPatronymique;
    }

    /**
     * Set nomUsuel
     *
     * @param string $nomUsuel
     * @return Dossier
     */
    public function setNomUsuel($nomUsuel)
    {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }

    /**
     * Get nomUsuel
     *
     * @return string 
     */
    public function getNomUsuel()
    {
        return $this->nomUsuel;
    }

    /**
     * Set numeroInsee
     *
     * @param string $numeroInsee
     * @return Dossier
     */
    public function setNumeroInsee($numeroInsee)
    {
        $this->numeroInsee = $numeroInsee;

        return $this;
    }

    /**
     * Get numeroInsee
     *
     * @return string 
     */
    public function getNumeroInsee()
    {
        return $this->numeroInsee;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Dossier
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set rib
     *
     * @param string $rib
     * @return Dossier
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string 
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Dossier
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set typeEmployeur
     *
     * @param string $typeEmployeur
     * @return Dossier
     */
    public function setTypeEmployeur($typeEmployeur)
    {
        $this->typeEmployeur = $typeEmployeur;

        return $this;
    }

    /**
     * Get typeEmployeur
     *
     * @return string 
     */
    public function getTypeEmployeur()
    {
        return $this->typeEmployeur;
    }

    /**
     * Set premierRecrutement
     *
     * @param boolean $premierRecrutement
     * @return Dossier
     */
    public function setPremierRecrutement($premierRecrutement)
    {
        $this->premierRecrutement = $premierRecrutement;

        return $this;
    }

    /**
     * Get premierRecrutement
     *
     * @return boolean 
     */
    public function getPremierRecrutement()
    {
        return $this->premierRecrutement;
    }

    /**
     * Set perteEmploi
     *
     * @param boolean $perteEmploi
     * @return Dossier
     */
    public function setPerteEmploi($perteEmploi)
    {
        $this->perteEmploi = $perteEmploi;

        return $this;
    }

    /**
     * Get perteEmploi
     *
     * @return boolean 
     */
    public function getPerteEmploi()
    {
        return $this->perteEmploi;
    }

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
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Dossier
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return Dossier
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return Dossier
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\IntervenantExterieur $intervenant
     * @return \Application\Entity\Db\Dossier
     */
    public function fromIntervenant(IntervenantExterieur $intervenant)
    {
        $this
                ->setNomUsuel($intervenant->getNomUsuel())
                ->setNomPatronymique($intervenant->getNomPatronymique())
                ->setPrenom($intervenant->getPrenom())
                ->setCivilite($intervenant->getCiviliteToString())
                ->setNumeroInsee($intervenant->getNumeroInsee())
                ->setAdresse((string) $intervenant->getAdressePrincipale(true))
                ->setEmail($intervenant->getEmail())
                ->setTelephone($intervenant->getTelPro() ?: $intervenant->getTelMobile());
//                ->setTypeEmployeur(null)
//                ->setStatut(null)
//                ->setRib(null);
        
        return $this;
    }
}
