<?php

namespace OffreFormation\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * CheminPedagogique
 */
class CheminPedagogique implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    protected $ordre;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \OffreFormation\Entity\Db\ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var \OffreFormation\Entity\Db\Etape
     */
    protected $etape;

    

    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return CheminPedagogique
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
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
     * Set elementPedagogique
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return CheminPedagogique
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
     * Set etape
     *
     * @param \OffreFormation\Entity\Db\Etape $etape
     *
     * @return CheminPedagogique
     */
    public function setEtape(?\OffreFormation\Entity\Db\Etape $etape = null)
    {
        $this->etape = $etape;

        return $this;
    }



    /**
     * Get etape
     *
     * @return \OffreFormation\Entity\Db\Etape
     */
    public function getEtape()
    {
        return $this->etape;
    }
}
