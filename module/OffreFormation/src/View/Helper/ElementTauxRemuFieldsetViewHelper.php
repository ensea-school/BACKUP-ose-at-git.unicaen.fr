<?php

namespace OffreFormation\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Form\EtapeTauxRemu\ElementTauxRemuFieldset;
use Paiement\Entity\Db\TauxRemu;

/**
 * Dessine un fieldset de type ElementTauxRemusFieldsetViewHelper.
 *
 * @see    ElementTauxRemusFieldset
 */
class ElementTauxRemuFieldsetViewHelper extends AbstractHtmlElement
{

    /**
     *
     * @param ElementTauxRemuFieldset $fieldset
     *
     * @return self|string
     */
    public function __invoke(ElementTauxRemuFieldset $fieldset = null)
    {
        if (null === $fieldset) {
            return $this;
        }

        return $this->render($fieldset);
    }



    /**
     * Rendu du formulaire.
     *
     * @param ElementTauxRemuFieldset $fieldset
     * @param boolean                 $inTable
     *
     * @return string
     */
    public function render(ElementTauxRemuFieldset $fieldset, $inTable = false)
    {
        $res = '';

        $vh = $this->getView()->formControlGroup();
        if ($inTable) {
            $vh->setIncludeLabel(false);
            $res .= '<td>';
        }
        $taux = $fieldset->getTauxRemu();
        if($taux){
            $res .= $vh->render($taux);
        }

        if ($inTable) {
            $res .= '</td>';
        }

        return $res;
    }
}
