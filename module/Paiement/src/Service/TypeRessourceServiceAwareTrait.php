<?php

namespace Paiement\Service;

/**
 * Description of TypeRessourceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeRessourceServiceAwareTrait
{
    protected ?TypeRessourceService $serviceTypeRessource = null;



    /**
     * @param TypeRessourceService $serviceTypeRessource
     *
     * @return self
     */
    public function setServiceTypeRessource(?TypeRessourceService $serviceTypeRessource)
    {
        $this->serviceTypeRessource = $serviceTypeRessource;

        return $this;
    }



    public function getServiceTypeRessource(): ?TypeRessourceService
    {
        if (empty($this->serviceTypeRessource)) {
            $this->serviceTypeRessource = \Application::$container->get(TypeRessourceService::class);
        }

        return $this->serviceTypeRessource;
    }
}