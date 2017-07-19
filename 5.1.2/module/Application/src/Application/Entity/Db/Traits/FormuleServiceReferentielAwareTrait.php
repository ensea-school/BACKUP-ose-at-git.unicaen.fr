<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleServiceReferentiel;

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
    private $formuleServiceReferentiel;





    /**
     * @param FormuleServiceReferentiel $formuleServiceReferentiel
     * @return self
     */
    public function setFormuleServiceReferentiel( FormuleServiceReferentiel $formuleServiceReferentiel = null )
    {
        $this->formuleServiceReferentiel = $formuleServiceReferentiel;
        return $this;
    }



    /**
     * @return FormuleServiceReferentiel
     */
    public function getFormuleServiceReferentiel()
    {
        return $this->formuleServiceReferentiel;
    }
}