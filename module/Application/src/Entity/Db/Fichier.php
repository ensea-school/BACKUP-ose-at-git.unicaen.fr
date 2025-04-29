<?php

namespace Application\Entity\Db;

use Application\Service\Traits\FichierServiceAwareTrait;
use DateTime;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use PieceJointe\Entity\Db\PieceJointe;
use UnicaenApp\Controller\Plugin\Upload\UploadedFileInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Filter\BytesFormatter;

/**
 * Fichier
 */
class Fichier implements HistoriqueAwareInterface, ResourceInterface, UploadedFileInterface
{
    use HistoriqueAwareTrait;
    use FichierServiceAwareTrait;

    /**
     *
     */
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
     * @var \Workflow\Entity\Db\Validation
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
        $string = sprintf(
            "%s - Fichier '%s'",
            $this->getTypeMime(),
            $this->getNom()
        );

        if ($this->getValidation()) {
            $string .= $this->getValidation();
        }

        return $string;
    }



    /**
     * Set url
     *
     * @param string $url
     *
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
     *
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
    public function getContenu($onlyBdd = false)
    {
        if ($onlyBdd || !$this->getId()) {
            return $this->contenu;
        } else {
            return $this->getServiceFichier()->getFichierContenu($this);
        }
    }



    /**
     * Set nom
     *
     * @param string $nom
     *
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
     *
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
     * Set typeMime
     *
     * @param string $typeMime
     *
     * @return self
     */
    public function setTypeMime($typeMime = null)
    {
        $this->type = $typeMime;

        return $this;
    }



    /**
     * Get typeMime
     *
     * @return string
     */
    public function getTypeMime()
    {
        return $this->type;
    }



    /**
     * Set description
     *
     * @param string $description
     *
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
     * Retourne la date de dépôt du fichier.
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->getHistoModification();
    }



    /**
     * Set validation
     *
     * @param \Workflow\Entity\Db\Validation $validation
     *
     * @return Fichier
     */
    public function setValidation(\Workflow\Entity\Db\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }



    /**
     * Get validation
     *
     * @return \Workflow\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }



    public function getPieceJointe(): ?PieceJointe
    {
        $pj = $this->pieceJointe;
        if ($pj->count() == 1) {
            return $this->pieceJointe->first();
        }

        return null;
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
