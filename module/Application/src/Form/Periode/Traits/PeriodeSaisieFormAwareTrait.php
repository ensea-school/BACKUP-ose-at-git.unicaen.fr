<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Form\Periode\Traits;

use Application\Form\Periode\PeriodeSaisieForm;

/**
 * Description of GradeSaisieFormAwareTrait
 */
trait PeriodeSaisieFormAwareTrait
{
    /**
     * @var PeriodeSaisieForm
     */
    private $formPeriodeSaisie;



    /**
     * @param PeriodeSaisieForm $formPeriodeSaisie
     *
     * @return self
     */
    public function setFormPeriodeSaisie(PeriodeSaisieForm $formPeriodeSaisie)
    {
        $this->formPeriodeSaisie = $formPeriodeSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return PeriodeSaisieForm
     */
    public function getFormPeriodeSaisie()
    {
        if (!empty($this->formPeriodeSaisie)) {
            return $this->formPeriodeSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(PeriodeSaisieForm::class);
    }
}