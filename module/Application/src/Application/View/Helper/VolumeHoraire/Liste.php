<?php

namespace Application\View\Helper\VolumeHoraire;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\VolumeHoraire;
use Doctrine\ORM\PersistentCollection;

/**
 * Aide de vue permettant d'afficher une liste de volumes horaires
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper
{

    /**
     * Données formattées
     *
     * @var array
     */
    protected $data = array();

    /**
     * typesIntervention
     *
     * @var array
     */
    public static $typesIntervention;



    /**
     * Helper entry point.
     *
     * @param VolumeHoraire[]|PersistentCollection $volumeHoraires
     * @param array $context
     * @return self
     */
    final public function __invoke( $volumeHoraires, array $context=array() )
    {
        $this->setVolumeHoraires( $volumeHoraires );
        $this->context = $context;
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
    protected function render(){
        $out = '<table class="table volume-horaire">';
        $out .= '<tr>';

        if (empty($this->context['service'])){
            $out .= "<th style=\"width:15%\">Service</th>\n";
        }
        $out .= "<th style=\"width:10%\">Période</th>\n";
        foreach( self::$typesIntervention as $ti ){
            $out .= "<th style=\"width:8%\" title=\"".$ti->getLibelle()."\">".$ti->getCode()."</th>\n";
        }
        $out .= "<th style=\"width:25%\">Motif de non paiement</th>\n";
        $out .= "</tr>\n";
        foreach( $this->data as $gvh ){
            $default = $gvh['vhDefault'];
            $out .= '<tr>';
            if (empty($this->context['service'])){
                $out .= "<td>".$this->renderService($default->getService())."</td>\n";
            }
            $out .= "<td>".$this->renderPeriode($default->getPeriode())."</td>\n";
            foreach( self::$typesIntervention as $ti ){
                if (isset($gvh[$ti->getId()])){
                    $out .= "<td>".$this->renderHeures($gvh[$ti->getId()]->getHeures())."</td>\n";
                }else{
                    $out .= '<td>&nbsp;</td>';
                }
            }
            $out .= "<td>".$this->renderMotifNonPaiement($default->getMotifNonPaiement())."</td>\n";
            $out .= "</tr>\n";
        }
        $out .= '</table>'."\n";
        return $out;
    }

    protected function renderService($service)
    {
        $out = '<a href="#">N° <span class="badge">'.(string)$service->getId().'</span></a>'."\n";
        return $out;
    }

    protected function renderPeriode($periode)
    {
        $out = $periode->getLibelle();
        return $out;
    }

    protected function renderHeures($heures)
    {
        $h = floor($heures);
        $m = ($heures - floor($heures)) * 60;
        if (0 == $m){
            return $h.'h';
        }else{
            return $h.'h'.sprintf('%02s', $m);
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
     * @return VolumeHoraire[]
     */
    public function getVolumeHoraires()
    {
        return $this->volumeHoraires;
    }

    /**
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     *
     * @param VolumeHoraire[] $volumeHoraires
     * @return self
     */
    public function setVolumeHoraires($volumeHoraires)
    {
        $this->volumeHoraires = $volumeHoraires;
        $this->data = array();
        foreach( $volumeHoraires as $vh ){
            $key = $vh->getService()->getId().'_'.$vh->getPeriode()->getId().'_';
            if ($mnp = $vh->getMotifNonPaiement()) $key .= $mnp->getId();
            if (! isset($this->data[$key])){
                $this->data[$key]['vhDefault'] = $vh;
            }
            $this->data[$key][$vh->getTypeIntervention()->getId()] = $vh;
        }
        return $this;
    }

    /**
     *
     * @param array $context
     * @return self
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

}