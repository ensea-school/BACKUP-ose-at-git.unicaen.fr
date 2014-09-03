<?php

namespace Application\View\Helper\VolumeHoraire;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\VolumeHoraireListe;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Entity\Db\TypeIntervention;


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
     * @param VolumeHoraireListe $volumeHoraireListe
     * @return self
     */
    final public function __invoke( VolumeHoraireListe $volumeHoraireListe )
    {
        /* Initialisation */
        $this->setVolumeHoraireListe( $volumeHoraireListe );
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
                'volume-horaire/default',
                [
                    'action' => 'liste', 'id' => $this->getVolumeHoraireListe()->getService()->getId()
                ], ['query' => [
                    'read-only' => $this->getReadOnly() ? '1' : '0',
                    'type-volume-horaire' => $this->getVolumeHoraireListe()->getTypeVolumehoraire()->getId(),
                ]]);
        return $url;
    }

    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render(){
        $hasMotifNonPaiement = $this->getServiceService()->canHaveMotifNonPaiement($this->getVolumeHoraireListe()->getService());

        $out = '<table class="table table-condensed table-bordered volume-horaire">';
        $out .= '<tr>';
        $out .= "<th style=\"width:10%\">Période</th>\n";
        foreach( $this->getTypesInterventions() as $ti ){
            $out .= "<th style=\"width:1%\"><abbr title=\"".$ti->getLibelle()."\">".$ti->getCode()."</abbr></th>\n";
        }
        if ($hasMotifNonPaiement){
            $out .= "<th style=\"width:25%\">Motif de non paiement</th>\n";
        }
        $out .= "</tr>\n";
        $periodes = $this->getServiceService()->getPeriodes( $this->getVolumeHoraireListe()->getService() );
        foreach( $periodes as $periode ){
            $vhl = $this->getVolumeHoraireListe()->setPeriode($periode)->setTypeIntervention(false);
            $motifsNonPaiement = [];
            if ($hasMotifNonPaiement){  // découpage par motif de non paiement
                $motifsNonPaiement = $vhl->getMotifsNonPaiement();
            }
            if(empty($motifsNonPaiement)){
                $motifsNonPaiement = [0 => false];
            }
            foreach( $motifsNonPaiement as $motifNonPaiement ){
                $out .= '<tr>';
                $out .= "<td>".$this->renderPeriode($periode)."</td>\n";
                foreach( $this->typesIntervention as $typeIntervention ){
                    $vhl->setMotifNonPaiement($motifNonPaiement)
                        ->setTypeIntervention($typeIntervention);
                    $out .= '<td style="text-align:right">'.$this->renderHeures( $vhl ).'</td>';
                }
                if ($hasMotifNonPaiement){
                    $out .= "<td>".$this->renderMotifNonPaiement($motifNonPaiement)."</td>\n";
                }
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

    public function renderHeures(VolumeHoraireListe $volumeHoraireListe)
    {
        $heures = $volumeHoraireListe->getHeures();
        if (0 !== $heures){
            $heures = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, -1);
        }else{
            $heures = \UnicaenApp\Util::formattedFloat(0, \NumberFormatter::DECIMAL, -1);;
        }

        $query = $volumeHoraireListe->filtersToArray();
        if (false === $volumeHoraireListe->getMotifNonPaiement()){
            $query['tous-motifs-non-paiement'] = '1';
        }
        if ($this->getReadOnly()){
            return $heures;
        }else{
            $url = $this->getView()->url(
                        'volume-horaire/saisie',
                        ['service' => $volumeHoraireListe->getService()->getId()],
                        ['query' => $query]
                   );

            return "<a class=\"ajax-popover volume-horaire\" data-event=\"save-volume-horaire\" data-placement=\"bottom\" data-service=\"".$volumeHoraireListe->getService()->getId()."\" href=\"".$url."\" >$heures</a>";
        }
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
     * @return VolumeHoraireListe
     */
    public function getVolumeHoraireListe()
    {
        return $this->volumeHoraireListe;
    }

    public function setVolumeHoraireListe(VolumeHoraireListe $volumeHoraireListe)
    {
        if (! $volumeHoraireListe->getTypeVolumeHoraire() instanceof \Application\Entity\Db\TypeVolumeHoraire){
            throw new \Common\Exception\LogicException('Le type de volume horaire de la liste n\'a pas été précisé');
        }
        $this->volumeHoraireListe = $volumeHoraireListe;
        $this->forcedReadOnly = ! $this->getServiceService()->canModify($volumeHoraireListe->getService());
        $this->typesIntervention = null;
        return $this;
    }

    public function getTypesInterventions()
    {
        if (! $this->typesIntervention){
            if ($this->getVolumeHoraireListe()->getService()->getElementPedagogique()){
                $this->typesIntervention = $this->getVolumeHoraireListe()->getService()->getElementPedagogique()->getTypeIntervention();
            }else{
                $this->typesIntervention = $this->getServiceTypeIntervention()->getList();
            }
        }
        return $this->typesIntervention;
    }

    /**
     * @return \Application\Service\Service
     */
    protected function getServiceService()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationService');
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
    }

}