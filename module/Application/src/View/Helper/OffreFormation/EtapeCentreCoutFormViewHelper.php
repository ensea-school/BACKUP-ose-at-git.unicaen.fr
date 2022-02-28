<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm;
use Laminas\View\Helper\AbstractHtmlElement;

/**
 * Dessine le formulaire de type EtapeCentreCoutFormViewHelper.
 *
 * @see    EtapeCentreCoutSaisieForm
 */
class EtapeCentreCoutFormViewHelper extends AbstractHtmlElement
{

    /**
     * @var EtapeCentreCoutForm
     */
    protected $form;



    /**
     *
     * @param EtapeCentreCoutForm $form
     *
     * @return self|string
     */
    public function __invoke(EtapeCentreCoutForm $form = null)
    {
        if (null === $form) {
            return $this;
        }

        return $this->render($form);
    }



    /**
     * Rendu du formulaire
     *
     * @param EtapeCentreCoutForm $form
     *
     * @return string
     */
    public function render(EtapeCentreCoutForm $form)
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
        $res .= '<table class="table table-bordered table-extra-condensed">';

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
            $res .= $this->getView()->formSelect($form->get($th->getCode()));
            $res .= ' <button type="button" class="btn btn-default btn-sm form-set-value pull-right" data-code="' . $th->getCode() . '" title="Appliquer à tous"><i class="fa-solid fa-arrow-down"></i></button>';
            $res .= '</th>';
        }
        $res .= '</tr>';

        foreach ($elements as $element) {
            $res         .= '<tr>';
            $res         .= '<th class="element-pedagogique">' . $element . '</th>';
            $formElement = $form->get('EL' . $element->getId());
            $res         .= $this->getView()->elementCentreCoutFieldset()->render($formElement, $typesHeures, true);
            $res         .= '</tr>';
        }

        $res .= '</table>';

        $res .= $this->getView()->formHidden($form->get('id'));
        $res .= $this->getView()->formRow($form->get('submit'));
        $res .= $this->getView()->form()->closeTag();

        return $res;
    }
}