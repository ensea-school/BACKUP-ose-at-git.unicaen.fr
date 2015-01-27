<?php

namespace Application\View\Helper\Service;

use Application\Form\Service\Saisie;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of SaisieForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieForm extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * @var Saisie
     */
    protected $form;

    /**
     *
     * @return \Application\Service\Periode[]
     */
    public function getPeriodes()
    {
        $service = $this->form->get('service')->getObject(); /* @var $service \Application\Entity\Db\Service */
        if ($service->getElementPedagogique() && $service->getElementPedagogique()->getPeriode()){
            return array( $service->getElementPedagogique()->getPeriode() );
        }
        return $this->getServicePeriode()->getEnseignement();
    }

    /**
     *
     * @param Saisie $form
     * @return SaisieForm|string
     */
    public function __invoke(Saisie $form = null)
    {
        $this->form = $form;
        $this->form->prepare();
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function getVolumesHorairesRefreshUrl()
    {
        $url = $this->getView()->url(
                'service/default',
                [
                    'action' => 'volumes-horaires-refresh',
                    'id' => $this->form->get('service')->get('id')->getValue()
                ]);
        return $url;
    }

    /**
     * Rendu du formulaire
     *
     * @param Saisie $form
     * @return string
     */
    public function render()
    {
        $fservice = $this->form->get('service');

        $res = $this->getView()->form()->openTag($this->form);
        if (! $this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $res .= $this->getView()->formControlGroup($fservice->get('intervenant'));
        }
        if ($fservice->has('interne-externe')){
            $interne = $fservice->get('interne-externe')->getValue() == 'service-interne';
            $res .= $this->getView()->formControlGroup($fservice->get('interne-externe'), 'formButtonGroup');
            $res .= '<div id="element-interne" '.(($interne) ? '' : 'style="display:none"').'>'.$this->getView()->fieldsetElementPedagogiqueRecherche($fservice->get('element-pedagogique')).'</div>';
            $res .= '<div id="element-externe" '.(($interne) ? 'style="display:none"' : '').'>'.$this->getView()->formControlGroup($fservice->get('etablissement')).'</div>';
        }else{
            $res .= '<div id="element-interne">'.$this->getView()->fieldsetElementPedagogiqueRecherche($fservice->get('element-pedagogique'))->render().'</div>';
        }
        $res .= '<div id="volumes-horaires" data-url="'.$this->getVolumesHorairesRefreshUrl().'">';
        $res .= $this->renderVolumesHoraires();
        $res .= '</div>';
        $res .= '<br />';
        $res .= $this->getView()->formRow($this->form->get('submit'));
        $res .= $this->getView()->formHidden($this->form->get('type-volume-horaire'));
        $res .= $this->getView()->formHidden($fservice->get('id'));
        $res .= $this->getView()->form()->closeTag().'<br />';
        $res .= '<script type="text/javascript">';
        $res .= '$(function() { ServiceForm.init(); });';
        $res .= '</script>';
        return $res;
    }

    public function renderVolumesHoraires()
    {
        $res = '';
        foreach( $this->getPeriodes() as $periode ){
            $res .= '<div class="periode" id="'.$periode->getCode().'">';
            $res .= '<h3>'.$periode.'</h3>';
            $res .= $this->renderVolumeHoraire( $this->form->get($periode->getCode()) );
            $res .= '</div>';
        }
        return $res;
    }

    public function renderVolumeHoraire($fieldset)
    {
        $element = $fieldset->getObject()->getService()->getElementPedagogique();
        if ($element){
            $typesIntervention = $element->getTypeIntervention();
        }else{
            $typesIntervention = $this->getServiceTypeIntervention()->getTypesIntervention();
        }

        $res  = $this->getView()->formHidden($fieldset->get('service'));
        $res .= $this->getView()->formHidden($fieldset->get('periode'));
        $res .= $this->getView()->formHidden($fieldset->get('type-volume-horaire'));

        $res .= '<div class="volume-horaire-saisie-multiple">';
        foreach( $typesIntervention as $typeIntervention ){
            $element = $fieldset->get($typeIntervention->getCode());
            $element->setAttribute('class', 'form-control')
                    ->setLabelAttributes(array('class' => 'control-label'));
            $res .= '<div style="">';
            $res .= $this->getView()->formLabel( $element );
            $res .= '<br />';
            $res .= $this->getView()->formNumber( $element);
            $res .= '</div>';
        }
        $res .= '</div><div class="volume-horaire-saisie-multiple-fin"></div>';
        return $res;
    }

    /**
     * @return \Application\Service\Service
     */
    protected function getServiceService()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationService');
    }

    /**
     * @return \Application\Service\Periode
     */
    protected function getServicePeriode()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationPeriode');
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
    }
}