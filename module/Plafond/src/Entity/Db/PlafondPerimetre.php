<?php

namespace Plafond\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * PlafondPerimetre
 */
class PlafondPerimetre
{
    const STRUCTURE      = 'structure';
    const INTERVENANT    = 'intervenant';
    const ELEMENT        = 'element';
    const VOLUME_HORAIRE = 'volume_horaire';
    const REFERENTIEL    = 'referentiel';
    const MISSION        = 'mission';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var int
     */
    protected $ordre;

    /**
     * @var Collection
     */
    protected $plafond;



    public function __construct()
    {
        $this->plafond = new ArrayCollection();
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return PlafondPerimetre
     */
    public function setCode($code): PlafondPerimetre
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return PlafondPerimetre
     */
    public function setLibelle($libelle): PlafondPerimetre
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return int
     */
    public function getOrdre(): int
    {
        return $this->ordre;
    }



    /**
     * @param int $ordre
     *
     * @return PlafondPerimetre
     */
    public function setOrdre(int $ordre): PlafondPerimetre
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * add Plafond
     *
     * @param Plafond $plafond
     *
     * @return $this
     */
    public function addPlafond(Plafond $plafond): self
    {
        $this->plafond[] = $plafond;

        return $this;
    }



    /**
     * Remove Plafond
     *
     * @param Plafond $plafond
     */
    public function removePlafond(Plafond $plafond)
    {
        $this->plafond->removeElement($plafond);
    }



    /**
     * Get Plafond
     *
     * @return Collection|Plafond[]
     */
    public function getPlafond(): Collection
    {
        return $this->plafond;
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
