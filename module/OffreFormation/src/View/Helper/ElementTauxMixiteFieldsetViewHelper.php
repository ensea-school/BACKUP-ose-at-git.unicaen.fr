<?php

namespace OffreFormation\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Form\TauxMixite\TauxMixiteFieldset;

/**
 * ElementTauxMixiteFieldsetViewHelper *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 * @see    ElementCentreCoutFieldset
 */
class ElementTauxMixiteFieldsetViewHelper extends AbstractHtmlElement
{

    /**
     *
     * @param TauxMixiteFieldset $fieldset
     *
     * @return self|string
     */
    public function __invoke(TauxMixiteFieldset $fieldset = null)
    {
        if (null === $fieldset) {
            return $this;
        }

        return $this->render($fieldset);
    }



    /**
     * Rendu du formulaire.
     *
     * @param TauxMixiteFieldset $fieldset
     * @param array              $typesHeures Tous les types d'heures possibles
     * @param boolean            $inTable
     *
     * @return string
     */
    public function render(TauxMixiteFieldset $fieldset, array $typesHeures, $inTable = false)
    {
        $res = '';

        $elementTypesHeures = $fieldset->getTypesHeures();
        foreach ($typesHeures as $th) {
            if ($elementTypesHeures->contains($th)) {
                if ($inTable) {
                    $res .= '<td>';
                }
                $res .= '<div class="input-group">';
                $res .= $this->getView()->formText($fieldset->get($th->getCode()));
                $res .= '<span class="input-group-addon">%</span>';
                $res .= '</div>';
                if ($inTable) {
                    $res .= '</td>';
                }
            } else {
                $res .= '<td>&nbsp;</td>';
            }
        }

        return $res;
    }
}