<?php

namespace OffreFormation\Entity\Db;

use Application\Entity\Db\Traits\SourceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use OffreFormation\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use OffreFormation\Entity\Db\Traits\TypeInterventionAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * VolumeHoraireEns
 */
class VolumeHoraireEns implements HistoriqueAwareInterface, ImportAwareInterface, ResourceInterface
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
     * @var float
     */
    protected $groupes;



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
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
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
     * @return float
     */
    public function getGroupes()
    {
        return $this->groupes;
    }



    /**
     * @param float $groupes
     *
     * @return VolumeHoraireEns
     */
    public function setGroupes($groupes)
    {
        $this->groupes = $groupes;

        return $this;
    }



    public function getResourceId(): string
    {
        return self::class;
    }

}