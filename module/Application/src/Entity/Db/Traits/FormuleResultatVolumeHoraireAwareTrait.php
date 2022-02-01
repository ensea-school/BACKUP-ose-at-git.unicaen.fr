<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleResultatVolumeHoraire;

/**
 * Description of FormuleResultatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireAwareTrait
{
    protected ?FormuleResultatVolumeHoraire $entityDbFormuleResultatVolumeHoraire;



    /**
     * @param FormuleResultatVolumeHoraire|null $entityDbFormuleResultatVolumeHoraire
     *
     * @return self
     */
    public function setEntityDbFormuleResultatVolumeHoraire( ?FormuleResultatVolumeHoraire $entityDbFormuleResultatVolumeHoraire )
    {
        $this->entityDbFormuleResultatVolumeHoraire = $entityDbFormuleResultatVolumeHoraire;

        return $this;
    }



    public function getEntityDbFormuleResultatVolumeHoraire(): ?FormuleResultatVolumeHoraire
    {
        if (!$this->entityDbFormuleResultatVolumeHoraire){
            $this->entityDbFormuleResultatVolumeHoraire = \Application::$container->get('FormElementManager')->get(FormuleResultatVolumeHoraire::class);
        }

        return $this->entityDbFormuleResultatVolumeHoraire;
    }
}