<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Entity\Db\Intervenant;

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
     * @var Intervenant
     */
    protected $intervenant;

    /**
     *
     * @var typeVolumeHoraire
     */
    protected $typeVolumeHoraire;


    /**
     *
     * @return Intervenant
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
    public function setIntervenant( Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
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

    public function getTotalRefreshUrl()
    {
        return $this->getView()->url(null, [],['query' => ['totaux' => 1]], true);
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

        if (! $this->getIntervenant()) {
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
        $out .= '<tfoot data-url="'.$this->getTotalRefreshUrl().'">'."\n";
        $out .= $this->renderTotaux();
        $out .= '</tfoot>'."\n";
        $out .= '</table>'."\n";
        $out .= $this->renderShowHide();
        $out .= '<script type="text/javascript">';
        $out .= '$(function() { Service.init("'.$this->getTypeVolumeHoraire()->getId().'");
            Service.setRenderIntervenants('.(! $this->getIntervenant() ? 'true' : 'false').')});';
        $out .= '</script>';
        return $out;
    }

    public function renderLigne( Service $service, $details=false )
    {
        if ($service->getTypeVolumeHoraire() !== $this->getTypeVolumeHoraire()){
            throw new \Common\Exception\LogicException('Le type de volume horaire du service ne correspond pas à celui de la liste');
        }

        $ligneView = $this->getView()->serviceLigne( $service )->setRenderIntervenants(! $this->getIntervenant());
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
        $data = [
                    'total_general' => 0,
                ];
        foreach( $typesIntervention as $ti ){
            $data[$ti->getCode()] = 0;

            foreach( $this->getServices() as $service ){
                $h = $service->getVolumeHoraireListe()->setTypeVolumeHoraire($this->getTypeVolumehoraire())->setTypeIntervention($ti)->getHeures();
                $data[$ti->getCode()] += $h;
                $data['total_general'] += $h;
            }

        }
        if ($this->getIntervenant()){
            $data['total_paye'] = $this->getFormuleHetd()->getServiceTotal($this->getIntervenant());
            $data['total_hetd'] = $this->getFormuleHetd()->getHetd($this->getIntervenant());
//            $data['total_compl'] = $this->getFormuleHetd()->getHeuresComplementaires($this->getIntervenant());
        }
        return $data;
    }

    public function renderTotaux()
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $typesIntervention = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
        $colspan = 3;

        if (! $this->getIntervenant()) $colspan += 2;
        if ($role instanceof \Application\Acl\ComposanteDbRole) $colspan += 2;
        if (!$context->getAnnee()) $colspan += 1;

        $data = $this->getTotaux();

        $out = '<tr>';
        $out .= "<th colspan='$colspan' style=\"text-align:right\">Totaux par type d'intervention :</th>\n";
        foreach( $typesIntervention as $ti ){
            $colspan++;
            $out .= "<td id=\"".$ti->getCode()."\" style=\"text-align:right\">".\UnicaenApp\Util::formattedFloat($data[$ti->getCode()], \NumberFormatter::DECIMAL, -1)."</td>\n";
        }
        $colspan--;
        $out .= "<td colspan='3'>&nbsp;</td>\n";
        $out .= "</tr>\n";
        $out .= '<tr>';
        $out .= "<th colspan=\"$colspan\" style=\"text-align:right\">Total des heures de service :</th>\n";
        $out .= "<td style=\"text-align:right\">".\UnicaenApp\Util::formattedFloat($data['total_general'], \NumberFormatter::DECIMAL, -1)."</td>\n";
        $out .= "<td colspan='3'>&nbsp;</td>\n";
        $out .= "</tr>\n";
        $title = [
            'Toutes structures confondues'
        ];
        if (isset($data['total_hetd'])){
            if ($data['total_paye'] != $data['total_general']){
                $title[] = 'Sur la base de '.\UnicaenApp\Util::formattedFloat($data['total_paye'], \NumberFormatter::DECIMAL, -1).' heures payées';
            }
            $out .= '<tr>';
            $out .= "<th colspan=\"$colspan\" style=\"text-align:right\">Total Heures &Eacute;quivalent TD :</th>\n";
            if (! empty($title)){
                $out .= "<td style=\"text-align:right\"><abbr title=\"".implode(",\n",$title).".\">".\UnicaenApp\Util::formattedFloat($data['total_hetd'], \NumberFormatter::DECIMAL, -1)."</abbr></td>\n";
            }else{
                $out .= "<td style=\"text-align:right\">".\UnicaenApp\Util::formattedFloat($data['total_hetd'], \NumberFormatter::DECIMAL, -1)."</td>\n";
            }
            $out .= "<td colspan='3'>&nbsp;</td>\n";
            $out .= "</tr>\n";
        }
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
     * @return \Application\Service\Process\FormuleHetd
     */
    public function getFormuleHetd()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('processFormuleHetd');
    }

}