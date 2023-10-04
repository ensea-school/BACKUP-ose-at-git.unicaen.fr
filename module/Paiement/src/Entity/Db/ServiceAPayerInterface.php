<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\DomaineFonctionnel;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Periode;
use Doctrine\Common\Collections\Collection;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\TypeHeures;
use Paiement\Entity\MiseEnPaiementListe;

/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface ServiceAPayerInterface
{
    /* Méthodes communes */

    public function getId(): ?int;



    public function getStructure(): ?Structure;



    public function getIntervenant(): ?Intervenant;



    /* Récupération des heures */

    public function getHeuresComplFi(): float;



    public function getHeuresComplFa(): float;



    public function getHeuresComplFc(): float;



    public function getHeuresComplFcMajorees(): float;



    public function getHeuresComplReferentiel(): float;



    public function getHeuresMission(): float;



    public function getHeuresCompl(TypeHeures $typeHeures): float;



    /* Gestion des mises en paiement*/

    public function addMiseEnPaiement(MiseEnPaiement $miseEnPaiement): self;



    public function removeMiseEnPaiement(MiseEnPaiement $miseEnPaiement): self;



    /**
     * @return Collection|MiseEnPaiement[]
     */
    public function getMiseEnPaiement(): Collection;



    public function getMiseEnPaiementListe(\DateTime $dateMiseEnPaiement = null, Periode $periodePaiement = null): MiseEnPaiementListe;



    /* Gestion des centres de coûts */

    /**
     * @param TypeHeures|null $typeHeures
     * @return Collection|CentreCout[]
     */
    public function getCentreCout(TypeHeures $typeHeures = null): Collection;



    public function getDefaultCentreCout(TypeHeures $typeHeures): ?CentreCout;



    /* Gestion des domaines fonctionnels */

    public function getDefaultDomaineFonctionnel(): ?DomaineFonctionnel;



    public function isDomaineFonctionnelModifiable(): bool;



    /* Détermine si c'est payable ou non */

    public function isPayable(): bool;
}