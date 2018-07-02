<?php

namespace Application\View\Helper\VolumeHoraire;

use Application\Constants;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\VolumeHoraireListe;
use Application\Hydrator\VolumeHoraire\ListeFilterHydrator;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\View\Helper\AbstractViewHelper;


/**
 * Aide de vue permettant d'afficher une liste de volumes horaires
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ListeCalendaire extends AbstractViewHelper
{
    use ServiceServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;

    /**
     * @var VolumeHoraireListe
     */
    protected $volumeHoraireListe;

    /**
     * Liste des types d'intervention
     *
     * @var TypeIntervention[]
     */
    protected $typesIntervention;

    /**
     * readOnly
     *
     * @var boolean
     */
    protected $readOnly;

    /**
     * Mode lecture seule forcé
     *
     * @var boolean
     */
    protected $forcedReadOnly;

    /**
     * hasForbiddenPeriodes
     *
     * @var boolean
     */
    protected $hasForbiddenPeriodes = false;



    /**
     *
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->readOnly || $this->forcedReadOnly;
    }



    /**
     *
     * @param boolean $readOnly
     *
     * @return self
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }



    public function hasForbiddenPeriodes()
    {
        return $this->hasForbiddenPeriodes;
    }



    /**
     * Helper entry point.
     *
     * @param VolumeHoraireListe $volumeHoraireListe
     *
     * @return self
     */
    final public function __invoke(VolumeHoraireListe $volumeHoraireListe)
    {
        /* Initialisation */
        $this->setVolumeHoraireListe($volumeHoraireListe);

        return $this;
    }



    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }



    public function getRefreshUrl()
    {
        $url = $this->getView()->url(
            'volume-horaire/liste',
            [
                'service' => $this->getVolumeHoraireListe()->getService()->getId(),
            ], ['query' => [
            'read-only'           => $this->getReadOnly() ? '1' : '0',
            'type-volume-horaire' => $this->getVolumeHoraireListe()->getTypeVolumehoraire()->getId(),
        ]]);

        return $url;
    }



    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render()
    {
        $this->hasForbiddenPeriodes = false;
        $canViewMNP                 = false;//$this->getView()->isAllowed($this->getVolumeHoraireListe()->getService()->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_VISUALISATION);
        $canEdit                    = true;

        $filtres = [
            VolumeHoraireListe::FILTRE_HORAIRE_DEBUT,
            VolumeHoraireListe::FILTRE_HORAIRE_FIN,
            VolumeHoraireListe::FILTRE_TYPE_INTERVENTION,
            VolumeHoraireListe::FILTRE_PERIODE,
        ];
        if ($canViewMNP){
            $filtres[] = VolumeHoraireListe::FILTRE_MOTIF_NON_PAIEMENT;
        }

        $out = '<table class="table table-condensed table-extra-condensed table-bordered volume-horaire">';
        $out .= '<thead>';
        $out .= '<tr>';
        $out .= "<th style=\"width:10em\">Début</th>\n";
        $out .= "<th style=\"width:4em\">Heures</th>\n";
        $out .= "<th style=\"width:11em\">Mode</th>\n";
        $out .= "<th style=\"width:10em\">Fin</th>\n";
        $out .= "<th style=\"widt:6em\">Période</th>\n";
        if ($canViewMNP){
            $out .= "<th>Motif de non paiement</th>\n";
        }
        if ($canEdit){
            $out .= "<th>&nbsp;</th>\n";
        }
        $out      .= "</tr>\n";
        $out .= '</thead>';
        $out .= '<body>';

        $vhls = $this->getVolumeHoraireListe()->getSousListes($filtres);
        foreach( $vhls as $vhl ){
            if ($vhl->getHeures() != 0) {
                $out .= '<tr>';
                $out .= "<td>" . $this->renderHoraire($vhl->getHoraireDebut()) . "</td>\n";
                $out .= "<td>" . $this->renderHeures($vhl) ."</td>\n";
                $out .= "<td>" . $this->renderTypeIntervention($vhl->getTypeIntervention()) ."</td>\n";
                $out .= "<td>" . $this->renderHoraire($vhl->getHoraireFin()) . "</td>\n";
                $out .= "<td>" . $vhl->getPeriode() . "</td>\n";
                if ($canViewMNP) {
                    $out .= "<td>" . $this->renderMotifNonPaiement($vhl->getMotifNonPaiement()) . "</td>\n";
                }
                if ($canEdit){
                    $out .= "<td>".$this->renderActions($vhl)."</td>\n";
                }
                $out .= "</tr>\n";
            }
        }
        $out .= '</tbody>';
        $out .= "</table>";

        return $out;
    }



    private function renderActions(VolumeHoraireListe $volumeHoraireListe)
    {
        $vhlph = new ListeFilterHydrator();

        $p1 = [
            'service' => $volumeHoraireListe->getService()->getId(),
        ];
        $p2 = [
            'query' => $vhlph->extract($volumeHoraireListe),
        ];
        if (false == $volumeHoraireListe->getMotifNonPaiement()){
            $p2['query']['motif-non-paiement'] = 'all';
        }

        return $this->getView()->tag('a', [
            'href' => $this->getView()->url('volume-horaire/saisie-calendaire', $p1, $p2),
            'class' => 'ajax-modal',
        ] )->html('<span class="glyphicon glyphicon-pencil"></span>');
    }



    private function renderTypeIntervention(TypeIntervention $typeIntervention)
    {
        return "<abbr title=\"".$typeIntervention->getLibelle()."\">".$typeIntervention->getCode()."</abbr>";
    }



    private function renderHoraire( $horaire )
    {
        if (!$horaire instanceof \DateTime) return null;

        return $horaire->format(Constants::DATETIME_FORMAT);
    }



    private function renderHeures(VolumeHoraireListe $volumeHoraireListe)
    {
        return \UnicaenApp\Util::formattedNumber($volumeHoraireListe->getHeures());
    }



    private function renderMotifNonPaiement($motifNonPaiement)
    {
        if (!empty($motifNonPaiement)) {
            $out = $motifNonPaiement->getLibelleLong();
        } else {
            $out = '';
        }

        return $out;
    }



    /**
     *
     * @return VolumeHoraireListe
     */
    public function getVolumeHoraireListe()
    {
        return $this->volumeHoraireListe;
    }



    public function setVolumeHoraireListe(VolumeHoraireListe $volumeHoraireListe)
    {
        $this->volumeHoraireListe = $volumeHoraireListe;
        $this->forcedReadOnly     = !$this->getView()->isAllowed($volumeHoraireListe->getService(), Privileges::ENSEIGNEMENT_EDITION);
        $this->typesIntervention  = null;

        return $this;
    }

}