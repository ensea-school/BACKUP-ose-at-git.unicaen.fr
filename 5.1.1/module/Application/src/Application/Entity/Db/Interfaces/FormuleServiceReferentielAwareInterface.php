<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleServiceReferentiel;

/**
 * Description of FormuleServiceReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleServiceReferentielAwareInterface
{
    /**
     * @param FormuleServiceReferentiel $formuleServiceReferentiel
     * @return self
     */
    public function setFormuleServiceReferentiel( FormuleServiceReferentiel $formuleServiceReferentiel = null );



    /**
     * @return FormuleServiceReferentiel
     */
    public function getFormuleServiceReferentiel();
}