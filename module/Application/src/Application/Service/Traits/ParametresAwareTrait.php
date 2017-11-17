<?php

namespace Application\Service\Traits;

use Application\Service\Parametres;

/**
 * Description of ParametresAwareTrait
 *
 * @author UnicaenCode
 */
trait ParametresAwareTrait
{
    /**
     * @var Parametres
     */
    private $serviceParametres;



    /**
     * @param Parametres $serviceParametres
     *
     * @return self
     */
    public function setServiceParametres(Parametres $serviceParametres)
    {
        $this->serviceParametres = $serviceParametres;

        return $this;
    }



    /**
     * @return Parametres
     */
    public function getServiceParametres()
    {
        if (empty($this->serviceParametres)) {
            $this->serviceParametres = \Application::$container->get('ApplicationParametres');
        }

        return $this->serviceParametres;
    }
}