<?php

namespace Plafond\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Plafond
 */
class Plafond
{
    use PlafondPerimetreAwareTrait;

    protected int        $id;

    protected int        $numero  = 0;

    protected string     $libelle = 'Nouveau plafond';

    protected string     $requete = '';

    protected Collection $plafondApplication;



    public function __construct()
    {
        $this->plafondApplication = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getNumero(): int
    {
        return $this->numero;
    }



    public function setNumero(int $numero): Plafond
    {
        $this->numero = $numero;

        return $this;
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }



    public function setLibelle(string $libelle): Plafond
    {
        $this->libelle = $libelle;

        return $this;
    }



    public function getRequete(): string
    {
        return $this->requete;
    }



    public function setRequete(string $requete): Plafond
    {
        $this->requete = $requete;

        return $this;
    }



    /**
     * add PlafondApplication
     *
     * @param PlafondApplication $plafondApplication
     *
     * @return $this
     */
    public function addPlafondApplication(PlafondApplication $plafondApplication): self
    {
        $this->plafondApplication[] = $plafondApplication;

        return $this;
    }



    /**
     * Remove PlafondApplication
     *
     * @param PlafondApplication $plafondApplication
     */
    public function removePlafondApplication(PlafondApplication $plafondApplication)
    {
        $this->plafondApplication->removeElement($plafondApplication);
    }



    /**
     * Get PlafondApplication
     *
     * @return Collection|PlafondApplication[]
     */
    public function getPlafondApplication(): Collection
    {
        return $this->plafondApplication;
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
