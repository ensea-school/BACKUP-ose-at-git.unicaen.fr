<?php

namespace Application\View\Helper\ServiceReferentiel;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\ServiceReferentiel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextAwareInterface;
use Application\Service\ContextAwareTrait;

/**
 * Aide de vue permettant d'afficher une liste de services referentiels
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper implements ServiceLocatorAwareInterface, ContextAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextAwareTrait;

    protected $services;
    
    /**
     * Helper entry point.
     *
     * @param ServiceReferentiel[] $services
     * @param array $context
     * @return self
     */
    final public function __invoke(array $services)
    {
        $this->services = $services;
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
    public function render($details = false)
    {
//        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();

        $colspan = 4;

        $out = '';
//        $out .= $this->renderShowHide();
        $out .= '<table id="services-ref" class="table service-ref-ref">';
        $out .= '<tr>';

        if (!$this->getContext()->getIntervenant()) {
            $out .= "<th colspan=\"2\">Intervenant</th>\n";
            $colspan += 2;
        }
        $out .= "<th>Structure</th>\n";
        $out .= "<th>Fonction référentielle</th>\n";
        if (!$this->getContext()->getAnnee()) {
            $out .= "<th>Année univ.</th>\n";
            $colspan += 1;
        }
        $out .= "<th>Heures</th>\n";
        $out .= "</tr>\n";

        foreach ($this->services as $service) {
            $out .= '<tr id="service-ref-ref-' . $service->getId() . '-ligne">';
            $out .= $this->getView()->serviceReferentielLigne($service)->render($details);
            $out .= '</tr>';
//            $out .= '<tr class="volume-horaire" id="service-ref-' . $service->getId() . '-volume-horaire-tr"' . ($details ? '' : ' style="display:none"') . '>'
//                    . '<td class="volume-horaire" id="service-ref-' . $service->getId() . '-volume-horaire-td" colspan="' . $colspan . '">'
//                    . $this->getView()->volumeHoraireListe($service->getVolumeHoraire(), array('service' => $service))->render()
//                    . '</td>'
//                    . '</tr>';
        }
        $out .= '</table>' . "\n";
//        $out .= $this->renderShowHide();

        $url = $this->getView()->url('service-ref/default', array('action' => 'saisir'));
        $out .= '<br /><a class="modal-action services-ref event_service-ref-add-message btn btn-default" href="' . $url . '" title="Modifier le service référentiel"><span class="glyphicon glyphicon-edit"></span> Modifier le service référentiel</a>';
        $out .= $this->getView()->modalAjaxDialog('service-ref-div', null, 'services-ref');
        $out .= '<script type="text/javascript">';
        $out .= '$(function() { ServiceReferentiel.init("' . $this->getView()->url('service-ref/default', array('action' => 'voirListe')) . '"); });';
        $out .= '</script>';
        return $out;
    }

    public function renderShowHide()
    {
        return
                '<div class="service-ref-show-hide-buttons">'
                . '<button type="button" class="btn btn-default btn-xs service-ref-show-all-details"><span class="glyphicon glyphicon-chevron-down"></span> Tout déplier</button> '
                . '<button type="button" class="btn btn-default btn-xs service-ref-hide-all-details"><span class="glyphicon glyphicon-chevron-up"></span> Tout replier</button>'
                . '</div>';
    }

    /**
     *
     * @return ServiceReferentiel[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     *
     * @param ServiceReferentiel[] $services
     * @return self
     */
    public function setServices(array $services)
    {
        $this->services = $services;
        return $this;
    }
}