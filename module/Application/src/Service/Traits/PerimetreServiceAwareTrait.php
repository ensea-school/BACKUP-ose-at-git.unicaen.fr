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
    protected ?PerimetreService $servicePerimetre = null;



    /**
     * @param PerimetreService $servicePerimetre
     *
     * @return self
     */
    public function setServicePerimetre(?PerimetreService $servicePerimetre)
    {
        $this->servicePerimetre = $servicePerimetre;

        return $this;
    }



    public function getServicePerimetre(): ?PerimetreService
    {
        if (empty($this->servicePerimetre)) {
            $this->servicePerimetre = \Framework\Application\Application::getInstance()->container()->get(PerimetreService::class);
        }

        return $this->servicePerimetre;
    }
}