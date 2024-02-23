<?php

namespace Formule\Service;


/**
 * Description of CreateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CreateurServiceAwareTrait
{
    protected ?CreateurService $serviceCreateur = null;



    /**
     * @param CreateurService $serviceCreateur
     *
     * @return self
     */
    public function setServiceCreateur(?CreateurService $serviceCreateur)
    {
        $this->serviceCreateur = $serviceCreateur;

        return $this;
    }



    public function getServiceCreateur(): ?CreateurService
    {
        if (empty($this->serviceCreateur)) {
            $this->serviceCreateur = \OseAdmin::instance()->container()->get(CreateurService::class);
        }

        return $this->serviceCreateur;
    }
}