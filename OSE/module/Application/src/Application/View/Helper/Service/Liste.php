<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHtmlElement;
use Application\Entity\Db\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervention;

/**
 * Aide de vue permettant d'afficher une liste de services
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHtmlElement implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * @var Intervenant|boolean
     */
    protected $intervenant;
    protected $renderStructure = true;


    public function getRenderStructure()
    {
        return $this->renderStructure;
    }

    public function setRenderStructure($renderStructure)
    {
        $this->renderStructure = $renderStructure;
        return $this;
    }

    /**
     * description
     *
     * @var Structure|boolean
     */
    protected $structure;

    /**
     *
     * @var typeVolumeHoraire
     */
    protected $typeVolumeHoraire;

    /**
     * Lecture seule ou non
     *
     * @var boolean
     */
    protected $readOnly;

    /**
     * Types d'intervention
     *
     * @var TypeIntervention[]
     */
    protected $typesIntervention;



    /**
     * @return TypeIntervention[]
     */
    public function getTypesIntervention()
    {
        if (! isset($this->typesIntervention) && isset($this->services)){
            $this->typesIntervention = $this->getServiceTypeIntervention()->getTypesIntervention();
            //$this->typesIntervention = $this->getServiceService()->getTypesIntervention($this->services); // par défaut
        }
        return $this->typesIntervention;
    }

    /**
     * @param TypeIntervention[] $typesIntervention
     * @return self
     */
    public function setTypesIntervention($typesIntervention)
    {
        $this->typesIntervention = $typesIntervention;
        return $this;
    }


    /**
     *
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->readOnly;
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
            $this->typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        }
        return $this->typeVolumeHoraire;
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
     * @param Service[] $services
     * @return self
     */
    final public function __invoke($services)
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

        $typesIntervention = $this->getTypesIntervention();
        $colspan = 3;
        $out = $this->renderShowHide();

        $attribs = $this->htmlAttribs([
            'id'                        => 'services',
            'class'                     => 'table table-bordered service',
            'data-intervenant'          => $this->toQuery( $this->getIntervenant() ),
            'data-structure'            => $this->toQuery( $this->getStructure() ),
            'data-type-volume-horaire'  => $this->getTypeVolumeHoraire()->getId(),
        ]);
        $out .= '<table '.$attribs.'>';
        $out .= '<tr>';

        if ($this->mustRenderIntervenant()) {
            $out .= "<th>Intervenant</th>\n";
            $out .= "<th title=\"Structure d'appartenance de l'intervenant\">Structure d'affectation</th>\n";
            $colspan += 2;
        }
        if ($this->mustRenderStructure()){
            $out .= "<th title=\"Structure gestionnaire de l'enseignement\">Composante d'enseignement</th>\n";
            $colspan++;
        }
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
        $out .= '$(function() { Service.init(); });';
        $out .= '</script>';
        return $out;
    }

    public function renderLigne( Service $service, $details=false )
    {
        if ($service->getTypeVolumeHoraire() !== $this->getTypeVolumeHoraire()){
            throw new \Common\Exception\LogicException('Le type de volume horaire du service ne correspond pas à celui de la liste');
        }

        $ligneView = $this
                        ->getView()
                        ->serviceLigne( $service )
                        ->setIntervenant($this->getIntervenant())
                        ->setReadOnly($this->getReadOnly())
                        ->setTypesIntervention($this->getTypesIntervention())
                        ->setStructure($this->getStructure());
        $vhlView = $this->getView()->volumeHoraireListe( $service->getVolumeHoraireListe() );
        $vhlView->setReadOnly($this->getReadOnly());

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
        $typesIntervention = $this->getTypesIntervention();
        $data = [
                    'total_general' => 0,
                ];
        foreach( $typesIntervention as $ti ){
            $data[$ti->getCode()] = 0;

            foreach( $this->getServices() as $service ){
                $h = $service->getVolumeHoraireListe()->setTypeVolumeHoraire($this->getTypeVolumehoraire())->setTypeIntervention($ti)->getHeures();
                $data[$ti->getCode()] += $h;
                //$data['total_general'] += $h;
            }

        }
        foreach( $this->getServices() as $service ){
            $data['total_general'] += $service->getVolumeHoraireListe()->setTypeIntervention(false)->getHeures();
        }
        if ($this->getIntervenant() instanceof Intervenant){
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
        $typesIntervention = $this->getTypesIntervention();
        $colspan = 2;

        if ($this->mustRenderIntervenant()) $colspan += 2;
        if ($this->mustRenderStructure()) $colspan++;
        if ($role instanceof \Application\Acl\ComposanteDbRole) $colspan += 2;
        if (!$context->getAnnee()) $colspan += 1;

        $data = $this->getTotaux();

        $out = '<tr>';
        $out .= "<th colspan='$colspan' style=\"text-align:right\">Totaux par type d'intervention :</th>\n";
        foreach( $typesIntervention as $ti ){
            $out .= "<td id=\"".$ti->getCode()."\" style=\"text-align:right\">".\UnicaenApp\Util::formattedFloat($data[$ti->getCode()], \NumberFormatter::DECIMAL, -1)."</td>\n";
        }
        $out .= "<td>&nbsp;</td>\n";
        $out .= "</tr>\n";
        $out .= '<tr>';
        $out .= "<th colspan=\"$colspan\" style=\"text-align:right\">Total des heures de service :</th>\n";
        $out .= "<td style=\"text-align:right\" colspan=\"".count($typesIntervention)."\">".\UnicaenApp\Util::formattedFloat($data['total_general'], \NumberFormatter::DECIMAL, -1)."</td>\n";
        $out .= "<td>&nbsp;</td>\n";
        $out .= "</tr>\n";
        $title = [
            'Toutes structures confondues'
        ];
        if (isset($data['total_hetd'])){
            if ($data['total_paye'] != $data['total_general']){
                $title[] = 'Sur la base de '.\UnicaenApp\Util::formattedFloat($data['total_paye'], \NumberFormatter::DECIMAL, -1).' heures payables';
            }
            $out .= '<tr>';
            $out .= "<th colspan=\"$colspan\" style=\"text-align:right\">Total Heures &Eacute;quivalent TD :</th>\n";
            if (! empty($title)){
                $out .= "<td style=\"text-align:right\" colspan=\"".count($typesIntervention)."\"><abbr title=\"".implode(",\n",$title).".\">".\UnicaenApp\Util::formattedFloat($data['total_hetd'], \NumberFormatter::DECIMAL, -1)."</abbr></td>\n";
            }else{
                $out .= "<td style=\"text-align:right\" colspan=\"".count($typesIntervention)."\">".\UnicaenApp\Util::formattedFloat($data['total_hetd'], \NumberFormatter::DECIMAL, -1)."</td>\n";
            }
            $out .= "<td>&nbsp;</td>\n";
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
    protected function getFormuleHetd()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('processFormuleHetd');
    }

    /**
     * @return \Application\Service\Service
     */
    protected function getServiceService()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationService');
    }

    /**
     *
     * @return \Application\Service\TypeVolumeHoraire
     */
    protected function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeVolumeHoraire');
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
    }
}

