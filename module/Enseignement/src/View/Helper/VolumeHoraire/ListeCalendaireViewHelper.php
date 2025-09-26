<?php

namespace Enseignement\View\Helper\VolumeHoraire;

use Application\Constants;
use Application\Provider\Privileges;
use Enseignement\Entity\VolumeHoraireListe;
use Enseignement\Hydrator\ListeFilterHydrator;
use Enseignement\Service\ServiceServiceAwareTrait;
use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use Service\Entity\Db\Tag;


/**
 * Aide de vue permettant d'afficher une liste de volumes horaires
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ListeCalendaireViewHelper extends AbstractHtmlElement
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
        $service = $this->getVolumeHoraireListe()->getService();

        $canViewMNP = $this->getView()->isAllowed($service->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_VISUALISATION);
        $canViewTag = $this->getView()->isAllowed($service->getIntervenant(), Privileges::TAG_VISUALISATION);
        $canEdit = $this->getView()->isAllowed($service, Privileges::ENSEIGNEMENT_PREVU_EDITION) || $this->getView()->isAllowed($service, Privileges::ENSEIGNEMENT_REALISE_EDITION);

        $filtres = [
            VolumeHoraireListe::FILTRE_HORAIRE_DEBUT,
            VolumeHoraireListe::FILTRE_HORAIRE_FIN,
            VolumeHoraireListe::FILTRE_TYPE_INTERVENTION,
            VolumeHoraireListe::FILTRE_PERIODE,
            VolumeHoraireListe::FILTRE_TAG,

        ];
        if ($canViewMNP) {
            $filtres[] = VolumeHoraireListe::FILTRE_MOTIF_NON_PAIEMENT;
        }
        if ($canViewTag) {
            $filtres[] = VolumeHoraireListe::FILTRE_TAG;
        }

        $out = '<table class="table table-xs table-bordered volume-horaire">';
        $out .= '<thead>';
        $out .= '<tr>';
        $out .= "<th style=\"width:10em\">Début</th>\n";
        $out .= "<th style=\"width:4em\">Heures</th>\n";
        $out .= "<th style=\"width:11em\">Type d'intervention</th>\n";
        $out .= "<th style=\"width:10em\">Fin</th>\n";
        $out .= "<th style=\"widt:6em\">Période</th>\n";
        if ($canViewMNP) {
            $out .= "<th>Motif de non paiement</th>\n";
        }
        if ($canViewTag) {
            $out .= "<th>Tag</th>\n";
        }
        if ($canEdit) {
            $out .= "<th style='text-align:center'>" . $this->renderAddAction($this->getVolumeHoraireListe()->createChild()->setNew(true)) . "</th>\n";
        }
        $out .= "</tr>\n";
        $out .= '</thead>';
        $out .= '<body>';

        $vhls = $this->getVolumeHoraireListe()->getSousListes($filtres);

        foreach ($vhls as $vhl) {
            $motifNonPaiement = $vhl->getMotifNonPaiement();
            if (empty($motifNonPaiement)) {
                $motifNonPaiement = false;
            }
            $tag = $vhl->getTag();
            if (empty($tag)) {
                $tag = false;
            }
            if ($vhl->getHeures() != 0) {
                $out .= '<tr>';
                $out .= "<td>" . $this->renderHoraire($vhl->getHoraireDebut()) . "</td>\n";
                $out .= "<td>" . $this->renderHeures($vhl) . "</td>\n";
                $out .= "<td>" . $this->renderTypeIntervention($vhl->getTypeIntervention()) . "</td>\n";
                $out .= "<td>" . $this->renderHoraire($vhl->getHoraireFin()) . "</td>\n";
                $out .= "<td>" . $vhl->getPeriode() . "</td>\n";
                if ($canViewMNP) {
                    $out .= "<td>" . $this->renderMotifNonPaiement($motifNonPaiement) . "</td>\n";
                }
                if ($canViewTag) {
                    $out .= "<td>" . $this->renderTag($tag) . "</td>\n";
                }
                if ($canEdit) {
                    $out .= "<td style='width:1%;white-space:nowrap'>" . $this->renderActions($vhl) . "</td>\n";
                }
                $out .= "</tr>\n";
            }
        }
        $out .= '</tbody>';
        $out .= "</table>";

        return $out;
    }


    private function renderAddAction(VolumeHoraireListe $volumeHoraireListe)
    {
        $vhlph = new ListeFilterHydrator();

        $p1 = ['service' => $volumeHoraireListe->getService()->getId()];
        $p2 = ['query' => $vhlph->extractInts($volumeHoraireListe)];

        return $this->getView()->tag('a', [
            'href'              => $this->getView()->url('volume-horaire/saisie-calendaire', $p1, $p2),
            'title'             => 'Ajouter',
            'class'             => 'pop-ajax',
            'data-submit-event' => 'save-volume-horaire',
            'data-min-width'    => '450px',
            'data-service'      => $volumeHoraireListe->getService()->getId(),
        ])->html('<i class="fas fa-plus"></i');
    }


    private function renderActions(VolumeHoraireListe $volumeHoraireListe)
    {
        $vhlph = new ListeFilterHydrator();

        $p1 = ['service' => $volumeHoraireListe->getService()->getId()];
        $p2 = ['query' => $vhlph->extractInts($volumeHoraireListe)];

        $edit = $this->getView()->tag('a', [
            'href'              => $this->getView()->url('volume-horaire/saisie-calendaire', $p1, $p2),
            'title'             => 'Modifier',
            'class'             => 'pop-ajax',
            'data-submit-event' => 'save-volume-horaire',
            'data-min-width'    => '450px',
            'data-service'      => $volumeHoraireListe->getService()->getId(),
        ])->html('<i class="fas fa-pencil"></i>');

        $delete = $this->getView()->tag('a', [
            'href'              => $this->getView()->url('volume-horaire/suppression-calendaire', $p1, $p2),
            'title'             => 'Supprimer',
            'class'             => 'pop-ajax',
            'data-submit-event' => 'save-volume-horaire',
            'data-content'      => 'Souhaitez-vous vraiment supprimer ces heures de service ?',
            'data-confirm'      => 'true',
            'data-service'      => $volumeHoraireListe->getService()->getId(),
        ])->html('<i class="fas fa-trash-can"></i>');

        return $edit . ' ' . $delete;
    }


    private function renderTypeIntervention(TypeIntervention $typeIntervention)
    {
        return "<abbr title=\"" . $typeIntervention->getLibelle() . "\">" . $typeIntervention->getCode() . "</abbr>";
    }


    private function renderHoraire($horaire)
    {
        if (!$horaire instanceof \DateTime) return null;

        $horaire = $horaire->format(Constants::DATETIME_FORMAT);

        if (str_ends_with($horaire, '00:00')){
            $horaire = substr($horaire, 0,-6);
        }

        return $horaire;
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

    protected function renderTag($tag)
    {
        /**
         * @var Tag $tag
         */

        if (!empty($tag)) {
            $out = $tag->getLibelleLong();
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
        $typeVolumeHoraire = $volumeHoraireListe->getTypeVolumeHoraire();
        $this->volumeHoraireListe = $volumeHoraireListe;
        $this->forcedReadOnly = !$this->getView()->isAllowed($volumeHoraireListe->getService(), $typeVolumeHoraire->getPrivilegeEnseignementEdition());
        $this->typesIntervention = null;

        return $this;
    }

}