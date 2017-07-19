<?php

namespace Application\Service\Traits;

use Application\Service\TypeRessourceService;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeRessourceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeRessourceServiceAwareTrait
{
    /**
     * @var TypeRessourceService
     */
    private $serviceTypeRessource;





    /**
     * @param TypeRessourceService $serviceTypeRessource
     * @return self
     */
    public function setServiceTypeRessource( TypeRessourceService $serviceTypeRessource )
    {
        $this->serviceTypeRessource = $serviceTypeRessource;
        return $this;
    }



    /**
     * @return TypeRessourceService
     * @throws RuntimeException
     */
    public function getServiceTypeRessource()
    {
        if (empty($this->serviceTypeRessource)){
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
        $this->serviceTypeRessource = $serviceLocator->get('applicationTypeRessource');
        }
        return $this->serviceTypeRessource;
    }
}