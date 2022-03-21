<?php

namespace Application\View\Helper\VolumeHoraire;

use Application\Entity\Db\MotifNonPaiement;
use Application\Hydrator\VolumeHoraire\ListeFilterHydrator;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\View\Helper\AbstractViewHelper;
use Application\Entity\VolumeHoraireListe;
use Application\Entity\Db\TypeIntervention;


/**
 * Aide de vue permettant d'afficher une liste de volumes horaires
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractViewHelper
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
        $canViewMNP                 = $this->getView()->isAllowed($this->getVolumeHoraireListe()->getService()->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_VISUALISATION);
        $canEditMNP                 = $this->getView()->isAllowed($this->getVolumeHoraireListe()->getService()->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_EDITION);

        $out = '<table class="table table-condensed table-bordered volume-horaire">';
        $out .= '<tr>';
        $out .= "<th style=\"width:10%\">Période</th>\n";
        foreach ($this->getTypesInterventions() as $ti) {
            $out .= "<th style=\"width:1%\"><abbr title=\"" . $ti->getLibelle() . "\">" . $ti->getCode() . "</abbr></th>\n";
        }
        if ($canViewMNP) {
            $out .= "<th style=\"width:25%\">Motif de non paiement</th>\n";
        }
        $out      .= "</tr>\n";
        $periodes = $this->getPeriodes();
        foreach ($periodes as $periode) {
            $vhl               = $this->getVolumeHoraireListe()->setPeriode($periode)->setTypeIntervention(false);
            $motifsNonPaiement = [];
            if ($canViewMNP) {  // découpage par motif de non paiement
                $motifsNonPaiement = $vhl->getMotifsNonPaiement();
                if (!isset($motifsNonPaiement[0]) && !$canEditMNP) {
                    $motifsNonPaiement = [0 => null] + $motifsNonPaiement;
                }
            }
            if (empty($motifsNonPaiement)) {
                $motifsNonPaiement = [0 => false];
            }
            foreach ($motifsNonPaiement as $motifNonPaiement) {
                $readOnly         = $motifNonPaiement instanceof MotifNonPaiement && !$canEditMNP;
                $forbiddenPeriode = false;
                if (
                    $this->getVolumeHoraireListe()->getService()
                    && $this->getVolumeHoraireListe()->getService()->getElementPedagogique()
                    && $this->getVolumeHoraireListe()->getService()->getElementPedagogique()->getPeriode()
                    && $this->getVolumeHoraireListe()->getService()->getElementPedagogique()->getPeriode() !== $periode
                ) {
                    $forbiddenPeriode           = true;
                    $this->hasForbiddenPeriodes = true;
                }
                if ($forbiddenPeriode) {
                    $out .= '<tr class="bg-danger">';
                    $out .= "<td><abbr title=\"La période n'est pas conforme à l'enseignement\">" . $this->renderPeriode($periode) . "</abbr></td>\n";
                } else {
                    $out .= '<tr>';
                    $out .= "<td>" . $this->renderPeriode($periode) . "</td>\n";
                }

                foreach ($this->typesIntervention as $typeIntervention) {
                    $vhl->setMotifNonPaiement($motifNonPaiement)
                        ->setTypeIntervention($typeIntervention);
                    if ($vhl->getHeures() == 0) {
                        $class = "heures-empty";
                    } else {
                        $class = "heures-not-empty";
                    }
                    $out .= '<td style="text-align:right" class="' . $class . '">' . $this->renderHeures($vhl, $readOnly) . '</td>';
                }
                if ($canViewMNP) {
                    $out .= "<td>" . $this->renderMotifNonPaiement($motifNonPaiement) . "</td>\n";
                }
                $out .= "</tr>\n";
            }
        }
        $out .= '</table>' . "\n";

        return $out;
    }



    protected function renderPeriode($periode)
    {
        if (!$periode) return "Indéterminée";
        $out = (string)$periode;

        return $out;
    }



    public function renderHeures(VolumeHoraireListe $volumeHoraireListe, $readOnly = false)
    {
        $heures = $volumeHoraireListe->getHeures();
        $heures = \UnicaenApp\Util::formattedNumber($heures);

        $vhlph = new ListeFilterHydrator();
        $query = $vhlph->extractInts($volumeHoraireListe);
        if (false === $volumeHoraireListe->getMotifNonPaiement()) {
            $query['tous-motifs-non-paiement'] = '1';
        }
        if ($readOnly || $this->getReadOnly()) {
            return $heures;
        } else {
            $url = $this->getView()->url(
                'volume-horaire/saisie',
                ['service' => $volumeHoraireListe->getService()->getId()],
                ['query' => $query]
            );

            return "<a class=\"pop-ajax volume-horaire\" data-submit-event=\"save-volume-horaire\" data-service=\"" . $volumeHoraireListe->getService()->getId() . "\" href=\"" . $url . "\" >$heures</a>";
        }
    }



    protected function renderMotifNonPaiement($motifNonPaiement)
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
        $typeVolumeHoraire        = $volumeHoraireListe->getTypeVolumeHoraire();
        $this->volumeHoraireListe = $volumeHoraireListe;
        $this->forcedReadOnly     = !$this->getView()->isAllowed($volumeHoraireListe->getService(), $typeVolumeHoraire->getPrivilegeEnseignementEdition());
        $this->typesIntervention  = null;

        return $this;
    }



    public function getTypesInterventions()
    {
        if (!$this->typesIntervention) {
            if ($this->getVolumeHoraireListe()->getService()->getElementPedagogique()) {
                $tis = $this->getVolumeHoraireListe()->getService()->getElementPedagogique()->getTypeIntervention();
            } else {
                $qb = $this->getServiceTypeIntervention()->finderByContext();
                $this->getServiceTypeIntervention()->finderByHistorique($qb);
                $tis = $this->getServiceTypeIntervention()->getList($qb);
            }
            $this->typesIntervention = [];
            foreach ($tis as $ti) {
                $this->typesIntervention[] = $ti;
            }
            uasort($this->typesIntervention, function ($a, $b) {
                return $a->getordre() - $b->getOrdre();
            });
        }

        return $this->typesIntervention;
    }



    public function getPeriodes()
    {
        $vhl = $this->getVolumeHoraireListe()->createChild()
            ->setTypeIntervention(false)
            ->setPeriode(false);

        $periodes   = $this->getServiceService()->getPeriodes($vhl->getService());
        $vhPeriodes = $vhl->getPeriodes();
        foreach ($vhPeriodes as $periode) {
            if (!isset($periodes[$periode->getId()])) $periodes[$periode->getId()] = $periode;
        }
        uasort($periodes, function ($a, $b) {
            return ($a ? $a->getOrdre() : '') > ($b ? $b->getOrdre() : '') ? 1 : 0;
        });

        return $periodes;
    }

}