<?php

namespace Paiement\Entity\Db;
use Application\Entity\Db\Annee;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use OffreFormation\Entity\Db\TypeInterventionStructure;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeModulateurStructure
 */
class TypeModulateurStructure implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Paiement\Entity\Db\TypeModulateur
     */
    protected $typeModulateur;

    /**
     * @var \Lieu\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var Annee
     */
    private $anneeDebut;

    /**
     * @var Annee
     */
    private $anneeFin;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    public function getResourceId(): string
    {
        return 'TypeModulateurStructure';
    }



    /**
     * Set typeModulateur
     *
     * @param \Paiement\Entity\Db\TypeModulateur $typeModulateur
     *
     * @return TypeModulateurStructure
     */
    public function setTypeModulateur(?\Paiement\Entity\Db\TypeModulateur $typeModulateur = null)
    {
        $this->typeModulateur = $typeModulateur;

        return $this;
    }



    /**
     * Get typeModulateur
     *
     * @return \Paiement\Entity\Db\TypeModulateur
     */
    public function getTypeModulateur()
    {
        return $this->typeModulateur;
    }



    /**
     * Set structure
     *
     * @param \Lieu\Entity\Db\Structure $structure
     *
     * @return TypeModulateurStructure
     */
    public function setStructure(?\Lieu\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Lieu\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @return Annee
     */
    public function getAnneeDebut()
    {
        return $this->anneeDebut;
    }



    /**
     * @param Annee $anneeDebut
     *
     * @return TypeInterventionStructure
     */
    public function setAnneeDebut(?Annee $anneeDebut = null)
    {
        $this->anneeDebut = $anneeDebut;

        return $this;
    }



    /**
     * @return Annee
     */
    public function getAnneeFin()
    {
        return $this->anneeFin;
    }



    /**
     * @param Annee $anneeFin
     *
     * @return TypeInterventionStructure
     */
    public function setAnneeFin(?Annee $anneeFin = null)
    {
        $this->anneeFin = $anneeFin;

        return $this;
    }

}
