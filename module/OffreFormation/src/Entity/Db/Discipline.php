<?php

namespace OffreFormation\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Discipline
 */
class Discipline implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

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



    public function __toString()
    {
        return $this->getSourceCode() . ' ' . $this->getLibelleLong();
    }

}
