<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use Application\Entity\Db\Traits\SourceAwareTrait;
use Application\Entity\Db\Traits\TypeInterventionAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * VolumeHoraireEns
 */
class VolumeHoraireEns implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use TypeInterventionAwareTrait;

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
     * Set heures
     *
     * @param float $heures
     *
     * @return VolumeHoraireEns
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }

}
