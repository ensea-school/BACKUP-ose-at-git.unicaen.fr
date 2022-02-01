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
    protected ?RechercheForm $formServiceRecherche;



    /**
     * @param RechercheForm|null $formServiceRecherche
     *
     * @return self
     */
    public function setFormServiceRecherche( ?RechercheForm $formServiceRecherche )
    {
        $this->formServiceRecherche = $formServiceRecherche;

        return $this;
    }



    public function getFormServiceRecherche(): ?RechercheForm
    {
        if (!$this->formServiceRecherche){
            $this->formServiceRecherche = \Application::$container->get('FormElementManager')->get(RechercheForm::class);
        }

        return $this->formServiceRecherche;
    }
}