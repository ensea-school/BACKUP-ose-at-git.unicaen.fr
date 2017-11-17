<?php

namespace Application\Service\Traits;

use Application\Service\TypeRessourceService;

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
     *
     * @return self
     */
    public function setServiceTypeRessource(TypeRessourceService $serviceTypeRessource)
    {
        $this->serviceTypeRessource = $serviceTypeRessource;

        return $this;
    }



    /**
     * @return TypeRessourceService
     */
    public function getServiceTypeRessource()
    {
        if (empty($this->serviceTypeRessource)) {
            $this->serviceTypeRessource = \Application::$container->get('applicationTypeRessource');
        }

        return $this->serviceTypeRessource;
    }
}