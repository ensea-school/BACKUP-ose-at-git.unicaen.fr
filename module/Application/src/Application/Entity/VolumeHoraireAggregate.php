<?php

namespace Application\Entity;

use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\Service;
use Application\Entity\Db\Periode;
use Application\Entity\Db\TypeIntervention;

/**
 * Description of VolumeHoraireAggregate
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireAggregate
{
    /**
     * @var Service
     */
    protected $service;

    /**
     * @var Periode
     */
    protected $periode;

    /**
     *
     * @var TypeIntervention
     */
    protected $typeIntervention;

    /**
     *
     * @param \Application\Entity\Db\Service|null $service
     * @param \Application\Entity\Db\Periode|null $periode
     */
    function __construct(Service $service=null, Periode $periode=null, TypeIntervention $typeIntervention=null)
    {
        if ($service)          $this->setService($service);
        if ($periode)          $this->setPeriode($periode);
        if ($typeIntervention) $this->setTypeIntervention($typeIntervention);
    }

    /**
     *
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     *
     * @param Service $service
     * @return self
     */
    public function setService(Service $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     *
     * @return Periode
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     *
     * @param Periode $periode
     * @return self
     */
    public function setPeriode(Periode $periode)
    {
        $this->periode = $periode;
        return $this;
    }

    /**
     * 
     * @return TypeIntervention
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }

    /**
     * 
     * @param TypeIntervention $typeIntervention
     * @return self
     */
    public function setTypeIntervention(TypeIntervention $typeIntervention)
    {
        $this->typeIntervention = $typeIntervention;
        return $this;
    }


}