<?php

namespace Application\Entity\Db;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectManagerAware;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * StructureService
 */
class Structure implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, ObjectManagerAware
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use EntityManagerAwareTrait;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var integer
     */
    protected $niveau;

    /**
     * @var string
     */
    protected $contactPj;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\TypeStructure
     */
    protected $type;

    /**
     * @var \Application\Entity\Db\Etablissement
     */
    protected $etablissement;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $parente;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $elementPedagogique;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $centreCout;

    /**
     * miseEnPaiementIntervenantStructure
     *
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;

    /**
     * @var boolean
     */
    protected $affAdresseContrat;

    /**
     * @var boolean
     */
    protected $enseignement;



    function __construct()
    {
        $this->elementPedagogique                 = new ArrayCollection;
        $this->centreCout                         = new ArrayCollection;
        $this->miseEnPaiementIntervenantStructure = new ArrayCollection;
    }



    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return Structure
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return Structure
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    /**
     * Get libelleCourt
     *
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }



    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     *
     * @return Structure
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    /**
     * Get libelleLong
     *
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }



    /**
     * Set niveau
     *
     * @param integer $niveau
     *
     * @return Structure
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }



    /**
     * Get niveau
     *
     * @return integer
     */
    public function getNiveau()
    {
        return $this->niveau;
    }



    /**
     * Set contactPj
     *
     * @param string $contactPj
     *
     * @return Structure
     */
    public function setContactPj($contactPj)
    {
        $this->contactPj = $contactPj;

        return $this;
    }



    /**
     * Get contactPj
     *
     * @return string
     */
    public function getContactPj()
    {
        return $this->contactPj;
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
     * @param \Application\Entity\Db\TypeStructure $type
     *
     * @return Structure
     */
    public function setType(\Application\Entity\Db\TypeStructure $type = null)
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeStructure
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * Set etablissement
     *
     * @param \Application\Entity\Db\Etablissement $etablissement
     *
     * @return Structure
     */
    public function setEtablissement(\Application\Entity\Db\Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }



    /**
     * Get etablissement
     *
     * @return \Application\Entity\Db\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }



    /**
     * Set parente
     *
     * @param \Application\Entity\Db\Structure $parente
     *
     * @return Structure
     */
    public function setParente(\Application\Entity\Db\Structure $parente = null)
    {
        $this->parente = $parente;

        return $this;
    }



    /**
     * Get parente
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getParente()
    {
        return $this->parente;
    }



    /**
     * Add elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return Intervenant
     */
    public function addElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique[] = $elementPedagogique;

        return $this;
    }



    /**
     * Remove elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     */
    public function removeElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique->removeElement($elementPedagogique);
    }



    /**
     * Get elementPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * Add centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     *
     * @return Intervenant
     */
    public function addCentreCout(\Application\Entity\Db\CentreCout $centreCout)
    {
        $this->centreCout[] = $centreCout;

        return $this;
    }



    /**
     * Remove centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     */
    public function removeCentreCout(\Application\Entity\Db\CentreCout $centreCout)
    {
        $this->service->removeElement($centreCout);
    }



    /**
     * Get centreCout
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }



    /**
     * Get miseEnPaiementIntervenantStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }



    /**
     * @return boolean
     */
    public function getAffAdresseContrat()
    {
        return $this->affAdresseContrat;
    }



    /**
     * @param boolean $affAdresseContrat
     *
     * @return Structure
     */
    public function setAffAdresseContrat($affAdresseContrat)
    {
        $this->affAdresseContrat = $affAdresseContrat;

        return $this;
    }



    /**
     * @return bool
     */
    public function isEnseignement()
    {
        return $this->enseignement;
    }



    /**
     * @param bool $enseignement
     *
     * @return Structure
     */
    public function setEnseignement($enseignement)
    {
        $this->enseignement = $enseignement;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleCourt();
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'Structure';
    }



    /**
     * @return AdresseStructure|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAdressePrincipale()
    {
        $dql = "
        SELECT
          a
        FROM
          Application\Entity\Db\AdresseStructure a
        WHERE
          a.structure = :structure
          AND a.principale = true
          AND a.histoDestruction IS NULL
        ";

        return $this->getEntityManager()->createQuery($dql)->setParameter('structure', $this)->getOneOrNullResult();
    }



    /**
     * Injects responsible ObjectManager and the ClassMetadata into this persistent object.
     *
     * @param ObjectManager $objectManager
     * @param ClassMetadata $classMetadata
     *
     * @return void
     */
    public function injectObjectManager(ObjectManager $objectManager, ClassMetadata $classMetadata)
    {
        $this->setEntityManager($objectManager);
    }

    function __sleep()
    {
        return [];
    }

}
