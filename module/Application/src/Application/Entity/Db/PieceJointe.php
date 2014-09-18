<?php

namespace Application\Entity\Db;
 
use Zend\Permissions\Acl\Resource\ResourceInterface;
 
/**
 * PieceJointe
 */
class PieceJointe implements HistoriqueAwareInterface, ValiditeAwareInterface, ResourceInterface
{
    const RESOURCE_ID = 'PieceJointe';
    
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
    private $url;

    /**
     * @var blob
     */
    private $fichier;

    /**
     * @var string
     */
    private $nomFichier;

    /**
     * @var float
     */
    private $tailleFichier;

    /**
     * @var string
     */
    private $typeFichier;

    /**
     * @var \DateTime
     */
    private $validiteDebut;

    /**
     * @var \DateTime
     */
    private $validiteFin;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypePieceJointe
     */
    private $type;

    /**
     * @var \Application\Entity\Db\Dossier
     */
    private $dossier;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

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
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return PieceJointe
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
     * @return PieceJointe
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
     * @return PieceJointe
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
     * Set url
     *
     * @param string $url
     * @return PieceJointe
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fichier
     *
     * @param string $fichier
     * @return PieceJointe
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * Get fichier
     *
     * @return string 
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set nomFichier
     *
     * @param string $nomFichier
     * @return PieceJointe
     */
    public function setNomFichier($nomFichier)
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    /**
     * Get nomFichier
     *
     * @return string 
     */
    public function getNomFichier()
    {
        return $this->nomFichier;
    }

    /**
     * Set tailleFichier
     *
     * @param float $tailleFichier
     * @return PieceJointe
     */
    public function setTailleFichier($tailleFichier)
    {
        $this->tailleFichier = $tailleFichier;

        return $this;
    }

    /**
     * Get tailleFichier
     *
     * @return float 
     */
    public function getTailleFichier()
    {
        return $this->tailleFichier;
    }

    /**
     * Get tailleFichier
     *
     * @return string 
     */
    public function getTailleFichierToString()
    {
        $f = new \UnicaenApp\Filter\BytesFormatter();
        
        return $f->filter($this->getTailleFichier());
    }

    /**
     * Set typeFichier
     *
     * @param string $typeFichier
     * @return PieceJointe
     */
    public function setTypeFichier($typeFichier)
    {
        $this->typeFichier = $typeFichier;

        return $this;
    }

    /**
     * Get typeFichier
     *
     * @return string 
     */
    public function getTypeFichier()
    {
        return $this->typeFichier;
    }

    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return PieceJointe
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return PieceJointe
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
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
     * Set type
     *
     * @param \Application\Entity\Db\TypePieceJointe $type
     * @return PieceJointe
     */
    public function setType(\Application\Entity\Db\TypePieceJointe $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypePieceJointe 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dossier
     *
     * @param \Application\Entity\Db\Dossier $dossier
     * @return PieceJointe
     */
    public function setDossier(\Application\Entity\Db\Dossier $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \Application\Entity\Db\Dossier 
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Set validation
     *
     * @param \Application\Entity\Db\Validation $validation
     * @return PieceJointe
     */
    public function setValidation(\Application\Entity\Db\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation
     *
     * @return \Application\Entity\Db\Validation 
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return PieceJointe
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
     * @return PieceJointe
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
     * @return PieceJointe
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
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return self::RESOURCE_ID;
    }
}
