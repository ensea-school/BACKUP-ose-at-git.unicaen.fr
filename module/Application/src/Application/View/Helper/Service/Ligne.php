<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

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
     * @var Service
     */
    protected $service;

    /**
     * Helper entry point.
     *
     * @param Service $service
     * @return self
     */
    final public function __invoke( Service $service )
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
    public function render( $details=false )
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        
        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
        $heures = $this->getServiceLocator()->getServiceLocator()->get('ApplicationService')->getTotalHeures($this->service);

        $sid = $this->service->getId();

        $out = '';
        if (!$role instanceof \Application\Acl\IntervenantRole) {
            $out .= '<td>'.$this->renderIntervenant($this->service->getIntervenant()).'</td>';
            if ($this->service->getIntervenant() instanceof Application\Entity\Db\IntervenantExterieur){
                $out .= '<td>'.$this->renderStructure( $this->service->getStructureAff() )."</td>\n";
            }else{
                $out .= "<td>&nbsp;</td>\n";
            }
            
        }
        if ($this->service->getEtablissement() === $context->getEtablissement()) {
            $out .= '<td>'.$this->renderStructure( $this->service->getStructureEns() )."</td>\n";
            $out .= '<td>'.$this->renderElementPedagogique( $this->service->getElementPedagogique() )."</td>\n";
        }else{
            $out .= '<td colspan="2">'.$this->renderEtablissement( $this->service->getEtablissement() )."</td>\n";
        }
        if (!$context->getAnnee()) {
            $out .= '<td>'.$this->renderAnnee( $this->service->getAnnee() )."</td>\n";
        }
        foreach( $typesIntervention as $ti ){
            $out .= $this->renderTypeIntervention( $ti, $heures );
        }

        $out .= $this->renderModifier();
        $out .= $this->renderSupprimer();
        $out .= $this->renderDetails( $details );
        return $out;
    }

    protected function renderIntervenant($intervenant)
    {
        $pourl = $this->getView()->url('intervenant/default', array('action' => 'apercevoir', 'id' => $intervenant->getSourceCode()));
        $out = '<a href="'.$pourl.'" data-po-href="'.$pourl.'" class="ajax-modal services">'.$intervenant.'</a>';
        return $out;
    }

    protected function renderStructure($structure)
    {
        if (! $structure) return '';

        $url = $this->getView()->url('structure/default', array('action' => 'voir', 'id' => $structure->getId()));
        $pourl = $this->getView()->url('structure/default', array('action' => 'apercevoir', 'id' => $structure->getId()));
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$structure.'</a>';
        return $out;
    }

    protected function renderElementPedagogique($element)
    {
        if (! $element) return '';
        $url = $this->getView()->url('of/element/voir', array('id' => $element->getId()));
        $pourl = $this->getView()->url('of/element/apercevoir', array('id' => $element->getId()));
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$element.'</a>';
        return $out;
    }

    protected function renderAnnee($annee)
    {
        $out = $annee->getLibelle();
        return $out;
    }

    protected function renderEtablissement($etablissement)
    {
        $url = $this->getView()->url('etablissement/default', array('action' => 'voir', 'id' => $etablissement->getId()));
        $pourl = $this->getView()->url('etablissement/default', array('action' => 'apercevoir', 'id' => $etablissement->getId()));
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$etablissement.'</a>';
        return $out;
    }

    protected function renderTypeIntervention( \Application\Entity\Db\TypeIntervention $typeIntervention, $heures )
    {
        $out = '<td id="service-'.$this->service->getId().'-ti-'.$typeIntervention->getId().'">'
                   .(array_key_exists($typeIntervention->getId(),$heures)
                        ? \UnicaenApp\Util::formattedFloat($heures[$typeIntervention->getId()], \NumberFormatter::DECIMAL, -1)
                        : ''
                    )
                   ."</td>\n";
        return $out;
    }

    protected function renderModifier()
    {
        $url = $this->getView()->url('service/default', array('action' => 'saisie', 'id' => $this->service->getId()));
        return '<td><a class="ajax-modal" data-event="service-modify-message" href="'.$url.'" title="Modifier le service"><span class="glyphicon glyphicon-edit"></span></a></td>';
    }

    protected function renderSupprimer()
    {
        $url = $this->getView()->url('service/default', array('action' => 'suppression', 'id' => $this->service->getId()));//onclick="return Service.get('.$this->service->getId().').delete(this)"
        return '<td><a class="ajax-modal service-delete" data-event="service-delete-message" data-id="'.$this->service->getId().'" href="'.$url.'" title="Supprimer le service"><span class="glyphicon glyphicon-remove"></span></a></td>';
    }

    protected function renderDetails( $details=false )
    {
        $out = '<td>'
              .'<a class="service-details-button" title="Détail des heures" onclick="Service.get('.$this->service->getId().').showHideDetails(this)">'
                  .'<span class="glyphicon glyphicon-chevron-'.($details ? 'up' : 'down').'"></span>'
              .'</a>'
              ."</td>\n";
        return $out;
    }

    /**
     *
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     *
     * @param Service $service
     * @return self
     */
    public function setService(Service $service)
    {
        $this->service = $service;
        return $this;
    }

}