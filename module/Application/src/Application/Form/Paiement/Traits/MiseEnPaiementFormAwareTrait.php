<?php

namespace Application\Form\Paiement\Traits;

use Application\Form\Paiement\MiseEnPaiementForm;
use Application\Module;
use RuntimeException;

/**
 * Description of MiseEnPaiementFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementFormAwareTrait
{
    /**
     * @var MiseEnPaiementForm
     */
    private $formPaiementMiseEnPaiement;





    /**
     * @param MiseEnPaiementForm $formPaiementMiseEnPaiement
     * @return self
     */
    public function setFormPaiementMiseEnPaiement( MiseEnPaiementForm $formPaiementMiseEnPaiement )
    {
        $this->formPaiementMiseEnPaiement = $formPaiementMiseEnPaiement;
        return $this;
    }



    /**
     * @return MiseEnPaiementForm
     * @throws RuntimeException
     */
    public function getFormPaiementMiseEnPaiement()
    {
        if (empty($this->formPaiementMiseEnPaiement)){
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
            $this->formPaiementMiseEnPaiement = $serviceLocator->get('FormElementManager')->get('PaiementMiseEnPaiementForm');
        }
        return $this->formPaiementMiseEnPaiement;
    }
}