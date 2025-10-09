<?php

namespace OffreFormation\Form\EtapeTauxRemu;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Description of EtapeTauxRemuFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeTauxRemuFormAwareTrait
{
    protected ?EtapeTauxRemuForm $formEtapeTauxRemuEtapeTauxRemu = null;



    /**
     * @param EtapeTauxRemuForm|null $formEtapeTauxRemuEtapeTauxRemu
     *
     * @return self
     */
    public function setFormEtapeTauxRemuEtapeTauxRemu(?EtapeTauxRemuForm $formEtapeTauxRemuEtapeTauxRemu)
    {
        $this->formEtapeTauxRemuEtapeTauxRemu = $formEtapeTauxRemuEtapeTauxRemu;

        return $this;
    }



    /**
     * @return EtapeTauxRemuForm|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getFormEtapeTauxRemuEtapeTauxRemu(): ?EtapeTauxRemuForm
    {
        if (!empty($this->formEtapeTauxRemuEtapeTauxRemu)) {
            return $this->formEtapeTauxRemuEtapeTauxRemu;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EtapeTauxRemuForm::class);
    }
}