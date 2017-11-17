<?php

namespace Application\Service\Traits;

use Application\Service\Etablissement;

/**
 * Description of EtablissementAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementAwareTrait
{
    /**
     * @var Etablissement
     */
    private $serviceEtablissement;



    /**
     * @param Etablissement $serviceEtablissement
     *
     * @return self
     */
    public function setServiceEtablissement(Etablissement $serviceEtablissement)
    {
        $this->serviceEtablissement = $serviceEtablissement;

        return $this;
    }



    /**
     * @return Etablissement
     */
    public function getServiceEtablissement()
    {
        if (empty($this->serviceEtablissement)) {
            $this->serviceEtablissement = \Application::$container->get('ApplicationEtablissement');
        }

        return $this->serviceEtablissement;
    }
}