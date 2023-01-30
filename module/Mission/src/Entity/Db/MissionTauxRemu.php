<?php

namespace Mission\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class MissionTauxRemu implements HistoriqueAwareInterface
{

    use HistoriqueAwareTrait;

    protected ?int             $id              = null;

    protected ?string          $code            = null;

    protected ?string          $libelle         = null;

    protected ?MissionTauxRemu $missionTauxRemu = null;

    protected Collection       $tauxRemuValeurs;



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



    public function getMissionTauxRemu(): ?MissionTauxRemu
    {
        return $this->missionTauxRemu;
    }



    public function setMissionTauxRemu(?MissionTauxRemu $missionTauxRemu): MissionTauxRemu
    {
        $this->missionTauxRemu = $missionTauxRemu;

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



    public function getDerniereValeur()
    {
        $valeurRetour     = null;
        $valeurRetourDate = null;
        $valeurs          = $this->tauxRemuValeurs->getValues();
        foreach ($valeurs as $valeur) {
            if ($valeurRetourDate == null || $valeur->getDateEffet() > $valeurRetourDate) {
                $valeurRetour     = $valeur->getValeur();
                $valeurRetourDate = $valeur->getDateEffet();
            }
        }

        return $valeurRetour;
    }



    public function getDerniereValeurDate()
    {
        $valeurRetourDate = null;
        $valeurs          = $this->tauxRemuValeurs->getValues();
        foreach ($valeurs as $valeur) {
            if ($valeurRetourDate == null || $valeur->getDateEffet() > $valeurRetourDate) {
                $valeurRetourDate = $valeur->getDateEffet();
            }
        }

        return $valeurRetourDate;
    }



    public function setValeur(DateTime $date, float $valeur)
    {
        $tauxRemuValeurProche = $this->getTauxRemuValeur($date);
        if ($tauxRemuValeurProche != null && $tauxRemuValeurProche->getDateEffet() == $date) {
            $tauxRemuValeurProche->setValeur($valeur);
        } else {
            //new tauxremu
            $newTauxRemu = new MissionTauxRemuValeur();
            $newTauxRemu->setValeur($valeur);
            $newTauxRemu->setDateEffet($date);
            $this->addTauxRemuValeur($newTauxRemu);
        }
    }
}
