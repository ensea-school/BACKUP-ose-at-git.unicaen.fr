<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Aide de vue permettant d'afficher une liste de services
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;


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
    public function render( $details = false ){
        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
        $colspan = 4;
        $out = $this->renderShowHide();
        $out .= '<table id="services" class="table service">';
        $out .= '<tr>';

        if (empty($this->context['intervenant'])){
            $out .= "<th colspan=\"2\">Intervenant</th>\n";
            $colspan += 2;
        }
        $out .= "<th>Structure</th>\n";
        $out .= "<th>Enseignement ou responsabilité</th>\n";
        if (empty($this->context['annee'])){
            $out .= "<th>Année univ.</th>\n";
            $colspan += 1;
        }
        foreach( $typesIntervention as $ti ){
            $colspan++;
            $out .= "<th style=\"width:8%\" title=\"".$ti->getLibelle()."\">".$ti->getCode()."</th>\n";
        }
        $out .= "<th>&nbsp;</th>\n";
        $out .= "<th>&nbsp;</th>\n";
        $out .= "</tr>\n";
        foreach( $this->services as $service ){
            $out .= $this->renderLigne($service, $details);
        }
        $out .= '</table>'."\n";
        $out .= $this->renderShowHide();

        $url = $this->getView()->url('service/default', array('action' => 'saisie'));
        $out .= '<br /><a class="modal-action event_service-add-message btn btn-primary" href="'.$url.'" title="Ajouter un service"><span class="glyphicon glyphicon-plus"></span> Saisir un nouveau service</a>';
        $out .= $this->getView()->modalAjaxDialog('service-div');
        $out .= '<script type="text/javascript">';
        $out .= '$(function() { Service.init("'.$this->getView()->url('service/default', array('action' => 'voirLigne') ).'"); });';
        $out .= '</script>';
        return $out;
    }

    public function renderLigne( Service $service, $details=false )
    {
        $url = $this->getView()->url('service/voirLigne', array('id' => $service->getId(), 'only-content' => 1));
        $detailsUrl = $this->getView()->url('volume-horaire/default', array('action' => 'liste', 'id' => $service->getId()));

        $out  = '<tr id="service-'.$service->getId().'-ligne" data-url="'.$url.'">';
        $out .= $this->getView()->serviceLigne( $service, $this->context )->render($details);
        $out .= '</tr>';
        $out .= '<tr class="volume-horaire" id="service-'.$service->getId().'-volume-horaire-tr"'.($details ? '' : ' style="display:none"').'>'
                .'<td class="volume-horaire" id="service-'.$service->getId().'-volume-horaire-td" data-url="'.$detailsUrl.'" colspan="999">'
                .$this->getView()->volumeHoraireListe( $service->getVolumeHoraire(), $service )->render()
                .'</td>'
                .'</tr>';
        return $out;
    }

    public function renderShowHide()
    {
        return
            '<div class="service-show-hide-buttons">'
            .'<button type="button" class="btn btn-default btn-xs service-show-all-details"><span class="glyphicon glyphicon-chevron-down"></span> Tout déplier</button> '
            .'<button type="button" class="btn btn-default btn-xs service-hide-all-details"><span class="glyphicon glyphicon-chevron-up"></span> Tout replier</button>'
           .'</div>';
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