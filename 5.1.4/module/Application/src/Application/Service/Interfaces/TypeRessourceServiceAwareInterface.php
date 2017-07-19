<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeRessourceService;
use RuntimeException;

/**
 * Description of TypeRessourceServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeRessourceServiceAwareInterface
{
    /**
     * @param TypeRessourceService $serviceTypeRessource
     * @return self
     */
    public function setServiceTypeRessource( TypeRessourceService $serviceTypeRessource );



    /**
     * @return TypeRessourceServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeRessource();
}