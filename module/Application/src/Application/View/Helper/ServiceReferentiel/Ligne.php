<?php

namespace Application\View\Helper\ServiceReferentiel;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\ServiceReferentiel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\Context;
use Application\Service\ContextAwareInterface;
use Application\Service\ContextAwareTrait;

/**
 * Aide de vue permettant d'afficher une ligne de service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Ligne extends AbstractHelper implements ServiceLocatorAwareInterface, ContextAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextAwareTrait;
    
    /**
     * @var ServiceReferentiel
     */
    protected $service;

    /**
     * Helper entry point.
     *
     * @param ServiceReferentiel $service
     * @return self
     */
    final public function __invoke(ServiceReferentiel $service)
    {
        $this->service = $service;
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
     * @param boolean $details
     * @return string
     */
    public function render($details = false)
    {
//        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
//        $heures = $this->getServiceLocator()->getServiceLocator()->get('ApplicationService')->getTotalHeures($this->service);

        $out = '';
        if (!$this->getContext()->getIntervenant()) {
            $out .= '<td>' . $this->service->getIntervenant() . "</td>\n";
        }
        if (!$this->getContext()->getStructure()) {
            $out .= '<td>' . $this->renderStructure($this->service->getStructure()) . "</td>\n";
        }
        if (!$this->getContext()->getAnnee()) {
            $out .= '<td>' . $this->renderAnnee($this->service->getAnnee()) . "</td>\n";
        }
        $out .= '<td>' . $this->renderFonction($this->service->getFonction()) . "</td>\n";
        $out .= '<td>' . $this->renderHeures($this->service->getHeures()) . "</td>\n";

//        $out .= $this->renderModifier();
//        $out .= $this->renderSupprimer();
//        $out .= $this->renderDetails($details);
        
        return $out;
    }

    protected function renderStructure($structure)
    {
        if (!$structure) {
            return '';
        }

        $url   = $this->getView()->url('structure/default', array('action' => 'voir', 'id' => $structure->getId()));
        $pourl = $this->getView()->url('structure/default', array('action' => 'apercevoir', 'id' => $structure->getId()));
        $out   = '<a data-poload="/ose/test" href="' . $url . '" data-po-href="' . $pourl . '" class="ajax-modal">' . $structure . '</a>';

        return $out;
    }

    protected function renderFonction($fonction)
    {
        if (!$fonction) {
            return '';
        }
        
        $out = "" . $fonction;

        return $out;
    }

    protected function renderAnnee($annee)
    {
        $out = "" . $annee;
        
        return $out;
    }

    protected function renderHeures($heures)
    {
        $out = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, -1);
        
        return $out;
    }

    protected function renderModifier()
    {
        $url = $this->getView()->url('service-ref/default', array('action' => 'saisie', 'id' => $this->service->getId()));
        return '<td><a class="ajax-modal event_service-modify-message" href="' . $url . '" title="Modifier le service référentiel"><span class="glyphicon glyphicon-edit"></span></a></td>';
    }

    protected function renderSupprimer()
    {
        $url = $this->getView()->url('service-ref/default', array('action' => 'suppression', 'id' => $this->service->getId())); //onclick="return ServiceReferentiel.get('.$this->service->getId().').delete(this)"
        return '<td><a class="service-delete" data-id="' . $this->service->getId() . '" href="' . $url . '" title="Supprimer le service référentiel"><span class="glyphicon glyphicon-remove"></span></a></td>';
    }

    protected function renderDetails($details = false)
    {
        $out = '<td>'
                . '<a class="service-details-button" title="Détails" onclick="ServiceReferentiel.get(' . $this->service->getId() . ').showHideDetails(this)">'
                . '<span class="glyphicon glyphicon-chevron-' . ($details ? 'up' : 'down') . '"></span>'
                . '</a>'
                . "</td>\n";
        return $out;
    }

    /**
     *
     * @return ServiceReferentiel
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     *
     * @param ServiceReferentiel $service
     * @return self
     */
    public function setService(ServiceReferentiel $service)
    {
        $this->service = $service;
        return $this;
    }
}