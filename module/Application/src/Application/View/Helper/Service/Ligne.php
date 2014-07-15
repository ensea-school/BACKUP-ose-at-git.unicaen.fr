<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;

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
     * description
     *
     * @var Intervenant|boolean
     */
    protected $intervenant;

    /**
     * description
     *
     * @var Structure|boolean
     */
    protected $structure;

    /**
     * readOnly
     *
     * @var boolean
     */
    protected $readOnly;

    /**
     * forcedReadOnly
     *
     * @var boolean
     */
    protected $forcedReadOnly = false;



    /**
     *
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->readOnly || $this->forcedReadOnly;
    }

    /**
     *
     * @param boolean $readOnly
     * @return self
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     *
     * @return Intervenant|boolean
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     *
     * @param Intervenant $intervenant
     * @return self
     */
    public function setIntervenant( $intervenant)
    {
        if (is_bool($intervenant) || $intervenant === null || $intervenant instanceof Intervenant){
            $this->intervenant = $intervenant;
        }else{
            throw new \Common\Exception\LogicException('La valeur transmise pour Intervenant n\'est pas correcte');
        }
        return $this;
    }

    /**
     *
     * @return boolean
     */
    protected function mustRenderIntervenant()
    {
        return $this->intervenant === null || $this->intervenant === false;
    }

    /**
     *
     * @return Structure|boolean
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     *
     * @param Structure $structure
     * @return self
     */
    public function setStructure( $structure )
    {
        if (is_bool($structure) || $structure === null || $structure instanceof Structure){
            $this->structure = $structure;
        }else{
            throw new \Common\Exception\LogicException('La valeur transmise pour Structure n\'est pas correcte');
        }
        return $this;
    }

    /**
     *
     * @return boolean
     */
    protected function mustRenderStructure()
    {
        return $this->structure === null || $this->structure === false;
    }

    protected function toQuery($param)
    {
        if (null === $param) return null;
        elseif (false === $param) return 'false';
        elseif( true === $param) return 'true';
        elseif(method_exists($param, 'getId')) return $param->getId();
        else throw new \Common\Exception\LogicException('Le paramètre n\'est pas du bon type');
    }

    /**
     * Helper entry point.
     *
     * @param Service $service
     * @return self
     */
    final public function __invoke( Service $service )
    {
        if (! $service->getTypeVolumeHoraire() instanceof TypeVolumeHoraire){
            throw new \Common\Exception\LogicException('Le type de volume horaire doit être précisé au niveau du service');
        }
        $this->setService( $service );
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
     * @return string
     */
    public function getRefreshUrl()
    {
        $url = $this->getView()->url(
                'service/rafraichir-ligne',
                [
                    'service' => $this->service->getId(),
                    'typeVolumeHoraire' => $this->service->getTypeVolumehoraire()->getId()
                ],
                ['query' => [
                    'only-content'  => 1,
                    'read-only'     => $this->getReadOnly() ? '1' : '0',
                    'intervenant'   => $this->toQuery($this->getIntervenant()),
                    'structure'     => $this->toQuery($this->getStructure()),
                ]]);
        return $url;
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
        $vhl     = $this->service->getVolumeHoraireListe();

        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
        $serviceService = $this->getServiceLocator()->getServiceLocator()->get('ApplicationService');

        $periode = $serviceService->getPeriode( $this->service );
        if ($periode){
            $vhl->setPeriode($periode);
        }

        $out = '';
        if ($this->mustRenderIntervenant()) {
            $out .= '<td>'.$this->renderIntervenant($this->service->getIntervenant()).'</td>';
            if ($this->service->getIntervenant() instanceof \Application\Entity\Db\IntervenantPermanent){
                $out .= '<td>'.$this->renderStructure( $this->service->getStructureAff() )."</td>\n";
            } else {
                $out .= "<td>&nbsp;</td>\n";
            }

        }
        if ($this->service->getEtablissement() === $context->getEtablissement()) {
            if ($this->mustRenderStructure()){
                $out .= '<td>'.$this->renderStructure($this->service->getStructureEns())."</td>\n";
            }
            $out .= '<td>'.$this->renderEtape($this->service->getElementPedagogique()->getEtape())."</td>\n";
            $out .= '<td>'.$this->renderElementPedagogique($this->service->getElementPedagogique())."</td>\n";
            if ($role instanceof \Application\Acl\ComposanteDbRole) {
                $out .= '<td>'.$this->renderFOAD($this->service->getElementPedagogique())."</td>\n";
                $out .= '<td>'.$this->renderRegimeInscription($this->service->getElementPedagogique())."</td>\n";
            }
        }else{
            $colspan = 2;
            if ($this->mustRenderStructure()){
                $colspan++;
            }
            if ($role instanceof \Application\Acl\ComposanteDbRole) {
                $colspan += 2;
            }
            $out .= '<td colspan="'.$colspan.'">'.$this->renderEtablissement( $this->service->getEtablissement() )."</td>\n";
        }
        if (!$context->getAnnee()) {
            $out .= '<td>'.$this->renderAnnee( $this->service->getAnnee() )."</td>\n";
        }
        foreach( $typesIntervention as $ti ){
            $out .= $this->renderTypeIntervention( $vhl->setTypeIntervention($ti) );
        }

        $out .= '<td class="actions">';
        if (! $this->getReadOnly()){
            $out .= $this->renderModifier();
            $out .= $this->renderSupprimer();
        }
        $out .= $this->renderDetails( $details );
        $out .= '</td>';
        return $out;
    }

    protected function renderIntervenant($intervenant)
    {
        $pourl = $this->getView()->url('intervenant/default', array('action' => 'voir', 'intervenant' => $intervenant->getSourceCode()));
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

    protected function renderEtape($etape)
    {
        if (! $etape) return '';
        $url = $this->getView()->url('of/etape/apercevoir', array('id' => $etape->getId()));
        $pourl = $this->getView()->url('of/etape/apercevoir', array('id' => $etape->getId()));
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$etape.'</a>';
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

    protected function renderFOAD($element)
    {
        if (! $element) return '';
        $out = (bool)$element->getTauxFoad() ? "Oui" : "Non";
        return $out;
    }

    protected function renderRegimeInscription($element)
    {
        if (! $element) return '';
        return $element->getHtmlRegimeInscription();
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

    protected function renderTypeIntervention( \Application\Entity\VolumeHoraireListe $liste )
    {
        $liste = $liste->setMotifNonPaiement(false);
        $out = '<td class="heures" style="text-align:right" id="service-'.$liste->getService()->getId().'-ti-'.$liste->getTypeIntervention()->getId().'">';
        //$out .= $this->getView()->volumeHoraireListe($liste)->renderHeures($liste);
        $out .= \UnicaenApp\Util::formattedFloat($liste->getHeures(), \NumberFormatter::DECIMAL, -1);
        $out .= "</td>\n";
        return $out;
    }

    protected function renderModifier()
    {
        $url = $this->getView()->url('service/default', array('action' => 'saisie', 'id' => $this->service->getId()));
        return '<a class="ajax-modal" data-event="service-modify-message" href="'.$url.'" title="Modifier l\'enseignement"><span class="glyphicon glyphicon-edit"></span></a>';
    }

    protected function renderSupprimer()
    {
        $url = $this->getView()->url('service/default', array('action' => 'suppression', 'id' => $this->service->getId()));//onclick="return Service.get('.$this->service->getId().').delete(this)"
        return '<a class="ajax-modal service-delete" data-event="service-delete-message" data-id="'.$this->service->getId().'" href="'.$url.'" title="Supprimer l\'enseignement"><span class="glyphicon glyphicon-remove"></span></a>';
    }

    protected function renderDetails( $details=false )
    {
        $out = 
              '<a class="service-details-button" title="Détail des heures" onclick="Service.get('.$this->service->getId().').showHideDetails(this)">'
                  .'<span class="glyphicon glyphicon-chevron-'.($details ? 'up' : 'down').'"></span>'
              .'</a>';
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
        $this->forcedReadOnly = ! $this->getServiceLocator()->getServicelocator()->get('applicationService')->canModify($service);
        $this->service = $service;
        return $this;
    }

}