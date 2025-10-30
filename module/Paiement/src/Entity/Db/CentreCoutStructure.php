<?php

namespace Paiement\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * CentreCoutStructure
 */
class CentreCoutStructure implements HistoriqueAwareInterface, ImportAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Paiement\Entity\Db\CentreCout
     */
    private $centreCout;

    /**
     * @var \Lieu\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var string
     */
    private $uniteBudgetaire;

    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->structure;
    }



    public function getResourceId(): string
    {
        return self::class;
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
     * Set centreCout
     *
     * @param $centreCout
     *
     * @return CentreCoutStructure
     */
    public function setCentreCout($centreCout)
    {
        $this->centreCout = $centreCout;

        return $this;
    }

    /**
     * Get centreCout
     *
     * @return CentreCout
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }

    /**
     * Set structure
     *
     * @param $structure
     *
     * @return CentreCoutStructure
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set uniteBudgetaire
     *
     * @param $uniteBudgetaire
     *
     * @return CentreCoutStructure
     */
    public function setUniteBudgetaire($uniteBudgetaire)
    {
        $this->uniteBudgetaire = $uniteBudgetaire;

        return $this;
    }

    /**
     * Get uniteBudgetaire
     *
     * @return string
     */
    public function getUniteBudgetaire()
    {
        return $this->uniteBudgetaire;
    }


}
