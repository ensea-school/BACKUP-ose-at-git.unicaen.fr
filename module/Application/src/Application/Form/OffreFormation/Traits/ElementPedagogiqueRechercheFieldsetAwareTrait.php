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
     * @return ElementPedagogiqueRechercheFieldset
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationElementPedagogiqueRecherche()
    {
        if (empty($this->fieldsetOffreFormationElementPedagogiqueRecherche)){
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
            $this->fieldsetOffreFormationElementPedagogiqueRecherche = $serviceLocator->get('FormElementManager')->get('FormElementPedagogiqueRechercheFieldset');
        }
        return $this->fieldsetOffreFormationElementPedagogiqueRecherche;
    }
}