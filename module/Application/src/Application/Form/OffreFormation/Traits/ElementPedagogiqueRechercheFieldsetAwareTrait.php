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
    /**
     * @var ElementPedagogiqueRechercheFieldset
     */
    private $fieldsetOffreFormationElementPedagogiqueRecherche;



    /**
     * @param ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche
     *
     * @return self
     */
    public function setFieldsetOffreFormationElementPedagogiqueRecherche(ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche)
    {
        $this->fieldsetOffreFormationElementPedagogiqueRecherche = $fieldsetOffreFormationElementPedagogiqueRecherche;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementPedagogiqueRechercheFieldset
     */
    public function getFieldsetOffreFormationElementPedagogiqueRecherche()
    {
        if (!empty($this->fieldsetOffreFormationElementPedagogiqueRecherche)) {
            return $this->fieldsetOffreFormationElementPedagogiqueRecherche;
        }

        return \Application::$container->get('FormElementManager')->get(ElementPedagogiqueRechercheFieldset::class);
    }
}