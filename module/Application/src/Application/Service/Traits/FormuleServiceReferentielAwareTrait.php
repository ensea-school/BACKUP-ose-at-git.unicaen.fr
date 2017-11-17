<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceReferentiel;

/**
 * Description of FormuleServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceReferentielAwareTrait
{
    /**
     * @var FormuleServiceReferentiel
     */
    private $serviceFormuleServiceReferentiel;



    /**
     * @param FormuleServiceReferentiel $serviceFormuleServiceReferentiel
     *
     * @return self
     */
    public function setServiceFormuleServiceReferentiel(FormuleServiceReferentiel $serviceFormuleServiceReferentiel)
    {
        $this->serviceFormuleServiceReferentiel = $serviceFormuleServiceReferentiel;

        return $this;
    }



    /**
     * @return FormuleServiceReferentiel
     */
    public function getServiceFormuleServiceReferentiel()
    {
        if (empty($this->serviceFormuleServiceReferentiel)) {
            $this->serviceFormuleServiceReferentiel = \Application::$container->get('ApplicationFormuleServiceReferentiel');
        }

        return $this->serviceFormuleServiceReferentiel;
    }
}