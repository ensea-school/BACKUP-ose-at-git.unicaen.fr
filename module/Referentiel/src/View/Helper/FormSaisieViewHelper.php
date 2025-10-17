<?php

namespace Referentiel\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use Service\Entity\Db\TypeVolumeHoraire;
use Referentiel\Entity\VolumeHoraireReferentielListe;
use Referentiel\Form\SaisieFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\Util;
use Referentiel\Form\Saisie as SaisieForm;

class FormSaisieViewHelper extends AbstractHtmlElement
{
    use ContextServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;

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
    public function __invoke(?SaisieForm $form = null)
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


        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->get($this->form->get('type-volume-horaire')->getValue());
        $inRealise = TypeVolumeHoraire::CODE_REALISE === $typeVolumeHoraire->getCode();
        $rappelPrevu = null;
        $buttonMarkup = null;

        if ($inRealise) {
            /**
             * Rappel du nombre d'heures prévues
             */
            $vhl = $this->form->get('service')->getObject()->getVolumeHoraireReferentielListe()->getChild();
            /* @var $vhl VolumeHoraireReferentielListe */
            $vhl->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
            $vhl->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getValide());
            $heures = $vhl->getHeures();

            $template = <<<EOS
<div class="float-end" style="opacity: 0.5">
    <strong>Prévu :</strong> <span id="rappel-heures-prevu" data-heures="%s">%s</span>
</div>
EOS;
            $rappelPrevu = sprintf($template,
                $heures,
                Util::formattedNumber($vhl->getHeures()));

            /**
             * Bouton Prévu->Réalisé
             */
            $button = new \Laminas\Form\Element\Button('referentiel-prevu-to-realise');
            $button
                ->setAttributes([
                    'class' => 'btn btn-secondary referentiel-prevu-to-realise',
                    'title' => "Initialise le formulaire avec les heures prévues",
                ])
                ->setLabel(str_replace('=>', '<i class="fas fa-arrow-right"></i>', $this->getView()->translate('Prévu => réalisé')))
                ->setLabelOption('disable_html_escape', true);
            $buttonMarkup = $this->getView()->formControlGroup($button);
        }

        $template = <<<EOS
<div class="row">
    <div class="col-md-7">
        %s
    </div>
    <div class="col-md-5">
        %s
    </div>
</div>
<div class="row">
    <div class="col-md-4" id="volumes-horaires">
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
            $this->getView()->formControlGroup($fservice->get('fonction')),
            $this->getView()->formControlGroup($fservice->get('structure')),
            $this->getView()->formControlGroup($fservice->get('heures'))
        );

        $template = <<<EOS
<div class="row">
<div class="col-md-6">
    %s
</div>
<div class="col-md-6">
    %s
</div>
</div>
EOS;
        $part .= sprintf(
            $template,
            $this->getView()->formControlGroup($fservice->get('tag')),
            $this->getView()->formControlGroup($fservice->get('motif-non-paiement')));


        $template = <<<EOS
<div>
    %s
</div>
EOS;
        $part .= sprintf(
            $template,
            $this->getView()->formControlGroup($fservice->get('formation')));

        $part .= sprintf(
            $template,
            $this->getView()->formControlGroup($fservice->get('commentaires')));

        $part .= $this->getView()->formHidden($fservice->get('id'));
        $part .= $this->getView()->formHidden($fservice->get('idPrev'));
        $part .= '<br />';
        $part .= $this->getView()->formRow($this->form->get('submit'));
        $part .= $this->getView()->formHidden($this->form->get('type-volume-horaire'));
        $part .= $this->getView()->form()->closeTag() . '<br />';

        return $part;
    }

}