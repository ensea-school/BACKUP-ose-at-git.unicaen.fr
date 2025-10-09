<?php
namespace OffreFormation\Form\EtapeTauxRemu;



/**
 * Description of ElementTauxRemuFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementTauxRemuFieldsetAwareTrait
{
    protected ?ElementTauxRemuFieldset $fieldsetEtapeTauxRemuElementTauxRemu = null;



    /**
     * @param ElementTauxRemuFieldset $fieldsetEtapeTauxRemuElementTauxRemu
     *
     * @return self
     */
    public function setFieldsetEtapeTauxRemuElementTauxRemu(?ElementTauxRemuFieldset $fieldsetEtapeTauxRemuElementTauxRemu)
    {
        $this->fieldsetEtapeTauxRemuElementTauxRemu = $fieldsetEtapeTauxRemuElementTauxRemu;

        return $this;
    }



    public function getFieldsetEtapeTauxRemuElementTauxRemu(): ?ElementTauxRemuFieldset
    {
        if (!empty($this->fieldsetEtapeTauxRemuElementTauxRemu)) {
            return $this->fieldsetEtapeTauxRemuElementTauxRemu;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ElementTauxRemuFieldset::class);
    }
}