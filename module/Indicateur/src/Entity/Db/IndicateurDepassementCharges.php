<?php

namespace Indicateur\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\PeriodeAwareTrait;
use OffreFormation\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use OffreFormation\Entity\Db\Traits\TypeInterventionAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use Lieu\Entity\Db\StructureAwareTrait;


/**
 * IndicateurDepassementCharges
 */
class IndicateurDepassementCharges
{
    use AnneeAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use TypeInterventionAwareTrait;
    use PeriodeAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var float
     */
    protected $heures;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }



    /**
     * @param float $heures
     *
     * @return IndicateurDepassementCharges
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }

}
