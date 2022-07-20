<?php

namespace Plafond\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Plafond\Interfaces\PlafondConfigInterface;

/**
 * Plafond
 */
class Plafond
{
    use PlafondPerimetreAwareTrait;

    protected ?int       $id      = null;

    protected int        $numero  = 0;

    protected string     $libelle = 'Nouveau plafond';

    protected ?string    $message = null;

    protected string     $requete = '';

    protected Collection $plafondStructure;

    protected Collection $plafondReferentiel;

    protected Collection $plafondStatut;



    public function __construct()
    {
        $this->plafondStructure   = new ArrayCollection();
        $this->plafondReferentiel = new ArrayCollection();
        $this->plafondStatut      = new ArrayCollection();
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



    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }



    /**
     * @param string|null $message
     *
     * @return Plafond
     */
    public function setMessage(?string $message): Plafond
    {
        $this->message = $message;

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
     * Get PlafondStructure
     *
     * @return PlafondStructure[]
     */
    public function getPlafondStructure(): Collection
    {
        return $this->plafondStructure;
    }



    /**
     * Get PlafondReferentiel
     *
     * @return PlafondReferentiel[]
     */
    public function getPlafondReferentiel(): Collection
    {
        return $this->plafondReferentiel;
    }



    /**
     * Get PlafondStatut
     *
     * @return PlafondStatut[]
     */
    public function getPlafondStatut(): Collection
    {
        return $this->plafondStatut;
    }



    public function addConfig(PlafondConfigInterface $plafondConfig): self
    {
        if ($plafondConfig->getPlafond() !== $this) {
            throw new \Exception('Mauvais plafond transmis');
        }

        if ($plafondConfig instanceof PlafondStatut) {
            $this->plafondStatut->add($plafondConfig);
        } elseif ($plafondConfig instanceof PlafondStructure) {
            $this->plafondStructure->add($plafondConfig);
        } elseif ($plafondConfig instanceof PlafondReferentiel) {
            $this->plafondReferentiel->add($plafondConfig);
        }

        return $this;
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
