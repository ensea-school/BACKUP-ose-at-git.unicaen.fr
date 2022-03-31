<?php

namespace Indicateur\Entity\Db;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Plafond\Entity\Db\PlafondPerimetre;

/**
 * TypeIndicateur
 */
class TypeIndicateur
{
    private int        $id;

    private int        $ordre;

    private string     $libelle;

    private Collection $indicateur;



    public function __construct()
    {
        $this->indicateur = new ArrayCollection();
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return int
     */
    public function getOrdre(): int
    {
        return $this->ordre;
    }



    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * Get formuleResultatService
     *
     * @return Collection|Indicateur[]
     */
    public function getIndicateur(): Collection
    {
        return $this->indicateur;
    }



    public function __toString()
    {
        return $this->getLibelle();
    }



    public function isPlafond(): bool
    {
        return $this->getPlafondPerimetreCode() !== null;
    }



    public function getPlafondPerimetreCode(): ?string
    {
        $corresp = [
            12 => PlafondPerimetre::INTERVENANT,
            13 => PlafondPerimetre::STRUCTURE,
            14 => PlafondPerimetre::REFERENTIEL,
            15 => PlafondPerimetre::ELEMENT,
            16 => PlafondPerimetre::VOLUME_HORAIRE,
        ];
        if (array_key_exists($this->getId(), $corresp)) {
            return $corresp[$this->getId()];
        } else {
            return null;
        }
    }
}