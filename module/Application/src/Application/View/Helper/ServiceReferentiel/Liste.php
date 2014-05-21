<?php

namespace Application\View\Helper\ServiceReferentiel;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\ServiceReferentiel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Acl\IntervenantRole;

/**
 * Aide de vue permettant d'afficher une liste de services referentiels
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

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
//        $urlSaisir    = $this->getView()->url('service-ref/default', array('action' => 'saisir'));
        $urlVoirListe = $this->getView()->url('service-ref/default', array('action' => 'voirListe'));
        $parts        = array();
        
        $parts[]              = '<table id="services-ref" class="table service-ref">';
        $parts[]              = '<tr>';
        $parts['intervenant'] = "<th>Intervenant</th>";
        $parts[]              = "<th>Structure</th>";
        $parts[]              = "<th>Fonction référentielle</th>";
        $parts['annee']       = "<th>Année univ.</th>";
        $parts[]              = "<th>Heures</th>";
        $parts[]              = "<th class=\"action\" colspan=\"2\">&nbsp;</th>";
        $parts[]              = "</tr>";

        foreach ($this->services as $service) {
            $parts[] = '<tr id="service-ref-' . $service->getId() . '-ligne">';
            $parts[] = $this->getView()->serviceReferentielLigne($service)->render($details);
            $parts[] = '</tr>';
        }
        
        $parts[] = '</table>';

//        $parts[] = '<a class="ajax-modal services-ref btn btn-default" data-event="service-ref-add-message" href="' . $urlSaisir . '" title="Ajouter un service référentiel"><span class="glyphicon glyphicon-plus"></span> Saisir un nouveau service</a>';
        
        $parts[] = '<script type="text/javascript">';
        $parts[] = '$(function() { ServiceReferentiel.init("' . $urlVoirListe . '"); });';
        $parts[] = '</script>';
        
        $this->applyGlobalContext($parts);
        
        return implode(PHP_EOL, $parts);
    }

    /**
     * 
     * @param array $parts
     * @return self
     */
    public function applyGlobalContext(array &$parts)
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof IntervenantRole) {
            unset($parts['intervenant']);
        }
        if ($context->getAnnee()) {
            unset($parts['annee']);
        }
        
        return $this;
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