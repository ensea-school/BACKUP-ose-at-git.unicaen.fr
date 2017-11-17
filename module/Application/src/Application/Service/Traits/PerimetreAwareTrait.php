<?php

namespace Application\Service\Traits;

use Application\Service\Perimetre;

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
     *
     * @return self
     */
    public function setServicePerimetre(Perimetre $servicePerimetre)
    {
        $this->servicePerimetre = $servicePerimetre;

        return $this;
    }



    /**
     * @return Perimetre
     */
    public function getServicePerimetre()
    {
        if (empty($this->servicePerimetre)) {
            $this->servicePerimetre = \Application::$container->get('ApplicationPerimetre');
        }

        return $this->servicePerimetre;
    }
}