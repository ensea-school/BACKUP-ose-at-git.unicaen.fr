<?php

namespace Application\Entity\Service;

use Application\Interfaces\TypeIntervenantAwareInterface;       use Application\Traits\TypeIntervenantAwareTrait;
use Application\Interfaces\IntervenantAwareInterface;           use Application\Traits\IntervenantAwareTrait;
use Application\Interfaces\NiveauEtapeAwareInterface;           use Application\Traits\NiveauEtapeAwareTrait;
use Application\Interfaces\EtapeAwareInterface;                 use Application\Traits\EtapeAwareTrait;
use Application\Interfaces\ElementPedagogiqueAwareInterface;    use Application\Traits\ElementPedagogiqueAwareTrait;
use Application\Interfaces\TypeVolumeHoraireAwareInterface;     use Application\Traits\TypeVolumeHoraireAwareTrait;
use Application\Interfaces\EtatVolumeHoraireAwareInterface;     use Application\Traits\EtatVolumeHoraireAwareTrait;
use Application\Entity\Db\Structure;

/**
 * Description of Recherche
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Recherche

implements
    TypeIntervenantAwareInterface,
    IntervenantAwareInterface,
    NiveauEtapeAwareInterface,
    EtapeAwareInterface,
    ElementPedagogiqueAwareInterface,
    TypeVolumeHoraireAwareInterface,
    EtatVolumeHoraireAwareInterface

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
     * @return self
     */
    function setStructureEns(Structure $structureEns = null)
    {
        $this->structureEns = $structureEns;
        return $this;
    }
}