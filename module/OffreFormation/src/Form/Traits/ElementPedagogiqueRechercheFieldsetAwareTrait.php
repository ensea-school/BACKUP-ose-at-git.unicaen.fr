<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\ElementPedagogiqueRechercheFieldset;

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
    public function setFieldsetOffreFormationElementPedagogiqueRecherche(?ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche)
    {
        $this->fieldsetOffreFormationElementPedagogiqueRecherche = $fieldsetOffreFormationElementPedagogiqueRecherche;

        return $this;
    }



    public function getFieldsetOffreFormationElementPedagogiqueRecherche(): ?ElementPedagogiqueRechercheFieldset
    {
        if (!empty($this->fieldsetOffreFormationElementPedagogiqueRecherche)) {
            return $this->fieldsetOffreFormationElementPedagogiqueRecherche;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ElementPedagogiqueRechercheFieldset::class);
    }
}