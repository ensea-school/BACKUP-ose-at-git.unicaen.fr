<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\HeuresCompForm;
use Application\Module;
use RuntimeException;

/**
 * Description of HeuresCompFormAwareTrait
 *
 * @author UnicaenCode
 */
trait HeuresCompFormAwareTrait
{
    /**
     * @var HeuresCompForm
     */
    private $formIntervenantHeuresComp;





    /**
     * @param HeuresCompForm $formIntervenantHeuresComp
     * @return self
     */
    public function setFormIntervenantHeuresComp( HeuresCompForm $formIntervenantHeuresComp )
    {
        $this->formIntervenantHeuresComp = $formIntervenantHeuresComp;
        return $this;
    }



    /**
     * @return HeuresCompForm
     * @throws RuntimeException
     */
    public function getFormIntervenantHeuresComp()
    {
        if (empty($this->formIntervenantHeuresComp)){
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
            $this->formIntervenantHeuresComp = $serviceLocator->get('FormElementManager')->get('IntervenantHeuresCompForm');
        }
        return $this->formIntervenantHeuresComp;
    }
}