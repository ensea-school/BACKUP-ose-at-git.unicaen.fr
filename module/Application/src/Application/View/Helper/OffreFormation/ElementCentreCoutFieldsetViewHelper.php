<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset;
use Zend\View\Helper\AbstractHtmlElement;

/**
 * Dessine un fieldset de type ElementCentreCoutFieldsetViewHelper.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see    ElementCentreCoutFieldset
 */
class ElementCentreCoutFieldsetViewHelper extends AbstractHtmlElement
{

    /**
     *
     * @param ElementCentreCoutFieldset $fieldset
     *
     * @return self|string
     */
    public function __invoke(ElementCentreCoutFieldset $fieldset = null)
    {
        if (null === $fieldset) {
            return $this;
        }

        return $this->render($fieldset);
    }



    /**
     * Rendu du formulaire.
     *
     * @param ElementCentreCoutFieldset $fieldset
     * @param array                     $typesHeures Tous les types d'heures possibles
     * @param boolean                   $inTable
     *
     * @return string
     */
    public function render(ElementCentreCoutFieldset $fieldset, array $typesHeures, $inTable = false)
    {
        $res = '';

        $elementTypesHeures = $fieldset->getTypesHeures();
        foreach ($typesHeures as $th) {
            if ($elementTypesHeures->contains($th)) {
                $vh = $this->getView()->formControlGroup();
                if ($inTable) {
                    $vh->setIncludeLabel(false);
                    $res .= '<td>';
                }
                $res .= $vh->render($fieldset->get($th->getCode()));
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