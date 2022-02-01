<?php

namespace Application\Service\Traits;

use Application\Service\PerimetreService;

/**
 * Description of PerimetreServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PerimetreServiceAwareTrait
{
    protected ?PerimetreService $servicePerimetre;



    /**
     * @param PerimetreService|null $servicePerimetre
     *
     * @return self
     */
    public function setServicePerimetre( ?PerimetreService $servicePerimetre )
    {
        $this->servicePerimetre = $servicePerimetre;

        return $this;
    }



    public function getServicePerimetre(): ?PerimetreService
    {
        if (!$this->servicePerimetre){
            $this->servicePerimetre = \Application::$container->get(PerimetreService::class);
        }

        return $this->servicePerimetre;
    }
}