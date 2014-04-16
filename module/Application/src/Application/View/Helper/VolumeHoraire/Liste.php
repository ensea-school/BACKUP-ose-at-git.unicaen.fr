<?php

namespace Application\View\Helper\VolumeHoraire;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\PersistentCollection;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\Service;
use Application\Entity\Db\TypeIntervention;


/**
 * Aide de vue permettant d'afficher une liste de volumes horaires
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Données formattées
     *
     * @var VolumeHoraire[][][]
     */
    protected $data = array();

    /**
     * Service
     *
     * @var Service
     */
    protected $service;

    /**
     * Liste des types d'intervention
     *
     * @var TypeIntervention[]
     */
    protected $typesIntervention;




    /**
     * Helper entry point.
     *
     * @param VolumeHoraire[]|PersistentCollection $volumeHoraires
     * @param Service $service
     * @return self
     */
    final public function __invoke( $volumeHoraires, Service $service )
    {
        /* Initialisation */
        $this->setService( $service );
        $this->setVolumeHoraires( $volumeHoraires );
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

    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render(){
        $out = '<table class="table volume-horaire">';
        $out .= '<tr>';
        $out .= "<th style=\"width:10%\">Période</th>\n";
        foreach( $this->typesIntervention as $ti ){
            $out .= "<th style=\"width:8%\" title=\"".$ti->getLibelle()."\">".$ti->getCode()."</th>\n";
        }
        $out .= "<th style=\"width:25%\">Motif de non paiement</th>\n";
        $out .= "</tr>\n";
        foreach( $this->data as $pid => $motifsNonPaiement ){
            $periode = $motifsNonPaiement['periode'];
            unset($motifsNonPaiement['periode']);
            foreach( $motifsNonPaiement as $mid => $typesIntervention ){
                $motifNonPaiement = isset($typesIntervention['motifNonPaiement']) ? $typesIntervention['motifNonPaiement'] : null;
                $mid = $motifNonPaiement ? $motifNonPaiement->getId() : null;
                $out .= '<tr>';
                $out .= "<td>".$this->renderPeriode($periode)."</td>\n";
                foreach( $this->typesIntervention as $tid => $null ){
                    $out .= '<td>'.$this->renderHeures( $typesIntervention[$tid], $pid, $mid, $tid ).'</td>';
                }
                $out .= "<td>".$this->renderMotifNonPaiement($motifNonPaiement)."</td>\n";
                $out .= "</tr>\n";
            }
        }
        $out .= '</table>'."\n";
        return $out;
    }

    protected function renderPeriode($periode)
    {
        if (! $periode) return "Indéterminée";
        $out = (string)$periode;
        return $out;
    }

    protected function renderHeures($volumeHoraire, $periodeId, $motifNonPaiementId, $typeInterventionId)
    {
        if ($volumeHoraire){
            $id = $volumeHoraire->getId();
            $heures = \UnicaenApp\Util::formattedFloat($volumeHoraire->getHeures(), \NumberFormatter::DECIMAL, -1);
        }else{
            $id = null;
            $heures = 0;
        }
        $context = array();
        $params = array('action' => 'saisie');
        if ($id)                 $params['id'] = $id;
        if ($this->getService()) $context['service'] = $this->getService()->getId();
        if ($periodeId)          $context['periode'] = $periodeId;
        if ($motifNonPaiementId) $context['motifNonPaiement'] = $motifNonPaiementId;
        if ($typeInterventionId) $context['typeIntervention'] = $typeInterventionId;
        return "<a class=\"ajax-popover volume-horaire event_save-volume-horaire\" data-placement=\"bottom\" data-service=\"".$context['service']."\" href=\"".$this->getView()->url('volume-horaire/default', $params, array('query' => $context ) )."\" >$heures</a>";
    }

    protected function renderMotifNonPaiement($motifNonPaiement)
    {
        if (! empty($motifNonPaiement)){
            $out = $motifNonPaiement->getLibelleLong();
        }else{
            $out = '';
        }
        return $out;
    }

    /**
     *
     * @param VolumeHoraire[] $volumeHoraires
     * @return self
     */
    public function setVolumeHoraires($volumeHoraires)
    {
        $serviceTypeIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention');
        /* @var $serviceTypeIntervention \Application\Service\TypeIntervention */

        $periodes = null;
        $elementPedagogique = $this->getService()->getElementPedagogique();
        $periodes = null;
        if ($elementPedagogique){
            $periode = $elementPedagogique->getPeriode();
            if ($periode){
                // Liste des périodes possibles iniitalisée en fonction de l'élément pédagogique
                $periodes = array( $periode->getId() => $periode );
            }
        }
        if (! $periodes){
            // Récupération des périodes issues du service Periodes
            $servicePeriode = $this->getServiceLocator()->getServiceLocator()->get('applicationPeriode');
            /* @var $servicePeriode \Application\Service\Periode */
            $periodes = $servicePeriode->getList( $servicePeriode->finderByEnseignement() );
        }
        /* Récupération éventuelle des volumes horaires saisis sur d'autres périodes que celles habituelles (en cas de besoin) */
        foreach( $volumeHoraires as $vh ){
            if ($vh->getPeriode() && ! isset($periodes[$vh->getPeriode()->getId()])){
                $periodes[$vh->getPeriode()->getId()] = $vh->getPeriode();
            }
        }
        /* Tri des périodes */
        uasort( $periodes, function( $a, $b ){
            return ($a ? $a->getOrdre() : '') > ($b ? $b->getOrdre() : '');
        });

        $typesIntervention = $serviceTypeIntervention->getTypesIntervention();
        $this->typesIntervention = $typesIntervention;
        $this->data = array(); // DATA [Periode][MotifNonPaiement][TypeIntervention]

        /* Initialisation du tableau */
        $this->data = array();
        foreach( $periodes as $pid => $p ){
            $motifsNonPaiement = array(0 => null);
            foreach( $volumeHoraires as $vh ){
                if ($vh->getPeriode() === $p){
                    if ($motifNonPaiement = $vh->getMotifNonPaiement()){
                        $motifsNonPaiement[$motifNonPaiement->getId()] = $motifNonPaiement;
                    }
                }
            }
            /* Tri des motifs de non paiement */
            uasort( $motifsNonPaiement, function( $a, $b ){
                return ($a ? $a->getLibelleLong() : '') > ($b ? $b->getLibelleLong() : '');
            });

            if (! isset($this->data[$pid])) $this->data[$pid] = array('periode' => $p);
            foreach( $motifsNonPaiement as $mid => $m ){
                if (! isset($this->data[$pid][$mid])) $this->data[$pid][$mid] = array('motifNonPaiement' => $m);
                foreach( $typesIntervention as $tid => $t ){
                    $this->data[$pid][$mid][$tid] = null;
                }
            }
        }

        /* Affectation des valeurs */
        foreach( $volumeHoraires as $vh ){
            $pid = $vh->getPeriode() ? $vh->getPeriode()->getId() : 0;
            $mid = $vh->getMotifNonPaiement() ? $vh->getMotifNonPaiement()->getId() : 0;
            $tid = $vh->getTypeIntervention()->getId();
            $this->data[$pid][$mid][$tid] = $vh;
        }
        return $this;
    }

    public function getService()
    {
        return $this->service;
    }

    public function setService(Service $service)
    {
        $this->service = $service;
        return $this;
    }


}