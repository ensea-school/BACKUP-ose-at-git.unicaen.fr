<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\EtapeModulateursSaisie;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of EtapeModulateursSaisieForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeModulateursSaisieForm extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * @var Saisie
     */
    protected $form;


    /**
     *
     * @param EtapeModulateursSaisie $form
     * @return self|string
     */
    public function __invoke(EtapeModulateursSaisie $form = null)
    {
        if (null === $form) {
            return $this;
        }

        return $this->render($form);
    }

    /**
     * Rendu du formulaire
     *
     * @param Saisie $form
     * @return string
     */
    public function render(EtapeModulateursSaisie $form)
    {
        $elements = $form->getEtape()->getElementPedagogique();
        $typesModulateurs = $form->getTypesModulateurs();
        $res = '<table class="table">';
        $res .= '<tr>';
        foreach( $typesModulateurs as $typeModulateur ){
            $res .= '<th>';
            $res .= $typeModulateur->getLibelle();
            $res .= '</th>';
        }
        $res .= '</tr>';
        foreach( $elements as $element ){
            $res .= '<tr>';
            $formElement = $form->get('EL'.$element->getId());
            $res .= $this->getView()->elementModulateursSaisieFieldset()->render( $formElement, $typesModulateurs );
            $res .= '</tr>';
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
        $res .= '</table>';
        return $res;
    }
}