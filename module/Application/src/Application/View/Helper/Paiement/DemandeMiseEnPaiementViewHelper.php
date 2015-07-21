<?php

namespace Application\View\Helper\Paiement;

use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\DomaineFonctionnelAwareTrait;
use Application\Service\Traits\EtablissementAwareTrait;
use Application\Service\Traits\TypeHeuresAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\ServiceAPayerInterface;
use Application\Entity\Db\FormuleResultatService;
use Application\Entity\Db\FormuleResultatServiceReferentiel;
use Application\Entity\Db\TypeHeures;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Db\DomaineFonctionnel;

/**
 * Description of DemandeMiseEnPaiementViewHelper
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class DemandeMiseEnPaiementViewHelper extends AbstractHtmlElement implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use DomaineFonctionnelAwareTrait;
    use TypeHeuresAwareTrait;
    use ContextAwareTrait;

    private $servicesAPayer = [];

    /**
     *
     * @var \Zend\Form\Form
     */
    private $form;

    private static $miseEnPaiementListeIdSequence = 1;

    /**
     * Mise lecture seule
     *
     * @var boolean
     */
    private $readOnly = false;

    /**
     * Liste des domaines fonctionnels
     *
     * @var array
     */
    protected $domainesFonctionnels;



    /**
     * Helper entry point.
     *
     * @param ServiceAPayerInterface[] $servicesAPayer
     * @return self
     */
    final public function __invoke( array $servicesAPayer )
    {
        $this->setServicesAPayer($servicesAPayer);
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
     *
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        if (null === $this->form){
            $this->form = new \Zend\Form\Form;
            $this->form->add( new \Zend\Form\Element\Hidden('changements') );
            $this->form->add([
                'name' => 'submit',
                'type'  => 'Submit',
                'attributes' => [
                    'value' => 'Enregistrer les demandes de paiement',
                    'class' => 'btn btn-primary sauvegarde',
                ],
            ]);

            $this->form->setAttribute('action', $this->getView()->url(null, [], [], true));
        }
        return $this->form;
    }



    public function render()
    {
        $servicesAPayer = $this->getServicesAPayer();
        $attrs = [
            'id'            => $this->getId(),
            'class'         => 'demande-mise-en-paiement',
            'data-params'   => json_encode($this->getParams())
        ];

        $out = '<div '.$this->htmlAttribs($attrs).'>';
        if ( (!$this->getReadOnly()) && $this->getView()->isAllowed('privilege/'.\Application\Entity\Db\Privilege::MISE_EN_PAIEMENT_DEMANDE) ){
            $out .= '<div style="padding-bottom:1em"><button type="button" class="btn btn-default toutes-heures-non-dmep">Demander le paiement de toutes les HETD</button></div>';
        }
        foreach( $servicesAPayer as $serviceAPayer ){
            $out .= $this->renderServiceAPayer($serviceAPayer);
        }
        if (! $this->getReadOnly() && $this->getView()->isAllowed('privilege/'.\Application\Entity\Db\Privilege::MISE_EN_PAIEMENT_DEMANDE)){
            $out .= '<div>';
            $out .= $this->getView()->form()->openTag($this->getForm());
            $out .= $this->getView()->formHidden($this->getForm()->get('changements'));
            $out .= $this->getView()->formRow($this->getForm()->get('submit'));
            $out .= $this->getView()->form()->closeTag();
            $out .= '</div>';
        }
        $out .= '</div>';
        $out .= '<script type="text/javascript">';
        $out .= '$(function() { DemandeMiseEnPaiement.get("'.$this->getId().'").init(); });';
        $out .= '</script>';

        return $out;
    }

    public function renderServiceAPayer( ServiceAPayerInterface $serviceAPayer )
    {
        $out  = '<div class="service-a-payer" id="'.$this->getServiceAPayerId($serviceAPayer).'">';
        $out .= $this->renderHead( $serviceAPayer );
        $typesHeures = $this->getServiceTypeHeures()->getList( $this->getServiceTypeHeures()->finderByServiceaPayer($serviceAPayer));
        $colSpan = 12 / count($typesHeures);
        if ($colSpan > 6) $colSpan = 6;
        $out .= '<div class="row">';
        foreach( $typesHeures as $typeHeures ){
            $out .= $this->renderMiseEnPaiementListe( $serviceAPayer, $typeHeures, $colSpan );
        }
        $out .= '</div>';
        $out .= '</div>';
        return $out;
    }

    public function renderHead( ServiceAPayerInterface $serviceAPayer )
    {
        $cartridgeItems = [];

        $cartridgeItems[] = $this->getView()->structure( $serviceAPayer->getStructure() )->renderLink();
        if ($serviceAPayer instanceof FormuleResultatService){
            if ($serviceAPayer->getService()->getElementPedagogique()){
                $cartridgeItems[] = $this->getView()->etape( $serviceAPayer->getService()->getElementPedagogique()->getEtape() )->renderLink();
                $cartridgeItems[] = $this->getView()->elementPedagogique( $serviceAPayer->getService()->getElementPedagogique() )->renderLink();
            }else{
                $cartridgeItems[] = 'Enseignements hors '.$this->getServiceContext()->getEtablissement()->getLibelle();
                $cartridgeItems[] = $this->getView()->etablissement( $serviceAPayer->getService()->getEtablissement() )->renderLink();
            }
        }elseif($serviceAPayer instanceof FormuleResultatServiceReferentiel){
            $cartridgeItems[] = 'Référentiel';
            $cartridgeItems[] = $this->getView()->fonctionReferentiel( $serviceAPayer->getServiceReferentiel()->getFonction() )->renderLink();
        }

        return $this->getView()->cartridge($cartridgeItems, [
            'theme'      => 'gray',
            'attributes' => ['style' => 'padding-bottom: 5px'],
        ]);
    }

    public function renderMiseEnPaiementListe( ServiceAPayerInterface $serviceAPayer, TypeHeures $typeHeures, $colSpan=12 )
    {
        $params = $this->getServiceAPayerParams($serviceAPayer, $typeHeures);

        $miseEnPaiement = new MiseEnPaiement;
        $miseEnPaiement->setServiceAPayer($serviceAPayer);
        $notAllowed = ! $this->getView()->isAllowed($miseEnPaiement, \Application\Entity\Db\Privilege::MISE_EN_PAIEMENT_DEMANDE);
        $readOnly = $this->getReadOnly() || $notAllowed;
        $saisieTerminee = ($params['heures-dmep'] + $params['heures-non-dmep']) == 0; // s'il reste des heures à positionner ou déjà positionnées

        $attrs = [
            'class'         => ['type-heures', 'col-md-'.$colSpan],
            'id'            => $typeHeures->getId(),
            'style'         => ['margin-bottom:.5em'],
        ];
        if ($notAllowed) $attrs['class'][] = 'not-allowed';
        $out  = '<div '.$this->htmlAttribs($attrs).'>';

        $attrs = [
            'class'         => ['table', 'table-condensed', 'table-extra-condensed', 'table-bordered', 'mise-en-paiement-liste'],
            'id'            => self::$miseEnPaiementListeIdSequence++,
            'data-params'   => json_encode($params),
        ];
        if ($notAllowed && ! $saisieTerminee) $attrs['class'][] = 'bg-warning';
        if ($readOnly) $attrs['class'][] = 'read-only';
        if ($saisieTerminee) $attrs['class'][] = 'bg-success';
        if (! $serviceAPayer->isPayable()){
            $out .= '<div class="alert alert-danger" role="alert">Des heures à payer ont été positionnées sur ce service alors que c\'est normalement impossible.</div>';
        }
        $out .= '<table '.$this->htmlAttribs($attrs).'>';
        $out .= '<thead><tr><th colspan="3">'.$typeHeures->getLibelleLong().'</th></tr><tr>';
        $out .= '<th style="width:8em"><abbr title="Heures équivalent TD">HETD</abbr></th>';
        $out .= '<th>Centre de coûts</th>';
        if ($serviceAPayer->isDomaineFonctionnelModifiable()){
            $out .= '<th>Domaine fonctionnel</th>';
        }
        $out .= '<th>&nbsp;</th>';
        $out .= '</tr></thead>';

        if ($params['heures-mep'] > 0){
            $title = [];
            foreach( $params['mises-en-paiement'] as $periode => $heures ){
                $title[] = $periode.' : '.strip_tags(\Common\Util::formattedHeures($heures)).' hetd mis en paiement';
            }
            $title = implode( '&#13;', $title );
            $out .= '<tr><td class="nombre"><abbr title="'.$title.'">'.\Common\Util::formattedHeures($params['heures-mep']).'</td><td>HETD déjà mises en paiement</td></tr>';
        }
        $out .= '<tfoot>';

        if (! $saisieTerminee){
            $out .= '<tr>';
            $out .= '<td class="nombre">';
            if (! $readOnly) $out .= '<button class="btn btn-default heures-non-dmep" type="button" title="Demander ces heures en paiement">';
            $out .= \Common\Util::formattedHeures($params['heures-non-dmep']);
            if (! $readOnly) $out .= '</button>';
            $out .= '</td>';
            $out .= '<th>HETD restantes</th>';
            if ($serviceAPayer->isDomaineFonctionnelModifiable()){
                $out .= '<td>&nbsp;</td>';
            }
            $out .= '<td>&nbsp;</td>';
            $out .= '</tr>';
        }
        $out .= '<tr class="active">';
        $out .= '<td class="nombre heures-total">'.\Common\Util::formattedHeures($params['heures-total']).'</td>';
        $out .= '<th>HETD au total</th>';
        $out .= '<td>&nbsp;</td>';
        if ($serviceAPayer->isDomaineFonctionnelModifiable()){
                $out .= '<td>&nbsp;</td>';
            }
        $out .= '</tr></tfoot>';
        $out .= '</table>';
        $out .= '</div>';
        return $out;
    }

    public function getId()
    {
        return 'demande-mise-en-paiement';
    }

    /**
     *
     * @return array
     */
    protected function getParams()
    {
        $params = [
        ];
        return $params;
    }

    protected function getServiceAPayerParams( ServiceAPayerInterface $serviceAPayer, TypeHeures $typeHeures )
    {
        $defaultCentreCout = $serviceAPayer->getDefaultCentreCout($typeHeures);
        $defaultDomaineFonctionnel = $serviceAPayer->getDefaultDomaineFonctionnel( $this->getServiceDomaineFonctionnel() );
        

        $params = [
            'centres-cout'          => [],
            'domaines-fonctionnels' => $serviceAPayer->isDomaineFonctionnelModifiable() ? $this->getDomainesFonctionnels() : null,
            'default-centre-cout'   => $defaultCentreCout ? $defaultCentreCout->getId() : null,
            'default-domaine-fonctionnel' => $defaultDomaineFonctionnel ? $defaultDomaineFonctionnel->getId() : null,
            'mises-en-paiement'     => [],
            'demandes-mep'          => [],
            'heures-total'          => $serviceAPayer->isPayable() ? $serviceAPayer->getHeuresCompl($typeHeures) : 0,
            'heures-mep'            => 0.0,
            'heures-dmep'           => 0.0,
            'heures-non-dmep'       => 0.0,
            'mep-defaults'          => [
                'formule-resultat-service-id'             => $serviceAPayer instanceof FormuleResultatService            ? $serviceAPayer->getId() : null,
                'formule-resultat-service-referentiel-id' => $serviceAPayer instanceof FormuleResultatServiceReferentiel ? $serviceAPayer->getId() : null,
                'type-heures-id'                          => $typeHeures->getId(),
            ],
        ];
        $mepBuffer = [];

        $ccCount = 0;
        $ccLast = null;
        foreach( $serviceAPayer->getCentreCout() as $centreCout ){
            if ($centreCout->typeHeuresMatches( $typeHeures )){
                $ccCount ++;
                $ccLast = $centreCout->getId();
                $params['centres-cout'][$ccLast] = [
                    'libelle' => (string)$centreCout,
                    'parent'  => $centreCout->getParent() ? $centreCout->getParent()->getId() : null
                ];
            }
        }
        if ($ccCount == 1){ // un seul choix possible, donc sél. par défaut!
            $params['default-centre-cout'] = $ccLast;
        }

        $misesEnPaiement = $serviceAPayer->getMiseEnPaiement()->filter( function( MiseEnPaiement $miseEnPaiement ) use ($typeHeures) {
            return $miseEnPaiement->getTypeHeures() === $typeHeures;
        } );
        foreach( $misesEnPaiement as $miseEnPaiement ){
            /* @var $miseEnPaiement MiseEnPaiement */
            if ( $pp = $miseEnPaiement->getPeriodePaiement() ){
                if (! isset($mepBuffer[$pp->getId()])){
                    $mepBuffer[$pp->getId()] = [
                        'periode' => $pp,
                        'heures'  => 0,
                    ];
                }
                $mepBuffer[$pp->getId()]['heures'] += $miseEnPaiement->getHeures(); // mise en buffer pour tri...
                $params['heures-mep'] += $miseEnPaiement->getHeures();
            }else{
                $domaineFonctionnel = $miseEnPaiement->getDomaineFonctionnel();

                $dmepParams = [
                    'centre-cout-id'            => $miseEnPaiement->getCentreCout()->getId(),
                    'domaine-fonctionnel-id'    => $domaineFonctionnel ? $domaineFonctionnel->getId() : null,
                    'heures'                    => $miseEnPaiement->getHeures(),
                    'read-only'                 => $this->getReadOnly() || ! $this->getView()->isAllowed($miseEnPaiement, \Application\Entity\Db\Privilege::MISE_EN_PAIEMENT_DEMANDE),
                ];
                if ($validation = $miseEnPaiement->getValidation()){
                    $dmepParams['validation'] = [
                        'date'          => $miseEnPaiement->getDateValidation()->format('d/m/Y'),
                        'utilisateur'   => (string)$validation->getHistoCreateur()
                    ];
                }
                $params['demandes-mep'][$miseEnPaiement->getId()] = $dmepParams;
                $params['heures-dmep'] += $miseEnPaiement->getHeures();
            }
        }
        $params['heures-non-dmep'] = $params['heures-total'] - $params['heures-mep'] - $params['heures-dmep'];

        // tri du buffer et mise en paramètres
        usort( $mepBuffer, function($a, $b){
            return $a['periode']->getOrdre() > $b['periode']->getOrdre();
        });
        foreach( $mepBuffer as $mb ){
            $params['mises-en-paiement'][(string)$mb['periode']] = $mb['heures'];
        }

        return $params;
    }

    /**
     *
     * @param ServiceAPayerInterface $serviceAPayer
     * @return string
     */
    protected function getServiceAPayerId( ServiceAPayerInterface $serviceAPayer )
    {
        $id = '';
        if     ($serviceAPayer instanceof FormuleResultatService)            $id .= 'service';
        elseif ($serviceAPayer instanceof FormuleResultatServiceReferentiel) $id .= 'referentiel';
        $id .= '-'.$serviceAPayer->getId();
        return $id;
    }

    public function getReadOnly()
    {
        return $this->readOnly;
    }

    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     *
     * @return ServiceAPayerInterface[]
     */
    public function getServicesAPayer()
    {
        return $this->servicesAPayer;
    }

    /**
     *
     * @param ServiceAPayerInterface[] $servicesAPayer
     * @return self
     */
    public function setServicesAPayer( array $servicesAPayer )
    {
        $this->servicesAPayer = $servicesAPayer;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getDomainesFonctionnels()
    {
        if (empty($this->domainesFonctionnels)){
            $sdf = $this->getServiceDomaineFonctionnel();
            $this->setDomainesFonctionnels( $sdf->getList( $sdf->finderByHistorique() ) );
        }
        return $this->domainesFonctionnels;
    }

    /**
     *
     * @param array $domainesFonctionnels
     * @return self
     */
    public function setDomainesFonctionnels( $domainesFonctionnels )
    {
        $this->domainesFonctionnels = [];
        foreach( $domainesFonctionnels as $id => $domaineFonctionnel ){
            if ($domaineFonctionnel instanceof DomaineFonctionnel){
                $this->domainesFonctionnels[$domaineFonctionnel->getId()] = (string)$domaineFonctionnel;
            }else{
                $this->domainesFonctionnels[$id] = (string)$domaineFonctionnel;
            }
        }
        return $this;
    }
}