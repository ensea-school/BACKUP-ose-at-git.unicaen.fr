<?php

namespace Application\View\Helper\VolumeHoraire;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\VolumeHoraire;

/**
 * Aide de vue permettant d'afficher une liste de volumes horaires
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper
{

    /**
     * Helper entry point.
     *
     * @param VolumeHoraire[] $volumeHoraires
     * @param array $context
     * @return self
     */
    final public function __invoke( array $volumeHoraires, array $context=array() )
    {
        $this->volumeHoraires = $volumeHoraires;
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
        if (empty($this->services)) return 'Aucun volume horaire n\'est renseigné';

        $out = '<table class="table">';
        $out .= '<tr>';

        if (empty($this->context['service'])){
            $out .= "<th>Service</th>\n";
        }
        $out .= "<th>Période</th>\n";
        $out .= "<th>Type d'intervention</th>\n";
        $out .= "<th>Heures</th>\n";
        $out .= "<th>Motif de non paiement</th>\n";
        $out .= "</tr>\n";
        foreach( $this->volumeHoraires as $volumeHoraire ){
            $out .= $this->getView()->volumeHoraireLigne( $volumeHoraire, $this->context );
        }
        $out .= '</table>'."\n";
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
    public function setVolumeHoraires(array $volumeHoraires)
    {
        $this->volumeHoraires = $volumeHoraires;
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