<?php

namespace OffreFormation\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Effectifs
 */
class Effectifs implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    private $fa = '0';

    /**
     * @var integer
     */
    private $fc = '0';

    /**
     * @var integer
     */
    private $fi = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \OffreFormation\Entity\Db\ElementPedagogique
     */
    private $elementPedagogique;



    /**
     * Set fa
     *
     * @param integer $fa
     *
     * @return Effectifs
     */
    public function setFa($fa)
    {
        $this->fa = $fa;

        return $this;
    }



    /**
     * Get fa
     *
     * @return integer
     */
    public function getFa()
    {
        return $this->fa;
    }



    /**
     * Set fc
     *
     * @param integer $fc
     *
     * @return Effectifs
     */
    public function setFc($fc)
    {
        $this->fc = $fc;

        return $this;
    }



    /**
     * Get fc
     *
     * @return integer
     */
    public function getFc()
    {
        return $this->fc;
    }



    /**
     * Set fi
     *
     * @param integer $fi
     *
     * @return Effectifs
     */
    public function setFi($fi)
    {
        $this->fi = $fi;

        return $this;
    }



    /**
     * Get fi
     *
     * @return integer
     */
    public function getFi()
    {
        return $this->fi;
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
     * @return Effectifs
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

}

