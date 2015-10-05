<?php

namespace Application\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * CentreCoutEp
 */
class CentreCoutEp implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    private $sourceCode;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;

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
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return CentreCoutEp
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }



    /**
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     *
     * @return CentreCoutEp
     */
    public function setSource(\Application\Entity\Db\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }



    /**
     * Get source
     *
     * @return \Application\Entity\Db\Source
     */
    public function getSource()
    {
        return $this->source;
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
