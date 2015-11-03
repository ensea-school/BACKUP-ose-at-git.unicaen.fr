<?php

namespace Application\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Effectifs
 */
class Effectifs implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

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
     * @var string
     */
    private $sourceCode;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    private $elementPedagogique;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;



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
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return Effectifs
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
     * Set elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return Effectifs
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     *
     * @return Effectifs
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

}

