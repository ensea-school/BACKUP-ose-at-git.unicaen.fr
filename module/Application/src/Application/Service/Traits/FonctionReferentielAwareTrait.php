<?php

namespace Application\Service\Traits;

use Application\Service\FonctionReferentiel;
use Common\Exception\RuntimeException;

trait FonctionReferentielAwareTrait
{
    /**
     * description
     *
     * @var FonctionReferentiel
     */
    private $serviceFonctionReferentiel;

    /**
     *
     * @param FonctionReferentiel $serviceFonctionReferentiel
     * @return self
     */
    public function setServiceFonctionReferentiel( FonctionReferentiel $serviceFonctionReferentiel )
    {
        $this->serviceFonctionReferentiel = $serviceFonctionReferentiel;
        return $this;
    }

    /**
     *
     * @return FonctionReferentiel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFonctionReferentiel()
    {
        if (empty($this->serviceFonctionReferentiel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFonctionReferentiel');
        }else{
            return $this->serviceFonctionReferentiel;
        }
    }

}