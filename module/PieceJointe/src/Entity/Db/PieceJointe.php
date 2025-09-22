<?php

namespace PieceJointe\Entity\Db;

use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * PieceJointe
 */
class PieceJointe implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    const RESOURCE_ID = 'PieceJointe';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \PieceJointe\Entity\Db\TypePieceJointe
     */
    private $type;

    /**
     * @var \Intervenant\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $fichier;

    /**
     * @var \Workflow\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var \Application\Entity\Db\Annee
     */
    protected $dateArchive;



    /**
     *
     */
    public function __construct()
    {
        $this->fichier = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        $string = (string)$this->getType();

        if ($this->getValidation()) {
            $string .= $this->getValidation();
        }

        return $string;
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
     * @param \PieceJointe\Entity\Db\TypePieceJointe $type
     *
     * @return PieceJointe
     */
    public function setType(?\PieceJointe\Entity\Db\TypePieceJointe $type = null)
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Get type
     *
     * @return \PieceJointe\Entity\Db\TypePieceJointe
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return PieceJointe
     */
    public function setIntervenant($intervenant)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Add fichier
     *
     * @param \Application\Entity\Db\Fichier $fichier
     *
     * @return TypeFichier
     */
    public function addFichier(\Application\Entity\Db\Fichier $fichier)
    {
        $this->fichier[] = $fichier;

        return $this;
    }



    /**
     * Remove fichier
     *
     * @param \Application\Entity\Db\Fichier $fichier
     */
    public function removeFichier(\Application\Entity\Db\Fichier $fichier)
    {
        $this->fichier->removeElement($fichier);
    }



    /**
     * Get fichier
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichier()
    {
        return $this->fichier;
    }



    /**
     * Set validation
     *
     * @param \Workflow\Entity\Db\Validation $validation
     *
     * @return PieceJointe
     */
    public function setValidation(?\Workflow\Entity\Db\Validation $validation = null)
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



    /**
     * Set dateArchive
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return PieceJointe
     */

    public function setDateArchive(?\Application\Entity\Db\Annee $annee = null)
    {
        $this->dateArchive = $annee;

        return $this;
    }



    /**
     * Get dateArchive
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getDateArchive()
    {
        return $this->dateArchive;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'PieceJointe';
    }
}
