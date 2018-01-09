<?php

namespace Application\Service\Traits;

use Application\Service\PersonnelService;

/**
 * Description of PersonnelAwareTrait
 *
 * @author UnicaenCode
 */
trait PersonnelServiceAwareTrait
{
    /**
     * @var PersonnelService
     */
    private $servicePersonnel;



    /**
     * @param PersonnelService $servicePersonnel
     *
     * @return self
     */
    public function setServicePersonnel(PersonnelService $servicePersonnel)
    {
        $this->servicePersonnel = $servicePersonnel;

        return $this;
    }



    /**
     * @return PersonnelService
     */
    public function getServicePersonnel()
    {
        if (empty($this->servicePersonnel)) {
            $this->servicePersonnel = \Application::$container->get(PersonnelService::class);
        }

        return $this->servicePersonnel;
    }
}