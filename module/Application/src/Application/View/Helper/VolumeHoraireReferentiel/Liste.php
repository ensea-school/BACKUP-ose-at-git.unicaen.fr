<?php

namespace Application\View\Helper\VolumeHoraireReferentiel;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\VolumeHoraireReferentielListe;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Aide de vue permettant d'afficher une liste de volumes horaires
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * @var VolumeHoraireReferentielListe
     */
    protected $volumeHoraireListe;

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
     * @return self
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     * Helper entry point.
     *
     * @param VolumeHoraireReferentielListe $volumeHoraireListe
     * @return self
     */
    final public function __invoke(VolumeHoraireReferentielListe $volumeHoraireListe)
    {
        /* Initialisation */
        $this->setVolumeHoraireReferentielListe($volumeHoraireListe);
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
                'volume-horaire/default', [
            'action' => 'liste', 'id'     => $this->getVolumeHoraireReferentielListe()->getService()->getId()
                ], ['query' => [
                'read-only'           => $this->getReadOnly() ? '1' : '0',
                'type-volume-horaire' => $this->getVolumeHoraireReferentielListe()->getTypeVolumehoraire()->getId(),
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
//        $this->hasForbiddenPeriodes = false;
//
//        $out = '<table class="table table-condensed table-bordered volume-horaire">';
//        $out .= '<tr>';
//        $out .= "<th style=\"width:10%\">Période</th>\n";
//        $out .= "</tr>\n";
//        $periodes = $this->getPeriodes();
//        foreach( $periodes as $periode ){
//            $vhl = $this->getVolumeHoraireReferentielListe()->setPeriode($periode)->setTypeIntervention(false);
//            $motifsNonPaiement = [];
//            if(empty($motifsNonPaiement)){
//                $motifsNonPaiement = [0 => false];
//            }
//            foreach( $motifsNonPaiement as $motifNonPaiement ){
//                $forbiddenPeriode = false;
//                if (
//                       $this->getVolumeHoraireReferentielListe()->getService()
//                    && $this->getVolumeHoraireReferentielListe()->getService()->getElementPedagogique()
//                    && $this->getVolumeHoraireReferentielListe()->getService()->getElementPedagogique()->getPeriode()
//                    && $this->getVolumeHoraireReferentielListe()->getService()->getElementPedagogique()->getPeriode() !== $periode
//                ){
//                    $forbiddenPeriode = true;
//                    $this->hasForbiddenPeriodes = true;
//                }
//                if ($forbiddenPeriode){
//                    $out .= '<tr class="bg-danger">';
//                    $out .= "<td><abbr title=\"La période n'est pas conforme à l'enseignement\">".$this->renderPeriode($periode)."</abbr></td>\n";
//                }else{
//                    $out .= '<tr>';
//                    $out .= "<td>".$this->renderPeriode($periode)."</td>\n";
//                }
//                
//                foreach( $this->typesIntervention as $typeIntervention ){
//                    $vhl->setMotifNonPaiement($motifNonPaiement)
//                        ->setTypeIntervention($typeIntervention);
//                    $out .= '<td style="text-align:right">'.$this->renderHeures( $vhl ).'</td>';
//                }
//                $out .= "</tr>\n";
//            }
//        }
//        $out .= '</table>' . "\n";
        $out = $this->renderHeures($this->getVolumeHoraireReferentielListe());
        
        return $out;
    }

    public function renderHeures(VolumeHoraireReferentielListe $volumeHoraireListe)
    {
        $heures = \Common\Util::formattedHeures($volumeHoraireListe->getHeures());

        $query = $volumeHoraireListe->filtersToArray();
//        if (false === $volumeHoraireListe->getMotifNonPaiement()) {
//            $query['tous-motifs-non-paiement'] = '1';
//        }
        if ($this->getReadOnly()) {
            return $heures;
        }
        else {
            $url = $this->getView()->url(
                    'volume-horaire-referentiel/saisie', ['serviceReferentiel' => $volumeHoraireListe->getService()->getId()], ['query' => $query]
            );

            return "<a class=\"ajax-popover volume-horaire\" data-event=\"save-volume-horaire-referentiel\" data-placement=\"bottom\" data-service=\"" . $volumeHoraireListe->getService()->getId() . "\" href=\"" . $url . "\" >$heures</a>";
        }
    }

    /**
     *
     * @return VolumeHoraireReferentielListe
     */
    public function getVolumeHoraireReferentielListe()
    {
        return $this->volumeHoraireListe;
    }

    public function setVolumeHoraireReferentielListe(VolumeHoraireReferentielListe $volumeHoraireListe)
    {
        if (!$volumeHoraireListe->getTypeVolumeHoraire() instanceof \Application\Entity\Db\TypeVolumeHoraire) {
            throw new \Common\Exception\LogicException('Le type de volume horaire de la liste n\'a pas été précisé');
        }
        $this->volumeHoraireListe = $volumeHoraireListe;
        $this->forcedReadOnly     = !$this->getView()->isAllowed($volumeHoraireListe->getService(), 'update');
        return $this;
    }

    /**
     * @return \Application\Service\ServiceReferentiel
     */
    protected function getServiceServiceReferentiel()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationServiceReferentiel');
    }
}