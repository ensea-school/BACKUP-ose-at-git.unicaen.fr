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
    public function render(ElementModulateursFieldset $fieldset, array $typesModulateurs)
    {
        $element = $fieldset->getElementPedagogique();
        $stm = $this->getServiceTypeModulateur();

        $res = '';
        $elementTypesModulateurs = $stm->getList( $stm->finderByElementPedagogique($element) );
        foreach( $typesModulateurs as $typeModulateur ){
            if (isset($elementTypesModulateurs[$typeModulateur->getId()])){
                $res .= '<td>'.$typeModulateur->getCode().'</td>';
            }
        }

        /*$fservice = $form->get('service');

        $interne = $fservice->get('interne-externe')->getValue() == 'service-interne';

        $form->prepare();

        $res = $this->getView()->form()->openTag($form);
        if (! $this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $res .= $this->getView()->formControlGroup($fservice->get('intervenant'));
        }
        $res .= $this->getView()->formControlGroup($fservice->get('interne-externe'), 'formButtonGroup');
        $res .= '<div id="element-interne" '.(($interne) ? '' : 'style="display:none"').'>'.$this->getView()->fieldsetElementPedagogiqueRecherche($fservice->get('element-pedagogique')).'</div>';
        $res .= '<div id="element-externe" '.(($interne) ? 'style="display:none"' : '').'>'.$this->getView()->formControlGroup($fservice->get('etablissement')).'</div>';
        foreach( $this->getPeriodes() as $periode ){
            $res .= $this->getView()->volumeHoraireSaisieMultipleFieldset(
                                            $form->get($periode->getCode()),
                                            $this->getServiceLocator()->getServiceLocator()->get('applicationService')->getPeriode($fservice->getObject())
                    );
        }
        $res .= '<br />';
        $res .= $this->getView()->formRow($form->get('submit'));
        $res .= $this->getView()->formHidden($fservice->get('id'));
        $res .= $this->getView()->form()->closeTag().'<br />';*/
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