<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * CheminPedagogique
 */
class CheminPedagogique implements HistoriqueAwareInterface
{
    /**
     * @var string
     */
    private $sourceCode;

    /**
     * @var string
     */
    private $ue;

    /**
     * @var integer
     */
    private $index;

    /**
     * @var \DateTime
     */
    private $dateDebutAnneeUniv;

    /**
     * @var \Application\Entity\Db\Etape
     */
    private $etape;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    private $elementPedagogique;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;

    /**
     * @var \Application\Entity\Db\Periode
     */
    private $periode;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;


    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return CheminPedagogique
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
     * Set ue
     *
     * @param string $ue
     * @return CheminPedagogique
     */
    public function setUe($ue)
    {
        $this->ue = $ue;

        return $this;
    }

    /**
     * Get ue
     *
     * @return string 
     */
    public function getUe()
    {
        return $this->ue;
    }

    /**
     * Set index
     *
     * @param integer $index
     * @return CheminPedagogique
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
     * Set dateDebutAnneeUniv
     *
     * @param \DateTime $dateDebutAnneeUniv
     * @return CheminPedagogique
     */
    public function setDateDebutAnneeUniv($dateDebutAnneeUniv)
    {
        $this->dateDebutAnneeUniv = $dateDebutAnneeUniv;

        return $this;
    }

    /**
     * Get dateDebutAnneeUniv
     *
     * @return \DateTime 
     */
    public function getDateDebutAnneeUniv()
    {
        return $this->dateDebutAnneeUniv;
    }

    /**
     * Set etape
     *
     * @param \Application\Entity\Db\Etape $etape
     * @return CheminPedagogique
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
     * Set elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     * @return CheminPedagogique
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return CheminPedagogique
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
     * Set periode
     *
     * @param \Application\Entity\Db\Periode $periode
     * @return CheminPedagogique
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return \Application\Entity\Db\Periode 
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return CheminPedagogique
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
