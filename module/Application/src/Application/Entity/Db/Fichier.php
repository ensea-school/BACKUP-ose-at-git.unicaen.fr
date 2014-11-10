<?php

namespace Application\Entity\Db;

use UnicaenApp\Filter\BytesFormatter;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Controller\Plugin\Upload\UploadedFileInterface;

/**
 * Fichier
 */
class Fichier implements HistoriqueAwareInterface, ResourceInterface, UploadedFileInterface
{
    use HistoriqueAwareTrait;
    
    const RESOURCE_ID = 'Fichier';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var float
     */
    private $taille;

    /**
     * @var string
     */
    private $type;

    /**
     * @var blob
     */
    private $contenu;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $pieceJointe;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

    /**
     * 
     */
    public function __construct()
    {
        $this->pieceJointe = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        $string = sprintf("%s - Fichier '%s'",
                $this->getType(), 
                $this->getNom());
        
        if ($this->getValidation()) {
            $string .= $this->getValidation();
        }
        
        return $string;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Fichier
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
     * Set contenu
     *
     * @param string $contenu
     * @return self
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Fichier
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set taille
     *
     * @param float $taille
     * @return Fichier
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille
     *
     * @return float 
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Get taille
     *
     * @return string 
     */
    public function getTailleToString()
    {
        $f = new BytesFormatter();
        
        return $f->filter($this->getTaille());
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
     * @param string $type
     * @return self
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set validation
     *
     * @param \Application\Entity\Db\Validation $validation
     * @return Fichier
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
     * Get pieceJointe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
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
