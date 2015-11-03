<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Discipline
 */
class Discipline implements HistoriqueAwareInterface
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
     * @var string
     */
    protected $codesCorresp1;

    /**
     * @var string
     */
    protected $codesCorresp2;

    /**
     * @var string
     */
    protected $codesCorresp3;

    /**
     * @var string
     */
    protected $codesCorresp4;

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
     * @return Discipline
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
     * @return Discipline
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
     * @return Discipline
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
     * @return string
     */
    public function getCodesCorresp1()
    {
        return $this->codesCorresp1;
    }



    /**
     * @param string $codesCorresp1
     *
     * @return Discipline
     */
    public function setCodesCorresp1($codesCorresp1)
    {
        $this->codesCorresp1 = $codesCorresp1;

        return $this;
    }



    /**
     * @return string
     */
    public function getCodesCorresp2()
    {
        return $this->codesCorresp2;
    }



    /**
     * @param string $codesCorresp2
     *
     * @return Discipline
     */
    public function setCodesCorresp2($codesCorresp2)
    {
        $this->codesCorresp2 = $codesCorresp2;

        return $this;
    }



    /**
     * @return string
     */
    public function getCodesCorresp3()
    {
        return $this->codesCorresp3;
    }



    /**
     * @param string $codesCorresp3
     *
     * @return Discipline
     */
    public function setCodesCorresp3($codesCorresp3)
    {
        $this->codesCorresp3 = $codesCorresp3;

        return $this;
    }



    /**
     * @return string
     */
    public function getCodesCorresp4()
    {
        return $this->codesCorresp4;
    }



    /**
     * @param string $codesCorresp4
     *
     * @return Discipline
     */
    public function setCodesCorresp4($codesCorresp4)
    {
        $this->codesCorresp4 = $codesCorresp4;

        return $this;
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
     * @return Discipline
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



    public function __toString()
    {
        return $this->getSourceCode().' '.$this->getLibelleLong();
    }

}
