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
 * Aide de vue permettant d'afficher une ligne de service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Ligne extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    
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
        $parts = array();
        
        $parts['intervenant'] = '<td>' . $this->service->getIntervenant() . "</td>\n";
        $parts[]              = '<td>' . $this->renderStructure($this->service->getStructure()) . "</td>\n";
        $parts['annee']       = '<td>' . $this->renderAnnee($this->service->getAnnee()) . "</td>\n";
        $parts[]              = '<td>' . $this->renderFonction($this->service->getFonction()) . "</td>\n";
        $parts[]              = '<td>' . $this->renderHeures($this->service->getHeures()) . "</td>\n";

        $parts[] = $this->renderModifier();
        $parts[] = $this->renderSupprimer();
        
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
        $query = array('sourceCode' => $this->service->getIntervenant()->getSourceCode());
        $url = $this->getView()->url('service-ref/default', array('action' => 'saisir'), array('query' => $query));
        return '<td><a class="ajax-modal" data-event="service-modify-message" href="' . $url . '" title="Modifier le service référentiel de ' . $this->service->getIntervenant() . '"><span class="glyphicon glyphicon-edit"></span></a></td>';
    }

    protected function renderSupprimer()
    {
        $url = $this->getView()->url('service-ref/default', array('action' => 'supprimer', 'id' => $this->service->getId()));
        return '<td><a class="ajax-modal" data-event="service-delete-message" data-id="' . $this->service->getId() . '" href="' . $url . '" title="Supprimer ce service référentiel"><span class="glyphicon glyphicon-remove"></span></a></td>';
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