<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset;
use RuntimeException;

/**
 * Description of ElementPedagogiqueRechercheFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementPedagogiqueRechercheFieldsetAwareInterface
{
    /**
     * @param ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche
     * @return self
     */
    public function setFieldsetOffreFormationElementPedagogiqueRecherche( ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche );



    /**
     * @return ElementPedagogiqueRechercheFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationElementPedagogiqueRecherche();
}