<?php

namespace OffreFormation\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Form\TauxMixite\TauxMixiteForm;

/**
 * Dessine le formulaire de type EtapeTauxMixiteFormViewHelper.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 * @see    TauxMixiteForm
 */
class EtapeTauxMixiteFormViewHelper extends AbstractHtmlElement
{

    /**
     * @var TauxMixiteForm
     */
    protected $form;



    /**
     *
     * @param TauxMixiteForm $form
     *
     * @return self|string
     */
    public function __invoke(?TauxMixiteForm $form = null)
    {
        if (null === $form) {
            return $this;
        }

        return $this->render($form);
    }



    /**
     * Rendu du formulaire
     *
     * @param TauxMixiteForm $form
     *
     * @return string
     */
    public function render(TauxMixiteForm $form)
    {
        $elements    = $form->getEtape()->getElementPedagogique();
        $typesHeures = $form->getTypesHeures();

        if (empty($elements)) {
            return 'Aucun enseignement n\'est associé à cette formation. Il est donc impossible de paramétrer les centres de coûts.';
        }
        if (empty($typesHeures)) {
            return "Aucun des éléments de cette formation n'est associé au moindre type d'heures éligible.";
        }

        $form->prepare();
        $res = $this->getView()->form()->openTag($form);
        $res .= '<table class="table table-bordered table-xs">';

        $res .= '<tr>';
        $res .= '<th rowspan="2" class="element-pedagogique">Elément</th>';
        foreach ($typesHeures as $th) {
            $res .= '<th>';
            $res .= $th->getLibelleCourt();
            $res .= '</th>';
        }
        $res .= '</tr>';

        $res .= '<tr>';
        foreach ($typesHeures as $th) {
            $res .= '<th>';
            $res .= '<div class="input-group">';
            $res .= $this->getView()->formText($form->get($th->getCode()));
            $res .= '<span class="input-group-btn">';
            $res .= '<button type="button" class="btn btn-secondary form-set-value" data-code="' . $th->getCode() . '" title="Appliquer à tous"><i class="fas fa-arrow-down"></i></button>';
            $res .= '</span>';
            $res .= '</div>';
            $res .= '</th>';
        }
        $res .= '</tr>';

        foreach ($elements as $element) {
            $res         .= '<tr>';
            $res         .= '<th class="element-pedagogique">' . $element . '</th>';
            $formElement = $form->get('EL' . $element->getId());
            $res         .= $this->getView()->elementTauxMixiteFieldset()->render($formElement, $typesHeures, true);
            $res         .= '</tr>';
        }

        $res .= '</table>';

        $res .= $this->getView()->formHidden($form->get('id'));
        $res .= $this->getView()->formRow($form->get('submit'));
        $res .= $this->getView()->form()->closeTag();

        return $res;
    }
}