<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Form\Corps\Traits;

use Application\Form\Corps\CorpsSaisieForm;

/**
 * Description of CorpsSaisieFormAwareTrait
 */
trait CorpsSaisieFormAwareTrait
{
    /**
     * @var CorpsSaisieForm
     */
    private $corpsSaisie;



    /**
     * @param CorpsSaisieForm $formCorpsSaisie
     *
     * @return self
     */
    public function setFormCorpsSaisie(CorpsSaisieForm $formCorpsSaisie)
    {
        $this->formCorpsSaisie = $formCorpsSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return CorpsSaisieForm
     */
    public function getFormCorpsSaisie()
    {
        if (!empty($this->formCorpsSaisie)) {
            return $this->formCorpsSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(CorpsSaisieForm::class);
    }
}