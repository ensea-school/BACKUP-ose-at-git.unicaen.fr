<?php

namespace Application\Entity\Service;

use Service\Entity\Db\EtatVolumeHoraire;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Traits\NiveauEtapeAwareTrait;
use Application\Entity\Db\Traits\EtapeAwareTrait;
use Application\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use Service\Entity\Db\EtatVolumeHoraireAwareTrait;
use Application\Entity\Db\Structure;

class Recherche
{

    use TypeIntervenantAwareTrait;
    use IntervenantAwareTrait;
    use NiveauEtapeAwareTrait;
    use EtapeAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;

    protected ?Structure $structureAff;

    protected ?Structure $structureEns;



    function getStructureAff(): ?Structure
    {
        return $this->structureAff;
    }



    function setStructureAff(?Structure $structureAff = null): Recherche
    {
        $this->structureAff = $structureAff;

        return $this;
    }



    function getStructureEns(): ?Structure
    {
        return $this->structureEns;
    }



    function setStructureEns(?Structure $structureEns = null): Recherche
    {
        $this->structureEns = $structureEns;

        return $this;
    }



    public function getFilters(): array
    {
        $filters = [];
        if ($c1 = $this->getTypeVolumeHoraire()) $filters['TYPE_VOLUME_HORAIRE_ID'] = $c1->getId();
        if ($c2 = $this->getEtatVolumeHoraire()) $filters['ETAT_VOLUME_HORAIRE_ID'] = $c2->getId();
        if ($c3 = $this->getTypeIntervenant()) $filters['TYPE_INTERVENANT_ID'] = $c3->getId();
        if ($c4 = $this->getIntervenant()) $filters['INTERVENANT_ID'] = $c4->getId();
        if ($c6 = $this->getEtape()) $filters['ETAPE_ID'] = $c6->getId();
        if ($c7 = $this->getElementPedagogique()) $filters['ELEMENT_PEDAGOGIQUE_ID'] = $c7->getId();
        if ($c8 = $this->getStructureAff()) $filters['STRUCTURE_AFF_ID'] = $c8->getId();
        if ($c9 = $this->getStructureEns()) $filters['STRUCTURE_ENS_ID'] = $c9->getId();

        return $filters;
    }



    public function __construct(?TypeVolumeHoraire $typeVolumeHoraire = null, ?EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        if ($typeVolumeHoraire) $this->setTypeVolumeHoraire($typeVolumeHoraire);
        if ($etatVolumeHoraire) $this->setEtatVolumeHoraire($etatVolumeHoraire);
    }

}