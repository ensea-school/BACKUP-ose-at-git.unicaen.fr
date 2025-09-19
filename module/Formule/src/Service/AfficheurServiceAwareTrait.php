<?php

namespace Formule\Service;


/**
 * Description of AfficheurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AfficheurServiceAwareTrait
{
    protected ?AfficheurService $serviceAfficheur = null;



    /**
     * @param AfficheurService $serviceAfficheur
     *
     * @return self
     */
    public function setServiceAfficheur(?AfficheurService $serviceAfficheur)
    {
        $this->serviceAfficheur = $serviceAfficheur;

        return $this;
    }



    public function getServiceAfficheur(): ?AfficheurService
    {
        if (empty($this->serviceAfficheur)) {
            $this->serviceAfficheur = \Framework\Application\Application::getInstance()->container()->get(AfficheurService::class);
        }

        return $this->serviceAfficheur;
    }
}