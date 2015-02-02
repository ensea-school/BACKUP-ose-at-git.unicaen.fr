<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHtmlElement;
use Application\Entity\Db\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Entity\Db\TypeIntervention;
use Application\Interfaces\TypeVolumeHoraireAwareInterface;
use Application\Traits\TypeVolumeHoraireAwareTrait;

/**
 * Aide de vue permettant d'afficher une liste de services
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class
    Liste
extends
    AbstractHtmlElement
implements
    ServiceLocatorAwareInterface,
    ContextProviderAwareInterface,
    TypeVolumeHoraireAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    use TypeVolumeHoraireAwareTrait;

    /**
     *
     * @var string
     */
    private $id;

    /**
     *
     * @var array
     */
    private $totaux;

    /**
     *
     * @var array
     */
    private $typesInterventionVisibility = [];

    /**
     *
     * @var boolean
     */
    private $addButtonVisibility;

    /**
     *
     * @var array
     */
    private $columns = [
        'intervenant'           => [
            'visibility'    => false,
            'head-text'     => "<th>Intervenant</th>",
        ],
        'structure-aff'         => [
            'visibility'    => false,
            'head-text'     => "<th title=\"Structure d'appartenance de l'intervenant\">Structure d'affectation</th>",
        ],
        'structure-ens'         => [
            'visibility'    => true,
            'head-text'     => "<th title=\"Structure gestionnaire de l'enseignement\">Composante d'enseignement</th>",
        ],
        'formation'             => [
            'visibility'    => true,
            'head-text'     => "<th title=\"Formation\">Formation</th>",
        ],
        'enseignement'          => [
            'visibility'    => true,
            'head-text'     => "<th title=\">Enseignement\">Enseignement</th>",
        ],
        'foad'                  => [
            'visibility'    => false,
            'head-text'     => "<th title=\"Formation ouverte à distance\">FOAD</th>",
        ],
        'regimes-inscription'   => [
            'visibility'    => false,
            'head-text'     => "<th title=\"Régime d'inscription\">Rég. d'insc.</th>",
        ],
        'annee'                 => [
            'visibility'    => false,
            'head-text'     => "<th>Année univ.</th>",
        ],
    ];

    /**
     * Types d'intervention
     *
     * @var TypeIntervention[]
     */
    protected $typesIntervention;

    /**
     * Lecture seule ou non
     *
     * @var boolean
     */
    private $readOnly = false;



    /**
     * Helper entry point.
     *
     * @param Service[] $services
     * @return self
     */
    final public function __invoke($services)
    {
        $this->setServices($services);
        $this->calcDefaultColumnsVisibility();
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

    public function getAddUrl()
    {
        return $this->getView()->url('service/saisie', [], ['query' => ['type-volume-horaire' => $this->getTypeVolumeHoraire()->getId()]]);
    }

    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render( $details = false )
    {
        $this->totaux = [];
        $typesIntervention = $this->getTypesIntervention();
        $colspan = 2;

        $attribs = [
            'id'            => $this->getId(true),
            'class'         => 'service-liste',
            'data-params'   => json_encode( $this->exportParams() ),
        ];

        $out = '<div '.$this->htmlAttribs($attribs).'>';
        if ( count($this->getServices()) > 150 ){
            return $out.'<div class="alert alert-danger" role="alert">Le nombre de services à afficher est trop important. Merci d\'affiner vos critères de recherche.</div></div>';
        }
        if ($this->getAddButtonVisibility() && ! $this->getReadOnly()){
            $out .= $this->renderActionButtons();
        }
        $out .= $this->renderShowHide();

        $out .= '<table class="table table-condensed table-extra-condensed table-bordered service">';
        $out .= '<tr>';

        foreach( $this->getColumnsList() as $columnName ){
            if ($this->getColumnVisibility($columnName)) {
                $out .= $this->getColumnHeadText($columnName)."\n";
                $colspan ++;
            }
        }
        foreach( $typesIntervention as $ti ){
            $display = $this->getTypeInterventionVisibility($ti) ? '' : ';display:none';
            $colspan++;
            $out .= "<th class=\"heures type-intervention ".$ti->getCode()."\" style=\"width:8%$display\"><abbr title=\"".$ti->getLibelle()."\">".$ti->getCode()."</abbr></th>\n";
        }
        $out .= "<th>&nbsp;</th>\n";
        $out .= "</tr>\n";

        foreach( $this->services as $service ){
            $out .= $this->renderLigne($service, $details);
        }

        $style =  $this->getTotaux()['total_general'] == 0 ? ' style="display:none"' : '';
        $out .= '<tfoot data-url="'.$this->getTotalRefreshUrl().'"'.$style.'>'."\n";
        $out .= $this->renderTotaux();
        $out .= '</tfoot>'."\n";
        $out .= '</table>'."\n";
        $out .= $this->renderShowHide();
        $out .= '</div>'."\n";
        $out .= '<script type="text/javascript">';
        $out .= '$(function() { ServiceListe.get("'.$this->getId().'").init(); });';
        $out .= '</script>';
        return $out;
    }

    public function renderActionButtons()
    {
        $attribs = [
            'class'         => 'ajax-modal services btn btn-primary',
            'data-event'    => 'service-add-message',
            'href'          => $this->getAddUrl(),
            'title'         => 'Ajouter un nouvel enseignement',
        ];
        $out = '<a '.$this->htmlAttribs($attribs).'><span class="glyphicon glyphicon-plus"></span> Je saisis</a>';

        if ($this->isInRealise()){
            $attribs = [
                'class'         => 'btn btn-warning prevu-to-realise-show',
                'data-toggle'   => 'modal',
                'data-target'   => '#prevu-to-realise-modal',
                //'data-event'    => 'service-constatation',
                //'href'          => $this->getAddUrl(),
                'title'         => "Saisir comme réalisées l'ensemble des heures prévisionnelles"
                                  .". Attention toutefois : si des heures réalisées ont déjà été saisies alors ces dernières seront écrasées!",
            ];
            $out .= '&nbsp;<button type="button" '.$this->htmlAttribs($attribs).'>Prévu <span class="glyphicon glyphicon-arrow-right"></span> réalisé</button>';
            $out .= '<div class="modal fade" id="prevu-to-realise-modal" tabindex="-1" role="dialog" aria-hidden="true">';
            $out .= '<div class="modal-dialog modal-sm">';
            $out .= '<div class="modal-content">';
            $out .= '<div class="modal-header">';
            $out .= '<button type="button" class="close" data-dismiss="modal" aria-label="Annuler"><span aria-hidden="true">&times;</span></button>';
            $out .= '<h4 class="modal-title">Saisir comme réalisées l\'ensemble des heures prévisionnelles</h4>';
            $out .= '</div>';
            $out .= '<div class="modal-body">';
            $out .= '<p>Souhaitez-vous réellement saisir comme réalisées l\'ensemble des heures prévisionnelles ?</p>';
            $out .= '<p>Attention : si des heures réalisées ont déjà été saisies alors ces dernières seront écrasées!</p>';
            $out .= '</div>';
            $out .= '<div class="modal-footer">';
            $out .= '<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>';
            $out .= '<button type="button" class="btn btn-primary prevu-to-realise">OK</button>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
        }
        return $out;
    }

    public function renderLigne( Service $service, $details=false, $show=true )
    {
        $ligneView = $this->getView()->serviceLigne( $this, $service );

        $volumeHoraireListe = $this->getView()->volumeHoraireListe( $service->getVolumeHoraireListe() );
        /* @var $volumeHoraireListe \Application\View\Helper\VolumeHoraire\Liste */

        $attribs = [
            'id'        => 'service-'.$service->getId().'-ligne',
            'data-id'   => $service->getId(),
            'class'     => 'service-ligne',
            'data-url'  => $ligneView->getRefreshUrl(),
        ];
        if (! $show) $attribs['style'] = 'display:none';
        $out  = '<tr '.$this->htmlAttribs($attribs).'>';
        $out .= $ligneView->render($details);
        $out .= '</tr>';
        $out .= '<tr class="volume-horaire" id="service-'.$service->getId().'-volume-horaire-tr"'.($details ? '' : ' style="display:none"').'>';
        if ( $this->isInRealise() ){
            $out .= '<td class="volume-horaire" style="padding-left:5em" id="service-'.$service->getId().'-volume-horaire-td" colspan="999">';

            $volumeHoraireListe->getVolumeHoraireListe()->setTypeVolumeHoraire( $this->getServiceTypeVolumeHoraire()->getPrevu() );
            $volumeHoraireListe->getVolumeHoraireListe()->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getValide() );
            $volumeHoraireListe->setReadOnly(true);
            $out .= '<div style="float:left;width:15%"><h5>Prévisionnel :</h5></div>';
            $out .= '<div id="vhl-prev" style="width:85%" data-url="'.$volumeHoraireListe->getRefreshUrl().'">'.$volumeHoraireListe->render().'</div>';

            $volumeHoraireListe->getVolumeHoraireListe()->setTypeVolumeHoraire( $this->getTypeVolumeHoraire() );
            $volumeHoraireListe->getVolumeHoraireListe()->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getSaisi() );
            $volumeHoraireListe->setReadOnly( $this->getReadOnly() );
            $out .= '<div style="float:left;width:15%"><h5>Réalisé :</h5></div>';
            $out .= '<div id="vhl" style="width:85%" data-url="'.$volumeHoraireListe->getRefreshUrl().'">'.$volumeHoraireListe->render().'</div>';
        }else{
            $out .= '<td class="volume-horaire" style="padding-left:10em" id="service-'.$service->getId().'-volume-horaire-td" colspan="999">';

            $volumeHoraireListe->getVolumeHoraireListe()->setTypeVolumeHoraire( $this->getTypeVolumeHoraire() );
            $volumeHoraireListe->getVolumeHoraireListe()->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getSaisi() );
            $volumeHoraireListe->setReadOnly( $this->getReadOnly() );
            $out .= '<div id="vhl" data-url="'.$volumeHoraireListe->getRefreshUrl().'">'.$volumeHoraireListe->render().'</div>';
        }
        $out .= '</td>';
        $out .= '</tr>';
        return $out;
    }

    public function renderTotaux()
    {
        $typesIntervention = $this->getTypesIntervention();

        $colspan = 0;
        if ($this->getColumnVisibility('intervenant'        ))  $colspan ++;
        if ($this->getColumnVisibility('structure-aff'      ))  $colspan ++;
        if ($this->getColumnVisibility('structure-ens'      ))  $colspan ++;
        if ($this->getColumnVisibility('formation'          ))  $colspan ++;
        if ($this->getColumnVisibility('enseignement'       ))  $colspan ++;
        if ($this->getColumnVisibility('foad'               ))  $colspan ++;
        if ($this->getColumnVisibility('regimes-inscription'))  $colspan ++;
        if ($this->getColumnVisibility('annee'              ))  $colspan ++;

        $data = $this->getTotaux();

        $out = '<tr>';
        $out .= "<th colspan='$colspan' style=\"text-align:right\">Totaux par type d'intervention :</th>\n";
        $typesInterventionDisplayed = 0;
        foreach( $typesIntervention as $ti ){
            if ($this->getTypeInterventionVisibility($ti)){
                $display = '';
                $typesInterventionDisplayed++;
            }else{
                $display = ';display:none';
            }
            $out .= "<td id=\"".$ti->getCode()."\" class=\"type-intervention ".$ti->getCode()."\" style=\"text-align:right$display\">".\Common\Util::formattedHeures($data[$ti->getCode()])."</td>\n";
        }
        $out .= "<td>&nbsp;</td>\n";
        $out .= "</tr>\n";
        $out .= '<tr>';
        $out .= "<th colspan=\"$colspan\" style=\"text-align:right\">Total des heures de service :</th>\n";
        $out .= "<td id=\"total-general\" style=\"text-align:right\" colspan=\"".$typesInterventionDisplayed."\">".\Common\Util::formattedHeures($data['total_general'])."</td>\n";
        $out .= "<td>&nbsp;</td>\n";
        $out .= "</tr>\n";
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
     * Détermine si nous sommes en service réalisé ou non
     *
     * @return boolean
     */
    public function isInRealise()
    {
        return $this->getTypeVolumeHoraire()->getCode() === \Application\Entity\Db\TypeVolumeHoraire::CODE_REALISE;
    }


    /**
     * @return string
     */
    public function getId( $reNew=false )
    {
        if (null === $this->id || $reNew){
            $this->id = uniqid('service-liste-');
        }
        return $this->id;
    }

    protected function getTotaux()
    {
        if (! $this->totaux){
            $typesIntervention = $this->getTypesIntervention();
            $data = [
                        'total_general' => 0,
                    ];
            foreach( $typesIntervention as $ti ){
                $data[$ti->getCode()] = 0;

                foreach( $this->getServices() as $service ){
                    $h = $service->getVolumeHoraireListe()->setTypeVolumeHoraire($this->getTypeVolumehoraire())->setTypeIntervention($ti)->getHeures();
                    $data[$ti->getCode()] += $h;
                }

            }
            foreach( $this->getServices() as $service ){
                $data['total_general'] += $service->getVolumeHoraireListe()->setTypeVolumeHoraire($this->getTypeVolumehoraire())->setTypeIntervention(false)->getHeures();
            }
            $this->totaux = $data;
        }
        return $this->totaux;
    }

    /**
     * Retourne les paramètres de configuration du View Helper sous forme de tableau transformable en JSON
     *
     * @return array
     */
    public function exportParams()
    {
        $params = [
            'read-only'                     => $this->getReadOnly(),
            'type-volume-horaire'           => $this->getTypeVolumeHoraire()->getId(),
            'columns-visibility'            => [],
            'types-intervention-visibility' => [],
            'in-realise'                    => $this->isInRealise(),
        ];
        foreach( $this->getColumnsList() as $columnName ){
            $params['columns-visibility'][$columnName] = $this->getColumnVisibility($columnName);
        }
        foreach( $this->getTypesIntervention() as $typeIntervention ){
            $params['types-intervention-visibility'][$typeIntervention->getCode()] = $this->getTypeInterventionVisibility($typeIntervention);
        }

        return $params;
    }

    /**
     * Copnfigure le View Helper selon les paramètres transmis
     *
     * @param array $params
     * @return self
     */
    public function applyParams( array $params )
    {
        if (isset($params['read-only'])){
            $this->setReadOnly( filter_var( $params['read-only'], FILTER_VALIDATE_BOOLEAN) );
        }
        if (isset($params['type-volume-horaire'])){
            $this->setTypeVolumeHoraire( $this->getServiceTypeVolumeHoraire()->get( (int)$params['type-volume-horaire'] ) );
        }
        if (isset($params['columns-visibility']) && is_array($params['columns-visibility'])){
            foreach( $params['columns-visibility'] as $columnName => $visibility ){
                $this->setColumnVisibility( $columnName, filter_var( $visibility, FILTER_VALIDATE_BOOLEAN) );
            }
        }
        if (isset($params['types-intervention-visibility']) && is_array($params['types-intervention-visibility'])){
            foreach( $params['types-intervention-visibility'] as $typeInterventionCode => $visibility ){
                $this->setTypeInterventionVisibility( $typeInterventionCode, filter_var( $visibility, FILTER_VALIDATE_BOOLEAN) );
            }
        }
        return $this;
    }

    /**
     * Calcule la visibilité par défaut des colonnes en fonction des données transmises!!
     * 
     * @return \Application\View\Helper\Service\Liste
     */
    public function calcDefaultColumnsVisibility()
    {
        $services = $this->getServices();
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        // si plusieurs années différentes sont détectées alors on prévoit d'afficher la colonne année par défaut
        // si plusieurs intervenants différents alors on prévoit d'afficher la colonne intervenant par défaut
        $annee       = null;    $multiAnnees        = false;
        $intervenant = null;    $multiIntervenants  = false;
        foreach( $services as $service ){
            if (empty($annee)) $annee = $service->getAnnee();
            elseif( $annee !== $service->getAnnee() ){ $multiAnnees = true; break;}

            if (empty($intervenant)) $intervenant = $service->getIntervenant();
            elseif( $intervenant !== $service->getIntervenant() ){ $multiIntervenants = true; break;}
        }
        $this->setColumnVisibility( 'annee',                $multiAnnees        );
        $this->setColumnVisibility( 'intervenant',          $multiIntervenants  );
        $this->setColumnVisibility( 'structure-aff',        $multiIntervenants  );

        // si c'est une composante alors on affiche le détail pour l'enseignement
        $detailsEns = ! $role instanceof \Application\Acl\IntervenantRole; /** @todo associer ça à un paramètre... */
        $this->setColumnVisibility( 'foad',                 $detailsEns         );
        $this->setColumnVisibility( 'regimes-inscription',  $detailsEns         );

        return $this;
    }

    /**
     * @param TypeIntervention $typeIntervention
     * @return boolean
     */
    public function getTypeInterventionVisibility( TypeIntervention $typeIntervention )
    {
        if (isset($this->typesInterventionVisibility[$typeIntervention->getCode()])){ // visibilité déterminée
            return $this->typesInterventionVisibility[$typeIntervention->getCode()];
        }else{ // visibilité calculée
            $totaux = $this->getTotaux();
            return $typeIntervention->getVisible() || ( isset($totaux[$typeIntervention->getCode()]) && $totaux[$typeIntervention->getCode()] > 0 );
        }
    }

    /**
     * 
     * @param TypeIntervention|string $typeIntervention
     * @return self
     */
    public function setTypeInterventionVisibility( $typeIntervention, $visibility )
    {
        if ($typeIntervention instanceof TypeIntervention){
            $typeIntervention = $typeIntervention->getCode();
        }
        $this->typesInterventionVisibility[$typeIntervention] = (boolean)$visibility;
        return $this;
    }

    /**
     * @return @string[]
     */
    public function getColumnsList()
    {
        return array_keys( $this->columns );
    }

    /**
     *
     * @param string $columnName
     * @param boolean $visibility
     * @return self
     */
    public function setColumnVisibility( $columnName, $visibility )
    {
        $this->columns[$columnName]['visibility'] = (boolean)$visibility;
        return $this;
    }

    /**
     *
     * @param string $columnName
     * @return boolean
     */
    public function getColumnVisibility( $columnName )
    {
        if (! array_key_exists($columnName, $this->columns)){
            throw new \Common\Exception\LogicException('La colonne "'.$columnName.'" n\'existe pas.');
        }
        return $this->columns[$columnName]['visibility'];
    }

    /**
     *
     * @param string $columnName
     * @return string
     * @throws \Common\Exception\LogicException
     */
    public function getColumnHeadText( $columnName )
    {
        if (! array_key_exists($columnName, $this->columns)){
            throw new \Common\Exception\LogicException('La colonne "'.$columnName.'" n\'existe pas.');
        }
        return $this->columns[$columnName]['head-text'];
    }

    /**
     * @return TypeIntervention[]
     */
    public function getTypesIntervention()
    {
        if (! isset($this->typesIntervention)){
            $this->typesIntervention = $this->getServiceTypeIntervention()->getList();
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
     * Retourne le type de volume horaire concerné.
     *
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        if (empty($this->typeVolumeHoraire)){
            $this->typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        }
        return $this->typeVolumeHoraire;
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
     * @return boolean
     */
    function getAddButtonVisibility()
    {
        return $this->addButtonVisibility;
    }

    /**
     *
     * @param boolean $addButtonVisibility
     * @return self
     */
    function setAddButtonVisibility($addButtonVisibility)
    {
        $this->addButtonVisibility = $addButtonVisibility;
        return $this;
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
     *
     * @return \Application\Service\EtatVolumeHoraire
     */
    protected function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationEtatVolumeHoraire');
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
    }
}