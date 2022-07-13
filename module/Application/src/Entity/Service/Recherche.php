<?php

namespace Application\Entity\Service;

use Application\Entity\Db\EtatVolumeHoraire;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Traits\NiveauEtapeAwareTrait;
use Application\Entity\Db\Traits\EtapeAwareTrait;
use Application\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Entity\Db\Traits\EtatVolumeHoraireAwareTrait;
use Application\Entity\Db\Structure;

/**
 * Description of Recherche
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Recherche
{

    use TypeIntervenantAwareTrait;
    use IntervenantAwareTrait;
    use NiveauEtapeAwareTrait;
    use EtapeAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;

    /**
     * Structure d'affectation
     *
     * @var \Application\Entity\Db\Structure
     */
    protected $structureAff;

    /**
     * Structure d'enseignement
     *
     * @var \Application\Entity\Db\Structure
     */
    protected $structureEns;



    /**
     * Retourne la structure d'affectation
     *
     * @return Structure
     */
    function getStructureAff()
    {
        return $this->structureAff;
    }



    /**
     * Assigne une structure d'affectation
     *
     * @param Structure $structureAff
     *
     * @return self
     */
    function setStructureAff(Structure $structureAff = null)
    {
        $this->structureAff = $structureAff;

        return $this;
    }



    /**
     * Retourne la structure d'enseignement
     *
     * @return Structure
     */
    function getStructureEns()
    {
        return $this->structureEns;
    }



    /**
     * Assigne une structure d'enseignement
     *
     * @param Structure $structureEns
     *
     * @return self
     */
    function setStructureEns(Structure $structureEns = null)
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



    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        if ($typeVolumeHoraire) $this->setTypeVolumeHoraire($typeVolumeHoraire);
        if ($etatVolumeHoraire) $this->setEtatVolumeHoraire($etatVolumeHoraire);
    }

}