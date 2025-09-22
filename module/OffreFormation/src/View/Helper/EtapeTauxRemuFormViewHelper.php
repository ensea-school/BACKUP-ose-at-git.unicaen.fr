<?php

namespace OffreFormation\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Form\EtapeTauxRemu\EtapeTauxRemuForm;

/**
 * Dessine le formulaire de type EtapetauxRemuFormViewHelper.
 *
 * @see    EtapetauxRemuSaisieForm
 */
class EtapeTauxRemuFormViewHelper extends AbstractHtmlElement
{

    /**
     * @var EtapetauxRemuForm
     */
    protected $form;



    /**
     *
     * @param EtapetauxRemuForm $form
     *
     * @return self|string
     */
    public function __invoke(?EtapetauxRemuForm $form = null)
    {
        if (null === $form) {
            return $this;
        }

        return $this->render($form);
    }



    /**
     * Rendu du formulaire
     *
     * @param EtapetauxRemuForm $form
     *
     * @return string
     */
    public function render(EtapetauxRemuForm $form)
    {

        $elements = $form->getEtape()->getElementPedagogique();

        if (empty($elements)) {
            return 'Aucun enseignement n\'est associé à cette formation. Il est donc impossible de paramétrer les centres de coûts.';
        }


        $form->prepare();
        $res = $this->getView()->form()->openTag($form);
        $res .= '<table class="table table-bordered table-xs">';

        $res .= '<tr>';
        $res .= '<th rowspan="2" class="element-pedagogique">Elément</th>';

        $res .= '</tr>';

        $res .= '<tr>';
        $res .= '<th>';
        $res .= $this->getView()->formSelect($form->get('tauxRemu'));
        $res .= ' <button type="button" class="btn btn-secondary btn-sm form-set-value float-end" data-code="" title="Appliquer à tous"><i class="fas fa-arrow-down"></i></button>';
        $res .= '</th>';
        $res .= '</tr>';

        foreach ($elements as $element) {
            $res         .= '<tr>';
            $res         .= '<th class="element-pedagogique">' . $element . '</th>';
            $formElement = $form->get('EL' . $element->getId());
            $res         .= $this->getView()->elementTauxRemuFieldset()->render($formElement, true);
            $res         .= '</tr>';
        }

        $res .= '</table>';

        $res .= $this->getView()->formHidden($form->get('id'));
        $res .= $this->getView()->formRow($form->get('submit'));
        $res .= $this->getView()->form()->closeTag();

        return $res;
    }
}