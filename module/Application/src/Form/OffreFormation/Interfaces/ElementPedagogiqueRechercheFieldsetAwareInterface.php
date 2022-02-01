<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset;

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
    public function setFormOffreFormationElementPedagogiqueRechercheFieldset( ElementPedagogiqueRechercheFieldset $formOffreFormationElementPedagogiqueRechercheFieldset );



    public function getFormOffreFormationElementPedagogiqueRechercheFieldset(): ?ElementPedagogiqueRechercheFieldset;
}