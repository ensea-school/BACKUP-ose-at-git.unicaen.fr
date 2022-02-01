<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleResultatVolumeHoraireReferentiel;

/**
 * Description of FormuleResultatVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireReferentielAwareTrait
{
    protected ?FormuleResultatVolumeHoraireReferentiel $entityDbFormuleResultatVolumeHoraireReferentiel;



    /**
     * @param FormuleResultatVolumeHoraireReferentiel|null $entityDbFormuleResultatVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setEntityDbFormuleResultatVolumeHoraireReferentiel( ?FormuleResultatVolumeHoraireReferentiel $entityDbFormuleResultatVolumeHoraireReferentiel )
    {
        $this->entityDbFormuleResultatVolumeHoraireReferentiel = $entityDbFormuleResultatVolumeHoraireReferentiel;

        return $this;
    }



    public function getEntityDbFormuleResultatVolumeHoraireReferentiel(): ?FormuleResultatVolumeHoraireReferentiel
    {
        if (!$this->entityDbFormuleResultatVolumeHoraireReferentiel){
            $this->entityDbFormuleResultatVolumeHoraireReferentiel = \Application::$container->get('FormElementManager')->get(FormuleResultatVolumeHoraireReferentiel::class);
        }

        return $this->entityDbFormuleResultatVolumeHoraireReferentiel;
    }
}