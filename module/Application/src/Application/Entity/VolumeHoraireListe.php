<?php

namespace Application\Entity;

use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\Service;
use Application\Entity\Db\Periode;
use Application\Entity\Db\TypeIntervention;

/**
 * Description of VolumeHoraireList
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireListe
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
     * @param \Application\Entity\Db\Service|null $service
     * @param \Application\Entity\Db\Periode|null $periode
     */
    function __construct(Service $service=null, Periode $periode=null)
    {
        if ($service) $this->setService($service);
        if ($periode) $this->setPeriode($periode);
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
     * @return Periode
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     *
     * @param \Application\Entity\Db\Service $service
     * @return \Application\Entity\VolumeHoraireList
     */
    public function setService(Service $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     *
     * @param \Application\Entity\Db\Periode $periode
     * @return \Application\Entity\VolumeHoraireList
     */
    public function setPeriode(Periode $periode)
    {
        $this->periode = $periode;
        return $this;
    }

    /**
     * Ajoute un volume horaire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     * @return \Application\Entity\VolumeHoraireListe
     */
    public function add( VolumeHoraire $volumeHoraire )
    {
        if (! $volumeHoraire->getService()){
            $volumeHoraire->setService( $this->getservice());
        }
        $this->checkVolumeHoraireEligibilite($volumeHoraire);
        $this->getService()->addVolumeHoraire($volumeHoraire);
        return $this;
    }

    /**
     * Retire un volume horaire
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     * @return \Application\Entity\VolumeHoraireListe
     */
    public function remove( VolumeHoraire $volumeHoraire )
    {
        $this->checkVolumeHoraireEligibilite($volumeHoraire);
        $volumeHoraire->setRemove(true);
        return $this;
    }

    /**
     * Retourne la liste des volumes horaires du service (sans les motifs de non paiement)
     * Les clés sont les codes des types d'intervention des volumes horaires
     *
     * @return VolumeHoraire[]
     */
    public function get()
    {
        $data = array();
        foreach( $this->getService()->getVolumeHoraire() as $volumeHoraire ){
            if (! $volumeHoraire->getMotifNonPaiement() && $volumeHoraire->getPeriode() === $this->getPeriode() && ! $volumeHoraire->getRemove()){
                $data[$volumeHoraire->getTypeIntervention()->getCode()] = $volumeHoraire;
            }
        }
        return $data;
    }

    /**
     *
     * @param \Application\Entity\Db\TypeIntervention $typeIntervention
     * @return Volumehoraire|null
     */
    public function getWithTypeIntervention( TypeIntervention $typeIntervention )
    {
        $data = $this->get();
        if (isset($data[$typeIntervention->getCode()])){
            return $data[$typeIntervention->getCode()];
        }else{
            return null;
        }
    }

    /**
     * Vérifie l'éligibilité d'un volume horaire à la liste
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     * @return true
     * @throws \Common\Exception\LogicException
     */
    protected function checkVolumeHoraireEligibilite( VolumeHoraire $volumeHoraire )
    {
        if ($volumeHoraire->getPeriode() !== $this->getPeriode()){
            throw new \Common\Exception\LogicException('La période du volume horaire ne correspond pas à la période de la liste');
        }
        if ($volumeHoraire->getService() !== $this->getService()){
            throw new \Common\Exception\LogicException('Le service du volume horaire ne correspond pas au service de la liste');
        }
        if ($volumeHoraire->getMotifNonPaiement()){
            throw new \Common\Exception\LogicException('La liste ne gère pas les motifs de non paiement');
        }
        return true;
    }
}