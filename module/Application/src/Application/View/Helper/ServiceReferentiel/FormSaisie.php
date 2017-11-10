<?php

namespace Application\View\Helper\ServiceReferentiel;

use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\VolumeHoraireReferentielListe;
use Application\Form\ServiceReferentiel\SaisieFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\View\Helper\AbstractViewHelper;
use UnicaenApp\Util;
use Application\Form\ServiceReferentiel\Saisie as SaisieForm;

/**
 * Description of SaisieForm
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class FormSaisie extends AbstractViewHelper
{
    use ContextServiceAwareTrait;
    use ServiceAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;

    /**
     * @var SaisieForm
     */
    protected $form;



    /**
     *
     * @param SaisieForm $form
     *
     * @return self
     */
    public function __invoke(SaisieForm $form = null)
    {
        $this->form = $form;
        $this->form->setAttribute('id', 'referentiel');
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
            'referentiel/volumes-horaires-refresh',
            [
                'id' => $this->form->get('service')->get('id')->getValue(),
            ]);

        return $url;
    }



    /**
     * Rendu du formulaire
     *
     * @param Saisie $form
     *
     * @return string
     */
    public function render()
    {
        $fservice = $this->form->get('service');
        /* @var $fservice SaisieFieldset */

        $part = $this->getView()->form()->openTag($this->form);

        if (!$this->getServiceContext()->getSelectedIdentityRole()->getIntervenant()) {
            $template = <<<EOS
<div>
    %s
</div>
EOS;
            $part .= sprintf(
                $template,
                $this->getView()->formControlGroup($fservice->get('intervenant')));
        }

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->get($this->form->get('type-volume-horaire')->getValue());
        $inRealise         = TypeVolumeHoraire::CODE_REALISE === $typeVolumeHoraire->getCode();
        $rappelPrevu       = null;
        $buttonMarkup      = null;

        if ($inRealise) {
            /**
             * Rappel du nombre d'heures prévues
             */
            $vhl = $this->form->get('service')->getObject()->getVolumeHoraireReferentielListe()->getChild();
            /* @var $vhl VolumeHoraireReferentielListe */
            $vhl->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
            $vhl->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getValide());
            $heures = $vhl->getHeures();

            $template    = <<<EOS
<div class="pull-right" style="opacity: 0.5">
    <strong>Prévu :</strong> <span id="rappel-heures-prevu" data-heures="%s">%s</span>
</div>
EOS;
            $rappelPrevu = sprintf($template,
                $heures,
                Util::formattedNumber($vhl->getHeures()));

            /**
             * Bouton Prévu->Réalisé
             */
            $button = new \Zend\Form\Element\Button('referentiel-prevu-to-realise');
            $button
                ->setAttributes([
                    'class' => 'btn btn-default referentiel-prevu-to-realise',
                    'title' => "Initialise le formulaire avec les heures prévues",
                ])
                ->setLabel('Prévu <span class="glyphicon glyphicon-arrow-right"></span> réalisé')
                ->setLabelOption('disable_html_escape', true);
            $buttonMarkup = $this->getView()->formControlGroup($button);
        }

        $template = <<<EOS
<div class="row">
    <div class="col-md-5">
        %s
    </div>
    <div class="col-md-7">
        %s
    </div>
</div>
<div class="row">
    <div class="col-md-4" id="volumes-horaires" data-url="%s">
        $rappelPrevu
        %s
    </div>
    <div class="col-md-4">
        <br />
        $buttonMarkup
    </div>
</div>
EOS;
        $part .= sprintf(
            $template,
            $this->getView()->formControlGroup($fservice->get('structure')),
            $this->getView()->formControlGroup($fservice->get('fonction')),
            $this->getVolumesHorairesRefreshUrl(),
            $this->getView()->formControlGroup($fservice->get('heures'))
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
        $part .= $this->getView()->form()->closeTag() . '<br />';

        $this->includeJavascript($part);

        return $part;
    }



    /**
     * @var bool
     */
    protected static $inlineJsAppended = false;



    /**
     *
     * @param string $html
     *
     * @return self
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
        } else {
            $this->getView()->inlineScript()->offsetSetScript(100, $js);
        }

        return $this;
    }



    /**
     *
     * @return string
     */
    protected function getJavascript()
    {
        $formId = $this->form->getAttribute('id');

        // collecte des structures associées aux fonctions
        $fonctions          = $this->form->get('service')->getFonctions();
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
                
    // si une structure est associée à la fonction sélectionnée
    if (structuresFonction[fonctionVal]) {
        structureSelect.val(structuresFonction[fonctionVal]);
        $('option:not(:selected)', structureSelect).attr('disabled', true);
    }
} 
EOS;

        return $js;
    }
}