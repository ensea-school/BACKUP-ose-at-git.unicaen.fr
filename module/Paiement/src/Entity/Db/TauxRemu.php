<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\Annee;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class TauxRemu implements HistoriqueAwareInterface, ResourceInterface
{

    const CODE_DEFAUT = 'TLD';

    use HistoriqueAwareTrait;

    protected ?int       $id       = null;

    protected ?string    $code     = null;

    protected ?string    $libelle  = null;

    protected ?TauxRemu  $tauxRemu = null;

    protected Collection $tauxRemuValeurs;

    protected Collection $statuts;

    protected Collection $sousTauxRemu;




    public function __construct()
    {
        $this->tauxRemuValeurs = new ArrayCollection();
        $this->sousTauxRemu    = new ArrayCollection();
    }


    /**
     * @return Collection
     */
    public function getSousTauxRemu(): Collection
    {
        return $this->sousTauxRemu;
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): TauxRemu
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): TauxRemu
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return bool
     */
    public function isDefaut(): bool
    {
        return self::CODE_DEFAUT == $this->getCode();
    }



    public function hasChildren(): bool
    {
        return !$this->sousTauxRemu->isEmpty();
    }




    public function getTauxRemu(): ?TauxRemu
    {
        return $this->tauxRemu;
    }



    public function setTauxRemu(?TauxRemu $tauxRemu): TauxRemu
    {
        $this->tauxRemu = $tauxRemu;

        return $this;
    }



    /**
     * @return Collection|TauxRemuValeur[]
     */
    public function getTauxRemuValeurs(): Collection
    {
        return $this->tauxRemuValeurs;
    }



    public function addTauxRemuValeur(TauxRemuValeur $tauxRemuValeur): self
    {
        $this->tauxRemuValeurs[] = $tauxRemuValeur;

        return $this;
    }



    public function removeTauxRemuValeur(TauxRemuValeur $tauxRemuValeur): self
    {
        $this->tauxRemuValeurs->removeElement($tauxRemuValeur);

        return $this;
    }



    public function getTauxRemuValeur(?\DateTime $date = null): ?TauxRemuValeur
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



    public function getValeurs(): Collection
    {
        return $this->tauxRemuValeurs;
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



    public function setValeurs($tauxRemuValeurs)
    {
        $this->tauxRemuValeurs = new ArrayCollection();
        foreach ($tauxRemuValeurs as $tauxRemuValeur) {
            $this->tauxRemuValeurs[] = $tauxRemuValeur;
        }
    }



    public function getValeurAnnee(Annee $annee): array //Entitée Annee
    {
        $valeurs        = [];
        $dateDebutAnnee = $annee->getDateDebut();
        $dateFinAnnee   = $annee->getDateFin();
        $temp           = null;
        $testValeur     = $this->getValeurs();
        foreach ($testValeur as $valeur) {
            $date = $valeur->getDateEffet();
            if (($temp == null || $temp > $dateDebutAnnee) && $date < $dateDebutAnnee) {
                $valeurs[] = $valeur;
                break;
            }
            if ($date >= $dateDebutAnnee && $date < $dateFinAnnee) {
                $valeurs[] = $valeur;
                $temp      = $date;
            }
        }

        return $valeurs;
    }



    public function setValeur(DateTime $date, float $valeur)
    {
        $tauxRemuValeurProche = $this->getTauxRemuValeur($date);
        if ($tauxRemuValeurProche != null && $tauxRemuValeurProche->getDateEffet() == $date) {
            $tauxRemuValeurProche->setValeur($valeur);
        } else {
            //new tauxremu
            $newTauxRemu = new TauxRemuValeur();
            $newTauxRemu->setValeur($valeur);
            $newTauxRemu->setDateEffet($date);
            $this->addTauxRemuValeur($newTauxRemu);
        }
    }



    public function getResourceId(): string
    {
        return self::class;
    }
}
