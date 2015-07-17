<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementDiscipline
 */
class ElementDiscipline implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $sourceCode;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Source
     */
    protected $source;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var \Application\Entity\Db\Discipline
     */
    protected $discipline;



    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return ElementDiscipline
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
     * @return ElementDiscipline
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
     * @return ElementDiscipline
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
     * Set discipline
     *
     * @param \Application\Entity\Db\Discipline $discipline
     *
     * @return ElementDiscipline
     */
    public function setDiscipline(\Application\Entity\Db\Discipline $discipline = null)
    {
        $this->discipline = $discipline;

        return $this;
    }



    /**
     * Get discipline
     *
     * @return \Application\Entity\Db\Discipline
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }
}
