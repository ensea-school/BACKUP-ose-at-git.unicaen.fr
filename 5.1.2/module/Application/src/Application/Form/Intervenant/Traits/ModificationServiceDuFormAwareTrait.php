<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\ModificationServiceDuForm;
use Application\Module;
use RuntimeException;

/**
 * Description of ModificationServiceDuFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ModificationServiceDuFormAwareTrait
{
    /**
     * @var ModificationServiceDuForm
     */
    private $formIntervenantModificationServiceDu;





    /**
     * @param ModificationServiceDuForm $formIntervenantModificationServiceDu
     * @return self
     */
    public function setFormIntervenantModificationServiceDu( ModificationServiceDuForm $formIntervenantModificationServiceDu )
    {
        $this->formIntervenantModificationServiceDu = $formIntervenantModificationServiceDu;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ModificationServiceDuForm
     * @throws RuntimeException
     */
    public function getFormIntervenantModificationServiceDu()
    {
        if (!empty($this->formIntervenantModificationServiceDu)){
            return $this->formIntervenantModificationServiceDu;
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
        return $serviceLocator->get('FormElementManager')->get('IntervenantModificationServiceDuForm');
    }
}