<?php

namespace Formule\Service;


/**
 * Description of TraducteurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TraducteurServiceAwareTrait
{
    protected ?TraducteurService $serviceTraducteur = null;



    /**
     * @param TraducteurService $serviceTraducteur
     *
     * @return self
     */
    public function setServiceTraducteur(?TraducteurService $serviceTraducteur)
    {
        $this->serviceTraducteur = $serviceTraducteur;

        return $this;
    }



    public function getServiceTraducteur(): ?TraducteurService
    {
        if (empty($this->serviceTraducteur)) {
            $this->serviceTraducteur = \Framework\Application\Application::getInstance()->container()->get(TraducteurService::class);
        }

        return $this->serviceTraducteur;
    }
}