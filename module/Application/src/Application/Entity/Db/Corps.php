<?php

namespace Application\Entity\Db;

/**
 * Corps
 */
class Corps implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

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
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return Corps
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
     * @return Corps
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
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return Corps
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
     * @return Corps
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




    /**************************************************************************************************
     *                                        Début ajout
     **************************************************************************************************/

    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleLong();
    }
}
