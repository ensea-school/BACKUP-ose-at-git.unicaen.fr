<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleResultatServiceReferentiel;

/**
 * Description of FormuleResultatServiceReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatServiceReferentielAwareInterface
{
    /**
     * @param FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel
     * @return self
     */
    public function setFormuleResultatServiceReferentiel( FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null );



    /**
     * @return FormuleResultatServiceReferentiel
     */
    public function getFormuleResultatServiceReferentiel();
}