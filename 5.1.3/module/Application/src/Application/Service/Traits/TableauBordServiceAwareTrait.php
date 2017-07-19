<?php

namespace Application\Service\Traits;

use Application\Service\TableauBordService;
use Application\Module;
use RuntimeException;

/**
 * Description of TableauBordServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TableauBordServiceAwareTrait
{
    /**
     * @var TableauBordService
     */
    private $serviceTableauBord;





    /**
     * @param TableauBordService $serviceTableauBord
     * @return self
     */
    public function setServiceTableauBord( TableauBordService $serviceTableauBord )
    {
        $this->serviceTableauBord = $serviceTableauBord;
        return $this;
    }



    /**
     * @return TableauBordService
     * @throws RuntimeException
     */
    public function getServiceTableauBord()
    {
        if (empty($this->serviceTableauBord)){
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
            $this->serviceTableauBord = $serviceLocator->get('tableauBord');
        }
        return $this->serviceTableauBord;
    }
}