<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset;

/**
 * Description of ElementPedagogiqueRechercheFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueRechercheFieldsetAwareTrait
{
    protected ?ElementPedagogiqueRechercheFieldset $formOffreFormationElementPedagogiqueRechercheFieldset = null;



    /**
     * @param ElementPedagogiqueRechercheFieldset $formOffreFormationElementPedagogiqueRechercheFieldset
     *
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueRechercheFieldset( ElementPedagogiqueRechercheFieldset $formOffreFormationElementPedagogiqueRechercheFieldset )
    {
        $this->formOffreFormationElementPedagogiqueRechercheFieldset = $formOffreFormationElementPedagogiqueRechercheFieldset;

        return $this;
    }



    public function getFormOffreFormationElementPedagogiqueRechercheFieldset(): ?ElementPedagogiqueRechercheFieldset
    {
        if (empty($this->formOffreFormationElementPedagogiqueRechercheFieldset)){
            $this->formOffreFormationElementPedagogiqueRechercheFieldset = \Application::$container->get('FormElementManager')->get(ElementPedagogiqueRechercheFieldset::class);
        }

        return $this->formOffreFormationElementPedagogiqueRechercheFieldset;
    }
}