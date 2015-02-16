<?php

namespace Application\View\Helper\Paiement;

use Zend\View\Helper\AbstractHtmlElement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\ServiceAPayerInterface;
use Application\Entity\Db\FormuleResultatService;
use Application\Entity\Db\FormuleResultatServiceReferentiel;
use Application\Entity\Db\TypeHeures;
use Application\Entity\Db\MiseEnPaiement;

/**
 * Description of DemandeMiseEnPaiementViewHelper
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class DemandeMiseEnPaiementViewHelper extends AbstractHtmlElement implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    private $servicesAPayer = [];

    /**
     *
     * @var \Zend\Form\Form
     */
    private $form;

    private static $miseEnPaiementListeIdSequence = 1;


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
            $this->form->add(array(
                'name' => 'submit',
                'type'  => 'Submit',
                'attributes' => array(
                    'value' => 'Effectuer la demande de paiement',
                    'class' => 'btn btn-primary sauvegarde',
                ),
            ));

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
        $out .= '<div style="padding-bottom:1em"><button type="button" class="btn btn-default toutes-heures-non-dmep">Demander toutes les HETD en paiement</button></div>';
        foreach( $servicesAPayer as $serviceAPayer ){
            $out .= $this->renderServiceAPayer($serviceAPayer);
        }
        $out .= '<div>';

        $out .= $this->getView()->form()->openTag($this->getForm());
        $out .= $this->getView()->formHidden($this->getForm()->get('changements'));
        $out .= $this->getView()->formRow($this->getForm()->get('submit'));
        $out .= $this->getView()->form()->closeTag();

        $out .= '</div>';
        $out .= '</div>';
        $out .= '<script type="text/javascript">';
        $out .= '$(function() { DemandeMiseEnPaiement.get("'.$this->getId().'").init(); });';
        $out .= '</script>';

        return $out;
    }

    public function renderServiceAPayer( ServiceAPayerInterface $serviceAPayer )
    {
        $out  = '<div class="service-a-payer" id="'.$this->getServiceAPayerId($serviceAPayer).'">';
        $out .= '<ul class="breadcrumb"><li>'.$this->renderHead( $serviceAPayer ).'</li></ul>';
        $typesHeures = $this->getServiceTypeHeures()->getListFromServiceAPayer($serviceAPayer);
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
        if ($serviceAPayer instanceof FormuleResultatService){
            if ($serviceAPayer->getService()->getElementPedagogique()){
                $out = $this->getView()->etape( $serviceAPayer->getService()->getElementPedagogique()->getEtape() )->renderLink();
                $out .= ' > ';
                $out .= $this->getView()->elementPedagogique( $serviceAPayer->getService()->getElementPedagogique() )->renderLink();
                return $out;
            }else{
                return $this->getView()->etablissement( $serviceAPayer->getService()->getEtablissement() )->renderLink();
            }
        }elseif($serviceAPayer instanceof FormuleResultatServiceReferentiel){
            return $this->getView()->fonctionReferentiel( $serviceAPayer->getServiceReferentiel()->getFonction() )->renderLink();
        }
    }

    public function renderMiseEnPaiementListe( ServiceAPayerInterface $serviceAPayer, TypeHeures $typeHeures, $colSpan=12 )
    {
        $params = $this->getServiceAPayerParams($serviceAPayer, $typeHeures);
        $attrs = [
            'class'         => ['type-heures', 'col-md-'.$colSpan],
            'id'            => $typeHeures->getId(),
            'style'         => ['margin-bottom:.5em'],
        ];
        $out  = '<div '.$this->htmlAttribs($attrs).'>';
        
        $attrs = [
            'class'         => ['table', 'table-condensed', 'table-extra-condensed', 'table-bordered', 'mise-en-paiement-liste'],
            'id'            => self::$miseEnPaiementListeIdSequence++,
            'data-params'   => json_encode($params),
        ];
        $out .= '<table '.$this->htmlAttribs($attrs).'>';
        $out .= '<thead><tr><th colspan="3">'.$typeHeures->getLibelleLong().'</th></tr><tr>';
        $out .= '<th style="width:8em"><abbr title="Heures équivalent TD">HETD</abbr></th>';
        $out .= '<th>Centre de coût</th>';
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
        $out .= '<tfoot><tr>';
        $out .= '<td class="nombre"><button class="btn btn-default heures-non-dmep" type="button" title="Demander ces heures en paiement">'.\Common\Util::formattedHeures($params['heures-non-dmep']).'</button></td>';
        $out .= '<th>HETD restantes</th>';
        $out .= '<td>&nbsp;</td>';
        $out .= '</tr>';
        $out .= '<tr class="active">';
        $out .= '<td class="nombre heures-total">'.\Common\Util::formattedHeures($params['heures-total']).'</td>';
        $out .= '<th>HETD au total</th>';
        $out .= '<td>&nbsp;</td>';
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

        $params = [
            'centres-cout'          => [],
            'default-centre-cout'   => $defaultCentreCout ? $defaultCentreCout->getId() : null,
            'mises-en-paiement'     => [],
            'demandes-mep'          => [],
            'heures-total'          => $serviceAPayer->getHeures($typeHeures),
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
                $dmepParams = [
                    'centre-cout-id'    => $miseEnPaiement->getCentreCout()->getId(),
                    'heures'            => $miseEnPaiement->getHeures(),
                    'read-only'         => $miseEnPaiement->getValidation() ? true : false,
                ];
                if ($validation = $miseEnPaiement->getValidation()){
                    $dmepParams['read-only'] = true;
                    $dmepParams['validation'] = [
                        'date'          => $miseEnPaiement->getDateValidation()->format('d/m/Y'),
                        'utilisateur'   => (string)$validation->getHistoCreateur()
                    ];
                }else{
                    $dmepParams['read-only'] = false;
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

    /**
     * 
     * @return ServiceAPayerInterface[]
     */
    function getServicesAPayer()
    {
        return $this->servicesAPayer;
    }

    /**
     *
     * @param ServiceAPayerInterface[] $servicesAPayer
     * @return self
     */
    function setServicesAPayer( array $servicesAPayer )
    {
        $this->servicesAPayer = $servicesAPayer;
        return $this;
    }


    /**
     * @return \Application\Service\TypeHeures
     */
    protected function getServiceTypeHeures()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeHeures');
    }
}