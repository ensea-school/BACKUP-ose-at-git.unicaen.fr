<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\CorpsAwareTrait;
use Application\Entity\Db\Traits\SourceAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Grade
 */
class Grade implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use CorpsAwareTrait;
    use SourceAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var integer
     */
    protected $echelle;

    /**
     * @var string
     */
    protected $sourceCode;



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
     * @return int
     */
    public function getEchelle()
    {
        return $this->echelle;
    }



    /**
     * @param int $echelle
     *
     * @return Grade
     */
    public function setEchelle($echelle)
    {
        $this->echelle = $echelle;

        return $this;
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
     * Retourne le grade précédé se son corps
     *
     * @return string
     */
    public function toStringWithCorps()
    {
        return $this->corps . ' - ' . $this;
    }



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
