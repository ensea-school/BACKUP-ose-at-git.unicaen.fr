<?php

namespace Application\Service\Traits;

use Application\Service\PerimetreService;

/**
 * Description of PerimetreAwareTrait
 *
 * @author UnicaenCode
 */
trait PerimetreServiceAwareTrait
{
    /**
     * @var PerimetreService
     */
    private $servicePerimetre;



    /**
     * @param PerimetreService $servicePerimetre
     *
     * @return self
     */
    public function setServicePerimetre(PerimetreService $servicePerimetre)
    {
        $this->servicePerimetre = $servicePerimetre;

        return $this;
    }



    /**
     * @return PerimetreService
     */
    public function getServicePerimetre()
    {
        if (empty($this->servicePerimetre)) {
            $this->servicePerimetre = \Application::$container->get(PerimetreService::class);
        }

        return $this->servicePerimetre;
    }
}