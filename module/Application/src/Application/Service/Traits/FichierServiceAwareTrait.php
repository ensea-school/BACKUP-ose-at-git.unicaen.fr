<?php

namespace Application\Service\Traits;

use Application\Service\FichierService;

/**
 * Description of FichierServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FichierServiceAwareTrait
{
    /**
     * @var FichierService
     */
    private $serviceFichier;



    /**
     * @param FichierService $serviceFichier
     *
     * @return self
     */
    public function setServiceFichier(FichierService $serviceFichier)
    {
        $this->serviceFichier = $serviceFichier;

        return $this;
    }



    /**
     * @return FichierService
     */
    public function getServiceFichier()
    {
        if (empty($this->serviceFichier)) {
            $this->serviceFichier = \Application::$container->get('applicationFichier');
        }

        return $this->serviceFichier;
    }
}