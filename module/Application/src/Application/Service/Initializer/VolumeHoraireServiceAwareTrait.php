<?php

namespace Application\Service\Initializer;

use Application\Service\VolumeHoraire as VolumeHoraireService;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait VolumeHoraireServiceAwareTrait
{
    /**
     * @var VolumeHoraireService
     */
    protected $volumeHoraireService;
    
    /**
     * Spécifie le service VolumeHoraire.
     *
     * @param VolumeHoraireService $volumeHoraireService
     * @return self
     */
    public function setServiceVolumeHoraire(VolumeHoraireService $volumeHoraireService = null)
    {
        $this->volumeHoraireService = $volumeHoraireService;
        
        return $this;
    }
    
    /**
     * Retourne le service VolumeHoraire.
     *
     * @return VolumeHoraireService
     */
    public function getServiceVolumeHoraire()
    {
        return $this->volumeHoraireService;
    }
}