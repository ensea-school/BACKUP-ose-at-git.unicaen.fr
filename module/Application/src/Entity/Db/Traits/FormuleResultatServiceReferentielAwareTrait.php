<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleResultatServiceReferentiel;

/**
 * Description of FormuleResultatServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceReferentielAwareTrait
{
    protected ?FormuleResultatServiceReferentiel $entityDbFormuleResultatServiceReferentiel;



    /**
     * @param FormuleResultatServiceReferentiel|null $entityDbFormuleResultatServiceReferentiel
     *
     * @return self
     */
    public function setEntityDbFormuleResultatServiceReferentiel( ?FormuleResultatServiceReferentiel $entityDbFormuleResultatServiceReferentiel )
    {
        $this->entityDbFormuleResultatServiceReferentiel = $entityDbFormuleResultatServiceReferentiel;

        return $this;
    }



    public function getEntityDbFormuleResultatServiceReferentiel(): ?FormuleResultatServiceReferentiel
    {
        if (!$this->entityDbFormuleResultatServiceReferentiel){
            $this->entityDbFormuleResultatServiceReferentiel = \Application::$container->get('FormElementManager')->get(FormuleResultatServiceReferentiel::class);
        }

        return $this->entityDbFormuleResultatServiceReferentiel;
    }
}