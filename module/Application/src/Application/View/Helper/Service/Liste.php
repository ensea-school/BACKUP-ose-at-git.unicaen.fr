<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Aide de vue permettant d'afficher une liste de services
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * description
     *
     * @var boolean
     */
    protected $renderIntervenants = true;

    /**
     *
     * @var typeVolumeHoraire
     */
    protected $typeVolumeHoraire;


    public function getRenderIntervenants()
    {
        return $this->renderIntervenants;
    }

    public function setRenderIntervenants($renderIntervenants)
    {
        $this->renderIntervenants = $renderIntervenants;
        return $this;
    }

    /**
     *
     * @param \Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire
     */
    public function setTypeVolumeHoraire( \Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire )
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        return $this;
    }

    /**
     *
     * @return \Application\Entity\Db\TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        if (! $this->typeVolumeHoraire){ // par défaut
            $this->typeVolumeHoraire = $this->getServiceLocator()->getServiceLocator()->get('applicationTypeVolumeHoraire')->getPrevu();
        }
        return $this->typeVolumeHoraire;
    }

    /**
     * Helper entry point.
     *
     * @param Service[] $services
     * @return self
     */
    final public function __invoke( array $services )
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
    public function render( $details = false )
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();

        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
        $colspan = 4;
        $out = $this->renderShowHide();
        $out .= '<table id="services" class="table table-bordered service">';
        $out .= '<tr>';

        if ($this->getRenderIntervenants()) {
            $out .= "<th>Intervenant</th>\n";
            $out .= "<th title=\"Structure d'appartenance de l'intervenant\">Structure d'affectation</th>\n";
            $colspan += 2;
        }
        $out .= "<th title=\"Structure gestionnaire de l'enseignement\">Composante d'enseignement</th>\n";
        $out .= "<th title=\"Formation\">Formation</th>\n";
        $out .= "<th title=\">Enseignement\">Enseignement</th>\n";
        if ($role instanceof \Application\Acl\ComposanteDbRole) {
            $out .= "<th title=\"Formation ouverte à distance\">FOAD</th>\n";
            $out .= "<th title=\"Régime d'inscription\">Rég. d'insc.</th>\n";
        }
        if (!$context->getAnnee()) {
            $out .= "<th>Année univ.</th>\n";
            $colspan += 1;
        }
        foreach( $typesIntervention as $ti ){
            $colspan++;
            $out .= "<th class=\"heures\" style=\"width:8%\"><abbr title=\"".$ti->getLibelle()."\">".$ti->getCode()."</abbr></th>\n";
        }
        $out .= "<th>&nbsp;</th>\n";
        $out .= "<th>&nbsp;</th>\n";
        $out .= "<th>&nbsp;</th>\n";
        $out .= "</tr>\n";

        foreach( $this->services as $service ){
            $out .= $this->renderLigne($service, $details);
        }
        $out .= '<tfoot>'."\n";
        $out .= $this->renderTotaux();
        $out .= '</tfoot>'."\n";
        $out .= '</table>'."\n";
        $out .= $this->renderShowHide();
//
//        $url = $this->getView()->url('service/default', array('action' => 'saisie'));
//        $out .= '<br /><a class="ajax-modal services btn btn-default" data-event="service-add-message" href="'.$url.'" title="Ajouter un service"><span class="glyphicon glyphicon-plus"></span> Saisir un nouveau service</a>';
        $out .= '<script type="text/javascript">';
        $out .= '$(function() { Service.init("'.$this->getTypeVolumeHoraire()->getId().'");
            Service.setRenderIntervenants('.$this->getRenderIntervenants().')});';
        $out .= '</script>';
        return $out;
    }

    public function renderLigne( Service $service, $details=false )
    {
        if ($service->getTypeVolumeHoraire() !== $this->getTypeVolumeHoraire()){
            throw new \Common\Exception\LogicException('Le type de volume horaire du service ne correspond pas à celui de la liste');
        }

        $ligneView = $this->getView()->serviceLigne( $service )->setRenderIntervenants($this->getRenderIntervenants());
        $vhlView = $this->getView()->volumeHoraireListe( $service->getVolumeHoraireListe() );

        $out  = '<tr id="service-'.$service->getId().'-ligne" data-url="'.$ligneView->getRefreshUrl().'">';
        $out .= $ligneView->render($details);
        $out .= '</tr>';
        $out .= '<tr class="volume-horaire" id="service-'.$service->getId().'-volume-horaire-tr"'.($details ? '' : ' style="display:none"').'>'
                .'<td class="volume-horaire" id="service-'.$service->getId().'-volume-horaire-td" data-url="'.$vhlView->getRefreshUrl().'" colspan="999">'
                .$vhlView->render()
                .'</td>'
                .'</tr>';
        return $out;
    }

    protected function getTotaux()
    {
        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
        $data = ['total_general' => 0];
        foreach( $typesIntervention as $ti ){
            $data[$ti->getCode()] = 0;

            foreach( $this->getServices() as $service ){
                $h = $service->getVolumeHoraireListe()->setTypeVolumeHoraire($this->getTypeVolumehoraire())->setTypeIntervention($ti)->getHeures();
                $data[$ti->getCode()] += $h;
                $data['total_general'] += $h;
            }

        }
        return $data;
    }

    public function renderTotaux()
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
        $colspan = 3;

        if ($this->getRenderIntervenants()) $colspan += 2;
        if ($role instanceof \Application\Acl\ComposanteDbRole) $colspan += 2;
        if (!$context->getAnnee()) $colspan += 1;

        $data = $this->getTotaux();

        $url = $this->getView()->url(null, [],['query' => ['totaux' => 1]], true);
        $out = '<tr id="service_totaux" data-url="'.$url.'">';
        $out .= "<th colspan='$colspan' style=\"text-align:right\">Totaux par type d'intervention :</th>\n";
        foreach( $typesIntervention as $ti ){
            $colspan++;
            $out .= "<td id=\"".$ti->getCode()."\" style=\"text-align:right\">".\UnicaenApp\Util::formattedFloat($data[$ti->getCode()], \NumberFormatter::DECIMAL, -1)."</td>\n";
        }
        $colspan--;
        $out .= "<td colspan='3'>&nbsp;</td>\n";
        $out .= "</tr>\n";
        $out .= '<tr id="service_total_general">';
        $out .= "<th colspan=\"$colspan\" style=\"text-align:right\">Total des heures de service :</th>\n";
        $out .= "<td id=\"total_general\" style=\"text-align:right\">".\UnicaenApp\Util::formattedFloat($data['total_general'], \NumberFormatter::DECIMAL, -1)."</td>\n";
        $out .= "<td colspan='3'>&nbsp;</td>\n";
        $out .= "</tr>\n";
        return $out;
    }

    public function renderJsonTotaux()
    {
        $data = $this->getTotaux();
        foreach( $data as $index => $heures ){
            $data[$index] = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, -1);
        }
        return json_encode($data);
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
     * @param Service[] $services
     * @return self
     */
    public function setServices(array $services)
    {
        $this->services = $services;
        return $this;
    }

}