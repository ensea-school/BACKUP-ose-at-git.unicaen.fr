<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutSaisieFieldset;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Dessine un fieldset de type ElementCentreCoutSaisieFieldset.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see ElementCentreCoutSaisieFieldset
 */
class FieldsetElementCentreCoutSaisieHelper extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     *
     * @param ElementCentreCoutSaisieFieldset $fieldset
     * @return self|string
     */
    public function __invoke(ElementCentreCoutSaisieFieldset $fieldset = null)
    {
        if (null === $fieldset) {
            return $this;
        }

        return $this->render($fieldset);
    }

    /**
     * Rendu du formulaire.
     *
     * @param ElementCentreCoutSaisieFieldset $fieldset
     * @param array $typesHeures Tous les types d'heures possibles
     * @param boolean $inTable
     * @return string
     */
    public function render(ElementCentreCoutSaisieFieldset $fieldset, array $typesHeures, $inTable = false)
    {
        $element = $fieldset->getElementPedagogique();
        $res     = '';
        
        $elementTypesHeures = $element->getTypeHeures();
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
            }
            else {
                $res .= '<td>&nbsp;</td>';
            }
        }
        
        return $res;
    }
}