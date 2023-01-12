<?php

namespace Mission\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class MissionTauxRemu implements HistoriqueAwareInterface
{

    use HistoriqueAwareTrait;

    protected ?int       $id      = null;

    protected ?string    $code    = null;

    protected ?string    $libelle = null;

    protected Collection $tauxRemuValeurs;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): MissionTauxRemu
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): MissionTauxRemu
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return Collection|MissionTauxRemuValeur[]
     */
    public function getTauxRemuValeurs(): Collection
    {
        return $this->tauxRemuValeurs;
    }



    public function addTauxRemuValeur(MissionTauxRemuValeur $missionTauxRemuValeur): self
    {
        $this->tauxRemuValeurs[] = $missionTauxRemuValeur;

        return $this;
    }



    public function removeTauxRemuValeur(MissionTauxRemuValeur $missionTauxRemuValeur): self
    {
        $this->tauxRemuValeurs->removeElement($missionTauxRemuValeur);

        return $this;
    }



    public function getTauxRemuValeur(?\DateTime $date = null): ?MissionTauxRemuValeur
    {
        if (empty($date)) {
            $date = new \DateTime();
        }

        foreach ($this->tauxRemuValeurs as $valeur) {
            if ($valeur->getDateEffet() > $date) {
                continue;
            }

            return $valeur;
        }

        return null;
    }



    public function getValeur(?\DateTime $date = null): ?float
    {
        $tauxRemuValeur = $this->getTauxRemuValeur($date);
        if ($tauxRemuValeur) {
            return $tauxRemuValeur->getValeur();
        } else {
            return null;
        }
    }



    public function __construct()
    {
        $this->tauxRemuValeurs = new ArrayCollection();
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
