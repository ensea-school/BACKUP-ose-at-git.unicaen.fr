<?php

namespace OffreFormation\View\Helper;

use Application\View\Helper\AbstractViewHelper;
use OffreFormation\Form\ElementModulateursFieldset;
use Application\Service\Traits\TypeModulateurServiceAwareTrait;


/**
 * Description of ElementModulateursSaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursSaisieFieldset extends AbstractViewHelper
{
    use TypeModulateurServiceAwareTrait;

    /**
     * @var Saisie
     */
    protected $form;


    /**
     *
     * @param ElementModulateursFieldset $fieldset
     * @return self|string
     */
    public function __invoke(ElementModulateursFieldset $fieldset = null)
    {
        if (null === $fieldset) {
            return $this;
        }

        return $this->render($fieldset);
    }

    /**
     * Rendu du formulaire
     *
     * @param ElementModulateursFieldset $fieldset
     * @return string
     */
    public function render(ElementModulateursFieldset $fieldset, array $typesModulateurs, $inTable=false)
    {
        $element = $fieldset->getElementPedagogique();
        $stm = $this->getServiceTypeModulateur();

        $res = '';
        $elementTypesModulateurs = $stm->getList( $stm->finderByElementPedagogique($element) );
        foreach( $typesModulateurs as $typeModulateur ){
            if (isset($elementTypesModulateurs[$typeModulateur->getId()])){
                $vh = $this->getView()->formControlGroup();
                if ($inTable){
                    $vh->setIncludeLabel(false);
                    $res .= '<td>';
                }
                $res .= $vh->render( $fieldset->get($typeModulateur->getCode()) );
                if ($inTable){
                    $res .= '</td>';
                }
            }else{
                $res .= '<td>&nbsp;</td>';
            }
        }
        return $res;
    }
}