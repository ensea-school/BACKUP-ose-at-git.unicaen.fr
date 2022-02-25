<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Form\Etablissement\Traits;

use Application\Form\Etablissement\EtablissementSaisieForm;

/**
 * Description of GradeSaisieFormAwareTrait
 */
trait EtablissementSaisieFormAwareTrait
{
    /**
     * @var EtablissementSaisieForm
     */
    private $formEtablissementSaisie;



    /**
     * @param EtablissementSaisieForm $formEtablissementSaisie
     *
     * @return self
     */
    public function setFormEtablissementSaisie(EtablissementSaisieForm $formEtablissementSaisie)
    {
        $this->formEtablissementSaisie = $formEtablissementSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EtablissementSaisieForm
     */
    public function getFormEtablissementSaisie()
    {
        if (!empty($this->formEtablissementSaisie)) {
            return $this->formEtablissementSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(EtablissementSaisieForm::class);
    }
}