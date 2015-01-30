<?php

namespace Application\View\Helper\ServiceReferentiel;

use Application\Form\ServiceReferentiel\Saisie;
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
class FormSaisie extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * @var Saisie
     */
    protected $form;

    /**
     *
     * @param Saisie $form
     * @return SaisieForm|string
     */
    public function __invoke(Saisie $form = null)
    {
        $this->form = $form;
        $this->form->setAttribute('id', uniqid("form-saisie-"));
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
                'referentiel/default',
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
        $fservice = $this->form->get('service'); /* @var $fservice \Application\Form\ServiceReferentiel\SaisieFieldset */

        $part = $this->getView()->form()->openTag($this->form);
        
        if (! $this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole) {
            $template = <<<EOS
<div>
    %s
</div>
EOS;
            $part .= sprintf(
                    $template, 
                    $this->getView()->formControlGroup($fservice->get('intervenant')));
        }
        
        $template = <<<EOS
<div class="row">
    <div class="col-md-4">
        %s
    </div>
    <div class="col-md-5">
        %s
    </div>
    <div class="col-md-3">
        <div id="volumes-horaires" data-url="%s">
            %s
        </div>
    </div>
</div>
EOS;
        $part .= sprintf(
                $template, 
                $this->getView()->formControlGroup($fservice->get('structure')), 
                $this->getView()->formControlGroup($fservice->get('fonction')),
                $this->getVolumesHorairesRefreshUrl(),
                $this->getView()->formControlGroup($fservice->get('heures'), 'formNumber')
        );
        
        $template = <<<EOS
<div>
    %s
</div>
EOS;
        $part .= sprintf(
                $template, 
                $this->getView()->formControlGroup($fservice->get('commentaires')));
            
        $part .= $this->getView()->formHidden($fservice->get('id'));
        $part .= '<br />';
        $part .= $this->getView()->formRow($this->form->get('submit'));
        $part .= $this->getView()->formHidden($this->form->get('type-volume-horaire'));
        $part .= $this->getView()->form()->closeTag().'<br />';
        
        $part .= '<script type="text/javascript">';
        $part .= '//$(function() { ServiceReferentielForm.init(); });';
        $part .= '</script>';
        
        $this->includeJavascript($part);
        
        return $part;
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
     * @var bool
     */
    protected static $inlineJsAppended = false;
    
    /**
     * 
     * @param string $html
     * @return \UnicaenApp\View\Helper\ToggleDetails
     */
    protected function includeJavascript(&$html)
    {
        $js = $this->getJavascript();
        
        $request          = $this->getView()->getHelperPluginManager()->getServiceLocator()->get('request');
        $isXmlHttpRequest = $request->isXmlHttpRequest();
        
        if ($isXmlHttpRequest) {
            // pour une requête AJAX on ne peut pas utilser le plugin "inlineScript"
            if (!static::$inlineJsAppended) {
                $html .= PHP_EOL . "<script>" . PHP_EOL . $js . PHP_EOL . "</script>";
                static::$inlineJsAppended = true;
            }
        }
        else {
            $this->getView()->inlineScript()->offsetSetScript(100, $js);
        }
        
        return $this;
    }
    
    protected function getJavascript()
    {
        $formId = $this->form->getAttribute('id');

        // collecte des structures associées aux fonctions
        $fonctions = $this->form->get('service')->getFonctions();
        $structuresFonction = [];
        foreach ($fonctions as $fonction) {
            $structuresFonction[$fonction->getId()] = (($s = $fonction->getStructure()) ? $s->getId() : 0);
        }
        $structuresFonction = json_encode($structuresFonction);
        
        $js = <<<EOS
var formId = "$formId";
var form   = $("#" + formId);
var structureSelectSel = "select.fonction-referentiel-structure"; 
var fonctionSelectSel  = "select.fonction-referentiel-fonction"; 
var structuresFonction = $structuresFonction;

$(function() {
    applyStructureFonction();
    //$(":input").tooltip();

    $('body')
        .on('change', "#" + formId + " " + fonctionSelectSel, function() {
            applyStructureFonction();
        });
});

/**
 * Si une structure est associée à la fonction sélectionnée, on la sélectionne
 * et interdit les autres structures.
 */
function applyStructureFonction()
{
    var fonctionVal     = $(fonctionSelectSel, form).val();
    var structureSelect = $(structureSelectSel, form);

    $('option', structureSelect).attr('disabled', false);
//console.log(structuresFonction[fonctionVal]);
    // si une structure est associée à la fonction sélectionnée
    if (structuresFonction[fonctionVal]) {
        structureSelect.val(structuresFonction[fonctionVal]);
        $('option:not(:selected)', structureSelect).attr('disabled', true);
    }
} 
EOS;
        return $js;
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