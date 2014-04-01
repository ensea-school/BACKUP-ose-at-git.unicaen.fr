<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Service;

/**
 * Aide de vue permettant d'afficher une liste de services
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper
{

    /**
     * Helper entry point.
     *
     * @param Service[] $services
     * @param array $context
     * @return self
     */
    final public function __invoke( array $services, array $context=array() )
    {
        $this->services = $services;
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
        if (empty($this->services)) return 'Aucun service n\'est renseigné';

        $colspan = 5;

        $out = '<table class="table service">';
        $out .= '<tr>';

        $out .= "<th>Numéro</th>\n";
        if (empty($this->context['intervenant'])){
            $out .= "<th colspan=\"2\">Intervenant</th>\n";
            $colspan += 2;
        }
        $out .= "<th>Structure</th>\n";
        $out .= "<th>Elément pédagogique</th>\n";
        if (empty($this->context['annee'])){
            $out .= "<th>Année univ.</th>\n";
            $colspan += 1;
        }
        $out .= "<th>&Eacute;tablissement</th>\n";
        $out .= "<th>&nbsp;</th>\n";
        $out .= "</tr>\n";
        foreach( $this->services as $service ){
            $out .= $this->getView()->serviceLigne( $service, $this->context );
            $out .= '<tr class="volume-horaire" id="service-'.$service->getId().'-details"><td class="volume-horaire" colspan="'.$colspan.'">'.$this->getView()->volumeHoraireListe( $service->getVolumeHoraire(), array('service' => $service ) ).'</td></tr>';
        }
        $out .= '</table>'."\n";
        return $out;
    }

    /**
     *
     * @return Service[]
     */
    public function getServices()
    {
        return $this->services;
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
     * @param Service[] $services
     * @return self
     */
    public function setServices(array $services)
    {
        $this->services = $services;
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