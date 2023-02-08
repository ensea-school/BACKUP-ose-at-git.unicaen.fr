<?php

namespace Paiement\Entity\Db;

use Application\Service\Traits\ContextServiceAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Paiement\Service\TauxServiceAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Util;

class TauxRemu implements HistoriqueAwareInterface
{

    use HistoriqueAwareTrait;
    use TauxServiceAwareTrait;
    use ContextServiceAwareTrait;

    protected ?int       $id       = null;

    protected ?string    $code     = null;

    protected ?string    $libelle  = null;

    protected ?TauxRemu  $tauxRemu = null;

    protected Collection $tauxRemuValeurs;

    protected Collection $statuts;




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



    public function getTauxRemuValeursIndex(): ?array
    {
        $tauxRemuindex = $this->getTauxRemu();
        if (!$tauxRemuindex) {
            return [];
        }
        $indexResult  = [];
        $valeur       = [];
        $annee        = $this->getServiceContext()->getAnnee()->getId();
        $valeursIndex = $tauxRemuindex->getValeurAnnee($annee);
        $valeurs      = $this->getValeurAnnee($annee);
        $sizeIndex    = sizeof($valeursIndex);
        $sizeTaux     = sizeof($valeurs);
        $i            = 0;
        $j            = 0;


        while ($i < $sizeIndex || $j < $sizeTaux) {
            if ($valeursIndex[$i]->getDateEffet() == $valeurs[$j]->getDateEffet()) {
                $valeur['valeur'] = $valeursIndex[$i]->getValeur() * $valeurs[$j]->getValeur();
                $valeur['date']   = $valeursIndex[$i]->getDateEffet();
            } else {
                if ($valeursIndex[$i]->getDateEffet() > $valeurs[$j]->getDateEffet()) {
                    $valeur['date'] = $valeursIndex[$i]->getDateEffet();
                } else {
                    $valeur['date'] = $valeurs[$j]->getDateEffet();
                }
                $valeur['valeur'] = $valeursIndex[$i]->getValeur() * $valeurs[$j]->getValeur();
            }
            if (!array_key_exists($valeur['date']->format(Util::DATE_FORMAT), $indexResult)) {
                $indexResult[$valeur['date']->format(Util::DATE_FORMAT)] = $valeur;
            }
            //rechercher le plus proche
            if ($i + 1 < $sizeIndex && $j + 1 < $sizeTaux) {
                if ($valeursIndex[$i]->getDateEffet() == $valeurs[$j]->getDateEffet()) {
                    if ($valeursIndex[$i + 1]->getDateEffet() > $valeurs[$j + 1]->getDateEffet()) {
                        $i++;
                    } else {
                        $j++;
                    }
                } else {
                    if ($valeursIndex[$i]->getDateEffet() > $valeurs[$j]->getDateEffet()) {
                        $i++;
                    } else {
                        $j++;
                    }
                }
            } else {
                if ($i + 1 < $sizeIndex) {
                    $i++;
                } else {
                    if ($j + 1 < $sizeTaux) {
                        $j++;
                    } else {
                        break;
                    }
                }
            }
            $valeur = [];
        }


        return $indexResult;
    }



    public
    function getTauxRemu(): ?TauxRemu
    {
        return $this->tauxRemu;
    }



    public
    function setTauxRemu(?TauxRemu $tauxRemu): TauxRemu
    {
        $this->tauxRemu = $tauxRemu;

        return $this;
    }



    /**
     * @return Collection|TauxRemuValeur[]
     */
    public
    function getTauxRemuValeurs(): Collection
    {
        return $this->tauxRemuValeurs;
    }



    public
    function addTauxRemuValeur(TauxRemuValeur $tauxRemuValeur): self
    {
        $this->tauxRemuValeurs[] = $tauxRemuValeur;

        return $this;
    }



    public
    function removeTauxRemuValeur(TauxRemuValeur $tauxRemuValeur): self
    {
        $this->tauxRemuValeurs->removeElement($tauxRemuValeur);

        return $this;
    }



    public
    function getTauxRemuValeur(?\DateTime $date = null): ?TauxRemuValeur
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



    public
    function getValeur(?\DateTime $date = null): ?float
    {
        $tauxRemuValeur = $this->getTauxRemuValeur($date);
        if ($tauxRemuValeur) {
            return $tauxRemuValeur->getValeur();
        } else {
            return null;
        }
    }



    public
    function getValeurs(): Collection
    {
        return $this->tauxRemuValeurs;
    }



    public
    function __construct()
    {
        $this->tauxRemuValeurs = new ArrayCollection();
    }



    public
    function __toString(): string
    {
        return $this->getLibelle();
    }



    public
    function getDerniereValeur()
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



    public
    function getDerniereValeurDate()
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



    public
    function setValeurs($tauxRemuValeurs)
    {
        $this->tauxRemuValeurs = new ArrayCollection();
        foreach ($tauxRemuValeurs as $tauxRemuValeur) {
            $this->tauxRemuValeurs[] = $tauxRemuValeur;
        }
    }



    public
    function getValeurAnnee(int $annee): array
    {
        $valeurs        = [];
        $dateDebutAnnee = "01-09-" . $annee;
        $dateFinAnnee   = "01-09-" . $annee + 1;
        $temp           = null;
        $testValeur     = $this->getValeurs();
        foreach ($testValeur as $valeur) {
            $date = $valeur->getDateEffet();
            if (($temp == null || $temp > new DateTime($dateDebutAnnee)) && $date < new DateTime($dateDebutAnnee)) {
                $valeurs[] = $valeur;
                break;
            }
            if ($date >= new DateTime($dateDebutAnnee) && new $date < new DateTime($dateFinAnnee)) {
                $valeurs[] = $valeur;
                $temp      = $date;
            }
        }

        return $valeurs;
    }



    public
    function setValeur(DateTime $date, float $valeur)
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
}
