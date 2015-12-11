<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setFieldsetOffreFormationElementPedagogiqueRecherche( ElementPedagogiqueRechercheFieldset $fieldsetOffreFormationElementPedagogiqueRecherche )
    {
        $this->fieldsetOffreFormationElementPedagogiqueRecherche = $fieldsetOffreFormationElementPedagogiqueRecherche;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementPedagogiqueRechercheFieldset
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationElementPedagogiqueRecherche()
    {
        if (!empty($this->fieldsetOffreFormationElementPedagogiqueRecherche)){
            return $this->fieldsetOffreFormationElementPedagogiqueRecherche;
        }

        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        return $serviceLocator->get('FormElementManager')->get('FormElementPedagogiqueRechercheFieldset');
    }
}