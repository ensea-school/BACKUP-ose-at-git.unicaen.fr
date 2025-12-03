<?php

namespace Plafond\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Plafond\Interfaces\PlafondConfigInterface;

class Plafond
{
    use PlafondPerimetreAwareTrait;

    protected ?int $id = null;

    protected int $numero = 0;

    protected string $libelle = 'Nouveau plafond';

    protected ?string $message = null;

    protected string $requete = '';

    protected bool $ok = true;

    protected Collection $plafondStructure;

    protected Collection $plafondReferentiel;

    protected Collection $plafondMission;

    protected Collection $plafondStatut;



    public function __construct()
    {
        $this->plafondStructure   = new ArrayCollection();
        $this->plafondReferentiel = new ArrayCollection();
        $this->plafondMission     = new ArrayCollection();
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



    public function getMessage(): ?string
    {
        return $this->message;
    }



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



    public function isOk(): bool
    {
        return $this->ok;
    }



    public function setOk(bool $ok): Plafond
    {
        $this->ok = $ok;
        return $this;
    }



    /**
     * Get PlafondStructure
     *
     * @return Collection|PlafondStructure[]
     */
    public function getPlafondStructure(): Collection|array
    {
        return $this->plafondStructure;
    }



    /**
     * Get PlafondReferentiel
     *
     * @return Collection|PlafondReferentiel[]
     */
    public function getPlafondReferentiel(): Collection|array
    {
        return $this->plafondReferentiel;
    }



    /**
     * Get PlafondMission
     *
     * @return Collection|PlafondMission[]
     */
    public function getPlafondMission(): Collection|array
    {
        return $this->plafondMission;
    }



    /**
     * Get PlafondStatut
     *
     * @return Collection|PlafondStatut[]
     */
    public function getPlafondStatut(): Collection|array
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
        } elseif ($plafondConfig instanceof PlafondMission) {
            $this->plafondMission->add($plafondConfig);
        }

        return $this;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }

}
