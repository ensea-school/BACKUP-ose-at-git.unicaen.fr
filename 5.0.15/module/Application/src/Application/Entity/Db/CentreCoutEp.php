<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

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
     * @var \Application\Entity\Db\ElementPedagogique
     */
    private $elementPedagogique;

    /**
     * @var \Application\Entity\Db\TypeHeures
     */
    private $typeHeures;

    /**
     * @var \Application\Entity\Db\CentreCout
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
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return CentreCoutEp
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }



    /**
     * Get elementPedagogique
     *
     * @return \Application\Entity\Db\ElementPedagogique
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * Set typeHeures
     *
     * @param \Application\Entity\Db\TypeHeures $typeHeures
     *
     * @return TypeHeuresEp
     */
    public function setTypeHeures(\Application\Entity\Db\TypeHeures $typeHeures = null)
    {
        $this->typeHeures = $typeHeures;

        return $this;
    }



    /**
     * Get typeHeures
     *
     * @return \Application\Entity\Db\TypeHeures
     */
    public function getTypeHeures()
    {
        return $this->typeHeures;
    }



    /**
     * Set centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     *
     * @return CentreCoutEp
     */
    public function setCentreCout(\Application\Entity\Db\CentreCout $centreCout = null)
    {
        $this->centreCout = $centreCout;

        return $this;
    }



    /**
     * Get centreCout
     *
     * @return \Application\Entity\Db\CentreCout
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'CentreCoutEp';
    }

}
