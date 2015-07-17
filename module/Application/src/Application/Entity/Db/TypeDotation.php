<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeDotation
 */
class TypeDotation implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    private $libelle;

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
     * @var \Application\Entity\Db\TypeRessource
     */
    private $typeRessource;



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeDotation
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
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return TypeDotation
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
     * @return TypeDotation
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
     * Set typeRessource
     *
     * @param \Application\Entity\Db\TypeRessource $typeRessource
     *
     * @return TypeDotation
     */
    public function setTypeRessource(\Application\Entity\Db\TypeRessource $typeRessource = null)
    {
        $this->typeRessource = $typeRessource;

        return $this;
    }



    /**
     * Get typeRessource
     *
     * @return \Application\Entity\Db\TypeRessource
     */
    public function getTypeRessource()
    {
        return $this->typeRessource;
    }
}
