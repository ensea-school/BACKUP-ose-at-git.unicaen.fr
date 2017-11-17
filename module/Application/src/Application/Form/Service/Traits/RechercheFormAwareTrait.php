<?php

namespace Application\Form\Service\Traits;

use Application\Form\Service\RechercheForm;

/**
 * Description of RechercheFormAwareTrait
 *
 * @author UnicaenCode
 */
trait RechercheFormAwareTrait
{
    /**
     * @var RechercheForm
     */
    private $formServiceRecherche;



    /**
     * @param RechercheForm $formServiceRecherche
     *
     * @return self
     */
    public function setFormServiceRecherche(RechercheForm $formServiceRecherche)
    {
        $this->formServiceRecherche = $formServiceRecherche;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return RechercheForm
     */
    public function getFormServiceRecherche()
    {
        if (!empty($this->formServiceRecherche)) {
            return $this->formServiceRecherche;
        }

        return \Application::$container->get('FormElementManager')->get('ServiceRechercheForm');
    }
}