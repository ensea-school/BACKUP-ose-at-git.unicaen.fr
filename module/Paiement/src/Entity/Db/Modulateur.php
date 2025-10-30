<?php

namespace Paiement\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Modulateur
 */
class Modulateur implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var float
     */
    protected $ponderationServiceCompl;

    /**
     * @var float
     */
    protected $ponderationServiceDu;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Paiement\Entity\Db\TypeModulateur
     */
    protected $typeModulateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $elementModulateur;



    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return Modulateur
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Modulateur
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * Set ponderationServiceCompl
     *
     * @param float $ponderationServiceCompl
     *
     * @return Modulateur
     */
    public function setPonderationServiceCompl($ponderationServiceCompl)
    {
        $this->ponderationServiceCompl = $ponderationServiceCompl;

        return $this;
    }



    /**
     * Get ponderationServiceCompl
     *
     * @return float
     */
    public function getPonderationServiceCompl()
    {
        return $this->ponderationServiceCompl;
    }



    /**
     * Set ponderationServiceDu
     *
     * @param float $ponderationServiceDu
     *
     * @return Modulateur
     */
    public function setPonderationServiceDu($ponderationServiceDu)
    {
        $this->ponderationServiceDu = $ponderationServiceDu;

        return $this;
    }



    /**
     * Get ponderationServiceDu
     *
     * @return float
     */
    public function getPonderationServiceDu()
    {
        return $this->ponderationServiceDu;
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
     * @param \Paiement\Entity\Db\TypeModulateur $type
     *
     * @return Modulateur
     */
    public function setTypeModulateur(?\Paiement\Entity\Db\TypeModulateur $typeModulateur = null)
    {
        $this->typeModulateur = $typeModulateur;

        return $this;
    }



    /**
     * Get type
     *
     * @return \Paiement\Entity\Db\TypeModulateur
     */
    public function getTypeModulateur()
    {
        return $this->typeModulateur;
    }



    /**
     * Add elementModulateur
     *
     * @param \OffreFormation\Entity\Db\ElementModulateur $elementModulateur
     *
     * @return self
     */
    public function addElementModulateur(\OffreFormation\Entity\Db\ElementModulateur $elementModulateur)
    {
        $this->elementModulateur[] = $elementModulateur;

        return $this;
    }



    /**
     * Remove elementModulateur
     *
     * @param \OffreFormation\Entity\Db\ElementModulateur $elementModulateur
     */
    public function removeElementModulateur(\Enseignement\Entity\Db\Service $elementModulateur)
    {
        $this->elementModulateur->removeElement($elementModulateur);
    }



    /**
     * Get elementModulateur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementModulateur()
    {
        return $this->elementModulateur;
    }



    public function getResourceId(): string
    {
        return self::class;
    }

}
