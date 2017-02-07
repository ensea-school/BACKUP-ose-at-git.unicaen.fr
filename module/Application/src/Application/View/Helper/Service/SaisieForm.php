<?php

namespace Application\View\Helper\Service;

use Application\Entity\Db\Periode;
use Application\Entity\Db\Service;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Form\Service\Saisie;
use Application\View\Helper\AbstractViewHelper;
use \Application\Service\Traits\ContextAwareTrait;
use \Application\Service\Traits\ServiceAwareTrait;
use \Application\Service\Traits\PeriodeAwareTrait;
use \Application\Service\Traits\TypeInterventionAwareTrait;
use \Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use \Application\Service\Traits\EtatVolumeHoraireAwareTrait;


/**
 * Description of SaisieForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieForm extends AbstractViewHelper
{
    use ContextAwareTrait;
    use ServiceAwareTrait;
    use PeriodeAwareTrait;
    use TypeInterventionAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;


    /**
     * @var Saisie
     */
    protected $form;

    /**
     *
     * @return Periode[]
     */
    public function getPeriodes()
    {
        $service = $this->form->get('service')->getObject(); /* @var $service Service */
        if ($service->getElementPedagogique() && $service->getElementPedagogique()->getPeriode()){
            return [ $service->getElementPedagogique()->getPeriode() ];
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
                'service/volumes-horaires-refresh',
                [
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
        if (! $this->getServiceContext()->getSelectedIdentityRole()->getIntervenant()){
            $res .= $this->getView()->formControlGroup($fservice->get('intervenant'));
        }
        if ($fservice->has('interne-externe')){
            $interne = $fservice->get('interne-externe')->getValue() == 'service-interne';
            $res .= $this->getView()->formControlGroup($fservice->get('interne-externe'), 'formButtonGroup');
            $res .= '<div id="element-interne" '.(($interne) ? '' : 'style="display:none"').'>'.$this->getView()->fieldsetElementPedagogiqueRecherche($fservice->get('element-pedagogique')).'</div>';
            $res .= '<div id="element-externe" '.(($interne) ? 'style="display:none"' : '').'>'
                .$this->getView()->formControlGroup($fservice->get('etablissement'))
                .$this->getView()->formControlGroup($fservice->get('description'))
                .'</div>';
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
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->get( $fieldset->get('type-volume-horaire')->getValue() );
        /* @var $typeVolumeHoraire TypeVolumeHoraire */
        $inRealise = $typeVolumeHoraire->isRealise();
        if ($inRealise){
            $vhl = $fieldset->getObject()->getService()->getVolumeHoraireListe()->getChild();
            /* @var $vhl \Application\Entity\VolumeHoraireListe */
            $vhl->setTypeVolumeHoraire( $this->getServiceTypeVolumeHoraire()->getPrevu() );
            $vhl->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getValide() );
            $vhl->setPeriode( $this->getServicePeriode()->get( $fieldset->get('periode')->getValue() ));
        }

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
                    ->setLabelAttributes(['class' => 'control-label']);
            $res .= '<div>';
            $res .= $this->getView()->formLabel( $element );
            if ($inRealise){
                $heures = $vhl->setTypeIntervention($typeIntervention)->getHeures();
                $res .= '<br />Prévues : <span id="prev-'.$typeIntervention->getCode().'" data-heures="'.$heures.'">';
                $res .= \UnicaenApp\Util::formattedNumber( $heures );
                $res .= '</span>';
            }
            $res .= '<br />';
            $res .= $this->getView()->formText( $element);
            $res .= '</div>';
        }
        if ($inRealise){
            $res .= '<div><label>&nbsp;</label><br /><br /><button type="button" class="btn btn-default prevu-to-realise" title="Initialise le formulaire avec les heures prévues">Prévu <span class="glyphicon glyphicon-arrow-right"></span> réalisé</button>';

            $res .= '</div>';
        }
        $res .= '</div><div class="volume-horaire-saisie-multiple-fin"></div>';
        return $res;
    }
}