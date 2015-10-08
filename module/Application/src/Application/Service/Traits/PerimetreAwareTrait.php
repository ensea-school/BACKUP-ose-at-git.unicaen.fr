<?php

namespace Application\Service\Traits;

use Application\Service\Perimetre;
use Application\Module;
use RuntimeException;

/**
 * Description of PerimetreAwareTrait
 *
 * @author UnicaenCode
 */
trait PerimetreAwareTrait
{
    /**
     * @var Perimetre
     */
    private $servicePerimetre;





    /**
     * @param Perimetre $servicePerimetre
     * @return self
     */
    public function setServicePerimetre( Perimetre $servicePerimetre )
    {
        $this->servicePerimetre = $servicePerimetre;
        return $this;
    }



    /**
     * @return Perimetre
     * @throws RuntimeException
     */
    public function getServicePerimetre()
    {
        if (empty($this->servicePerimetre)){
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
        $this->servicePerimetre = $serviceLocator->get('ApplicationPerimetre');
        }
        return $this->servicePerimetre;
    }
}