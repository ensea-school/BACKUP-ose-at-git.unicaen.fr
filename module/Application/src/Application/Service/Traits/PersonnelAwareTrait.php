<?php

namespace Application\Service\Traits;

use Application\Service\Personnel;

/**
 * Description of PersonnelAwareTrait
 *
 * @author UnicaenCode
 */
trait PersonnelAwareTrait
{
    /**
     * @var Personnel
     */
    private $servicePersonnel;



    /**
     * @param Personnel $servicePersonnel
     *
     * @return self
     */
    public function setServicePersonnel(Personnel $servicePersonnel)
    {
        $this->servicePersonnel = $servicePersonnel;

        return $this;
    }



    /**
     * @return Personnel
     */
    public function getServicePersonnel()
    {
        if (empty($this->servicePersonnel)) {
            $this->servicePersonnel = \Application::$container->get('ApplicationPersonnel');
        }

        return $this->servicePersonnel;
    }
}