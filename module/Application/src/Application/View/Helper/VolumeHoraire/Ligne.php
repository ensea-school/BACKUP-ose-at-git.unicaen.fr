<?php

namespace Application\View\Helper\VolumeHoraire;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\VolumeHoraire;

/**
 * Aide de vue permettant d'afficher une ligne de volume horaire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Ligne extends AbstractHelper
{

    /**
     * @var VolumeHoraire
     */
    protected $volumeHoraire;

    /**
     * Contexte
     *
     * @var array
     */
    protected $context;





    /**
     * Helper entry point.
     *
     * @param VolumeHoraire $volumeHoraire
     * @return self
     */
    final public function __invoke( VolumeHoraire $volumeHoraire, array $context=array() )
    {
        $this->volumeHoraire = $volumeHoraire;
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
        $out = '<tr>';

        if (empty($this->context['service'])){
            $out .= '<td>'.$this->renderService($volumeHoraire->getService())."</td>\n";
        }
        $out .= '<td>'.$this->renderPeriode( $this->volumeHoraire->getPeriode() )."</td>\n";
        $out .= '<td>'.$this->renderTypeIntervention( $this->volumeHoraire->getTypeIntervention() )."</td>\n";
        $out .= '<td>'.$this->renderHeures( $this->volumeHoraire->getHeures() )."</td>\n";
        $out .= '<td>'.$this->renderMotifNonPaiement( $this->volumeHoraire->getMotifNonPaiement() )."</td>\n";
        $out .= '</tr>';
        return $out;
    }

    protected function renderService($service)
    {
        $out = '<a href="#">N° <span class="badge">'.(string)$service->getId().'</span></a>'."\n";
        return $out;
    }

    protected function renderPeriode($periode)
    {
        $out = $periode->getLibelle;
        return $out;
    }

    protected function renderTypeIntervention($typeIntervention)
    {
        $out = $typeIntervention->getLibelle();
        return $out;
    }

    protected function renderHeures($heures)
    {
        $out = $heures;
        return $out;
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
     * @return VolumeHoraire
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
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
     * @param VolumeHoraire $volumeHoraire
     * @return self
     */
    public function setVolumeHoraire(VolumeHoraire $volumeHoraire)
    {
        $this->volumeHoraire = $volumeHoraire;
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