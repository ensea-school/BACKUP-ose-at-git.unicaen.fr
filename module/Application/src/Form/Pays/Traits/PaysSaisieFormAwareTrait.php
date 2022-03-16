<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Form\Pays\Traits;

use Application\Form\Pays\PaysSaisieForm;

/**
 * Description of GradeSaisieFormAwareTrait
 */
trait PaysSaisieFormAwareTrait
{
    /**
     * @var PaysSaisieForm
     */
    private $paysSaisie;



    /**
     * @param PaysSaisieForm $paysSaisie
     *
     * @return self
     */
    public function setFormGroupeTypeFormationSaisie(PaysSaisieForm $paysSaisie)
    {
        $this->formPaysSaisie = $paysSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return PaysSaisieForm
     */
    public function getFormPaysSaisie(): PaysSaisieForm
    {
        if (!empty($this->formPaysSaisie)) {
            return $this->formPaysSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(PaysSaisieForm::class);
    }
}