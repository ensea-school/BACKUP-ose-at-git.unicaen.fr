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
    protected $adresse;

    /**
     * @var Civilite
     */
    protected $civilite;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $telephone;

    /**
     * @var \DateTime
     */
    protected $histoCreation;

    /**
     * @var \DateTime
     */
    protected $histoDestruction;

    /**
     * @var \DateTime
     */
    protected $histoModification;

    /**
     * @var string
     */
    protected $nomPatronymique;

    /**
     * @var string
     */
    protected $nomUsuel;

    /**
     * @var string
     */
    protected $numeroInsee;
    
    /**
     * @var boolean
     */
    protected $numeroInseeEstProvisoire = false;

    /**
     * @var string
     */
    protected $prenom;

    /**
     * @var string
     */
    protected $rib;

    /**
     * @var StatutIntervenant
     */
    protected $statut;

    /**
     * @var boolean
     */
    protected $premierRecrutement;

    /**
     * @var boolean
     */
    protected $perteEmploi;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $pieceJointe;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;


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
     * @param Civilite $civilite
     * @return Dossier
     */
    public function setCivilite(Civilite $civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return Civilite 
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
     * Set numeroInseeEstProvisoire
     *
     * @param boolean $numeroInseeEstProvisoire
     * @return Dossier
     */
    public function setNumeroInseeEstProvisoire($numeroInseeEstProvisoire)
    {
        $this->numeroInseeEstProvisoire = $numeroInseeEstProvisoire;

        return $this;
    }

    /**
     * Get numeroInseeEstProvisoire
     *
     * @return boolean 
     */
    public function getNumeroInseeEstProvisoire()
    {
        return $this->numeroInseeEstProvisoire;
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
     * @param StatutIntervenant $statut
     * @return Dossier
     */
    public function setStatut(StatutIntervenant $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return StatutIntervenant 
     */
    public function getStatut()
    {
        return $this->statut;
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
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
                ->setCivilite($intervenant->getCivilite())
                ->setNumeroInsee($intervenant->getNumeroInsee() . $intervenant->getNumeroInseeCle())
                ->setAdresse((string) $intervenant->getAdressePrincipale(true))
                ->setEmail($intervenant->getEmail())
                ->setTelephone($intervenant->getTelPro() ?: $intervenant->getTelMobile())
                ->setStatut($intervenant->getStatut())
                ->setRib(preg_replace('/\s+/', '', $intervenant->getBIC() . '-' . $intervenant->getIBAN()));
        
        return $this;
    }
}
