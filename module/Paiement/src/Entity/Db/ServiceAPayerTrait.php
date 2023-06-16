<?php

namespace Paiement\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Paiement\Entity\MiseEnPaiementListe;
use Application\Entity\Db\Periode;
use OffreFormation\Entity\Db\TypeHeures;

trait ServiceAPayerTrait
{

    private ?int $id = null;

    private Collection $miseEnPaiement;

    protected ?Collection $centreCout = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function addMiseEnPaiement(MiseEnPaiement $miseEnPaiement): self
    {
        $this->miseEnPaiement[] = $miseEnPaiement;

        return $this;
    }



    public function removeMiseEnPaiement(MiseEnPaiement $miseEnPaiement): self
    {
        $this->miseEnPaiement->removeElement($miseEnPaiement);

        return $this;
    }



    /**
     * @return Collection|MiseEnPaiement[]
     */
    public function getMiseEnPaiement(): Collection
    {
        return $this->miseEnPaiement;
    }



    /**
     * @param TypeHeures|null $typeHeures
     * @return Collection|CentreCout[]
     */
    public function getCentreCout(TypeHeures $typeHeures = null): Collection
    {
        $filter = function (CentreCout $centreCout) use ($typeHeures) {
            if ($typeHeures) {
                return $centreCout->typeHeuresMatches($typeHeures);
            } else {
                return true;
            }
        };

        // Hack pour éviter un problème d'initialisation
        if (null === $this->centreCout){
            $this->centreCout = new ArrayCollection();
        }

        return $this->centreCout->filter($filter);
    }



    public function getMiseEnPaiementListe(\DateTime $dateMiseEnPaiement = null, Periode $periodePaiement = null): MiseEnPaiementListe
    {
        $liste = new MiseEnPaiementListe($this);
        if ($dateMiseEnPaiement) $liste->setDateMiseEnPaiement($dateMiseEnPaiement);
        if ($periodePaiement) $liste->setPeriodePaiement($periodePaiement);

        return $liste;
    }


}