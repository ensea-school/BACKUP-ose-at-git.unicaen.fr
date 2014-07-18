<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\ElementModulateursFieldset;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of ElementModulateursSaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursSaisieFieldset extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

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

    /**
     * @return \Application\Service\TypeModulateur
     */
    protected function getServiceTypeModulateur()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeModulateur');
    }
}