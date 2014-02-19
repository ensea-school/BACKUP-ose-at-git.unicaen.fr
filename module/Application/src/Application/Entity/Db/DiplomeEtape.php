<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiplomeEtape
 */
class DiplomeEtape
{
    /**
     * @var string
     */
    private $sourceCode;

    /**
     * @var integer
     */
    private $index;

    /**
     * @var \Application\Entity\Db\Diplome
     */
    private $diplome;

    /**
     * @var \Application\Entity\Db\Etape
     */
    private $etape;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;


    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return DiplomeEtape
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
     * Set index
     *
     * @param integer $index
     * @return DiplomeEtape
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index
     *
     * @return integer 
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set diplome
     *
     * @param \Application\Entity\Db\Diplome $diplome
     * @return DiplomeEtape
     */
    public function setDiplome(\Application\Entity\Db\Diplome $diplome)
    {
        $this->diplome = $diplome;

        return $this;
    }

    /**
     * Get diplome
     *
     * @return \Application\Entity\Db\Diplome 
     */
    public function getDiplome()
    {
        return $this->diplome;
    }

    /**
     * Set etape
     *
     * @param \Application\Entity\Db\Etape $etape
     * @return DiplomeEtape
     */
    public function setEtape(\Application\Entity\Db\Etape $etape)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Get etape
     *
     * @return \Application\Entity\Db\Etape 
     */
    public function getEtape()
    {
        return $this->etape;
    }

    /**
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return DiplomeEtape
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
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return DiplomeEtape
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
    }
}
