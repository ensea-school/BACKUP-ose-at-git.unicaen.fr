<?php

namespace OffreFormation\Entity\Db;

use Application\Entity\Db\TypeHeuresEp;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * CentreCoutEp
 */
class CentreCoutEp implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \OffreFormation\Entity\Db\ElementPedagogique
     */
    private $elementPedagogique;

    /**
     * @var \OffreFormation\Entity\Db\TypeHeures
     */
    private $typeHeures;

    /**
     * @var \Paiement\Entity\Db\CentreCout
     */
    private $centreCout;



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
     * Set elementPedagogique
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return CentreCoutEp
     */
    public function setElementPedagogique(?\OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }



    /**
     * Get elementPedagogique
     *
     * @return \OffreFormation\Entity\Db\ElementPedagogique
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * Set typeHeures
     *
     * @param \OffreFormation\Entity\Db\TypeHeures $typeHeures
     *
     * @return TypeHeuresEp
     */
    public function setTypeHeures(?\OffreFormation\Entity\Db\TypeHeures $typeHeures = null)
    {
        $this->typeHeures = $typeHeures;

        return $this;
    }



    /**
     * Get typeHeures
     *
     * @return \OffreFormation\Entity\Db\TypeHeures
     */
    public function getTypeHeures()
    {
        return $this->typeHeures;
    }



    /**
     * Set centreCout
     *
     * @param \Paiement\Entity\Db\CentreCout $centreCout
     *
     * @return CentreCoutEp
     */
    public function setCentreCout(?\Paiement\Entity\Db\CentreCout $centreCout = null)
    {
        $this->centreCout = $centreCout;

        return $this;
    }



    /**
     * Get centreCout
     *
     * @return \Paiement\Entity\Db\CentreCout
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }



    public function getResourceId(): string
    {
        return self::class;
    }

}
