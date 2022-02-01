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
    protected ?ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche = null;



    /**
     * @param ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche
     *
     * @return self
     */
    public function setFieldsetOffreFormationElementPedagogiqueRecherche( ?ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche )
    {
        $this->fieldsetOffreFormationElementPedagogiqueRecherche = $fieldsetOffreFormationElementPedagogiqueRecherche;

        return $this;
    }



    public function getFieldsetOffreFormationElementPedagogiqueRecherche(): ?ElementPedagogiqueRechercheFieldset
    {
        if (empty($this->fieldsetOffreFormationElementPedagogiqueRecherche)){
            $this->fieldsetOffreFormationElementPedagogiqueRecherche = \Application::$container->get('FormElementManager')->get(ElementPedagogiqueRechercheFieldset::class);
        }

        return $this->fieldsetOffreFormationElementPedagogiqueRecherche;
    }
}