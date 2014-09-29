<?php

namespace Application\View\Helper\ServiceReferentiel;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\ServiceReferentiel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use NumberFormatter;
use UnicaenApp\Util;

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
    public function render()
    {
        $urlVoirListe = $this->getView()->url('service-ref/default', array('action' => 'voirListe'));
        $parts        = array();
        
        $parts[]              = '<table id="services-ref" class="table service-ref">';
        $parts[]              = '<tr>';
        $parts['intervenant'] = "<th>Intervenant</th>";
        $parts[]              = "<th>Structure</th>";
        $parts[]              = "<th>Fonction référentielle</th>";
        $parts[]              = "<th>Commentaires</th>";
        $parts['annee']       = "<th>Année univ.</th>";
        $parts[]              = "<th>Heures</th>";
        $parts[]              = "<th class=\"action\" colspan=\"2\">&nbsp;</th>";
        $parts[]              = "</tr>";

        $total = 0;

        foreach ($this->services as $service) {
            $parts[] = '<tr id="service-ref-' . $service->getId() . '-ligne">';
            $parts[] = $this->renderLigne($service);
            $total += $service->getHeures();
            $parts[] = '</tr>';
        }
        $parts[] = '<tfoot>';
        $parts[] = '<th style="text-align:right" colspan="'.($this->getColumnsCount()-3).'">Total :</th>';
        $parts[] = '<td style="text-align:right;padding-right:2em">'.Util::formattedFloat($total, NumberFormatter::DECIMAL, -1).'</td>';
        $parts[] = "<td class=\"action\" colspan=\"2\">&nbsp;</td>";
        $parts[] = '</tfoot>';
        $parts[] = '</table>';
        
        $parts[] = '<script type="text/javascript">';
        $parts[] = '$(function() { ServiceReferentiel.init("' . $urlVoirListe . '"); });';
        $parts[] = '</script>';
        
        $this->applyGlobalContext($parts);
        
        return implode(PHP_EOL, $parts);
    }
    
    protected function renderLigne($service)
    {
        $helper = $this->getView()->serviceReferentielLigne($service); /* @var $helper Ligne */
        $helper->setRenderIntervenants($this->getRenderIntervenants());
        
        return $helper->render();
    }

    /**
     *
     * @return integer
     */
    private function getColumnsCount()
    {
        $count = 8;
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();

        if (!$this->getRenderIntervenants()) {
            $count--;
        }
        if ($context->getAnnee()) {
            $count--;
        }

        return $count;
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
        
        if (!$this->getRenderIntervenants()) {
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
    
    /**
     * @var boolean
     */
    protected $renderIntervenants = true;

    public function getRenderIntervenants()
    {
        return $this->renderIntervenants;
    }

    public function setRenderIntervenants($renderIntervenants)
    {
        $this->renderIntervenants = $renderIntervenants;
        return $this;
    }
}