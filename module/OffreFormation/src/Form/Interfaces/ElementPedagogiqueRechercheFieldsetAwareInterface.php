<?php

namespace OffreFormation\Form\Interfaces;

use OffreFormation\Form\ElementPedagogiqueRechercheFieldset;

/**
 * Description of ElementPedagogiqueRechercheFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementPedagogiqueRechercheFieldsetAwareInterface
{
    /**
     * @param ElementPedagogiqueRechercheFieldset|null $formOffreFormationElementPedagogiqueRechercheFieldset
     *
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueRechercheFieldset( ?ElementPedagogiqueRechercheFieldset $formOffreFormationElementPedagogiqueRechercheFieldset );



    public function getFormOffreFormationElementPedagogiqueRechercheFieldset(): ?ElementPedagogiqueRechercheFieldset;
}