<?php

namespace Application\Service\Traits;

use Application\Service\TypeVolumeHoraireService;

/**
 * Description of TypeVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeVolumeHoraireServiceAwareTrait
{
    /**
     * @var TypeVolumeHoraireService
     */
    private $serviceTypeVolumeHoraire;



    /**
     * @param TypeVolumeHoraireService $serviceTypeVolumeHoraire
     *
     * @return self
     */
    public function setServiceTypeVolumeHoraire(TypeVolumeHoraireService $serviceTypeVolumeHoraire)
    {
        $this->serviceTypeVolumeHoraire = $serviceTypeVolumeHoraire;

        return $this;
    }



    /**
     * @return TypeVolumeHoraireService
     */
    public function getServiceTypeVolumeHoraire()
    {
        if (empty($this->serviceTypeVolumeHoraire)) {
            $this->serviceTypeVolumeHoraire = \Application::$container->get('ApplicationTypeVolumeHoraire');
        }

        return $this->serviceTypeVolumeHoraire;
    }
}