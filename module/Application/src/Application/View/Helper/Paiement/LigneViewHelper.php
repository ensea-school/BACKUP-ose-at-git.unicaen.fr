<?php

namespace Application\View\Helper\Paiement;

use Zend\View\Helper\AbstractHtmlElement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\ServiceAPayerInterface;
use Application\Interfaces\ServiceAPayerAwareInterface;
use Application\Traits\ServiceAPayerAwareTrait;
use Application\Entity\Db\FormuleResultatService;
use Application\Entity\Db\FormuleResultatServiceReferentiel;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\MiseEnPaiementListe;

/**
 * Description of LigneViewHelper
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class LigneViewHelper extends AbstractHtmlElement implements ServiceLocatorAwareInterface, ServiceAPayerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ServiceAPayerAwareTrait;

    /**
     * Helper entry point.
     *
     * @param Liste $liste
     * @param Service $service
     * @return self
     */
    final public function __invoke( ServiceAPayerInterface $serviceAPayer )
    {
        $this->setServiceAPayer($serviceAPayer);
        return $this;
    }

    public function getMiseEnPaiementSaisieFormUrl(ServiceAPayerInterface $serviceAPayer)
    {
        $params = [];
        if ($serviceAPayer instanceof FormuleResultatService){
            $params['formule-resultat-service'] = $serviceAPayer->getId();
        }
        if ($serviceAPayer instanceof FormuleResultatServiceReferentiel){
            $params['formule-resultat-service-referentiel'] = $serviceAPayer->getId();
        }
        $url = $this->getView()->url(
                'paiement/saisie', [], ['query'=>$params]);
        return $url;
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

    public function render()
    {
        $sap = $this->getServiceAPayer();
        $mpl = $sap->getMiseEnPaiementListe();

        $sclass = 'service-a-payer-';
        if     ($sap instanceof FormuleResultatService              ) $sclass .= 'service';
        elseif ($sap instanceof FormuleResultatServiceReferentiel   ) $sclass .= 'referentiel';

        $out  = '<div class="service-a-payer '.$sclass.'" id="'.$sap->getId().'">';
        $out .= '<div class="head">'.$this->renderHead().'</div>';

        $typesHeures = $this->getServiceTypeHeures()->getListFromServiceAPayer($sap);
        foreach( $typesHeures as $typeHeures ){
            $out .= $this->renderTypeHeures( $mpl->getChild()->setTypeHeures($typeHeures) );
        }
        $out .= '</div>';
        $out .= '<hr />';
        return $out;
    }

    public function renderHead()
    {
        $sap = $this->getServiceAPayer();
        if ($sap instanceof FormuleResultatService){
            if ($sap->getService()->getElementPedagogique()){
                $out = $this->getView()->etape( $sap->getService()->getElementPedagogique()->getEtape() )->renderLink();
                $out .= ' > ';
                $out .= $this->getView()->elementPedagogique( $sap->getService()->getElementPedagogique() )->renderLink();
                return $out;
            }else{
                return $this->getView()->etablissement( $sap->getService()->getEtablissement() )->renderLink();
            }
        }elseif($sap instanceof FormuleResultatServiceReferentiel){
            return $this->getView()->fonctionReferentiel( $sap->getServiceReferentiel()->getFonction() )->renderLink();
        }
    }

    public function renderTypeHeures( MiseEnPaiementListe $mpl )
    {
        $sap = $this->getServiceAPayer();
        $form = $this->getMiseEnPaiementSaisieForm();
        $heuresTotal = $sap->getHeures($mpl->getTypeHeures());
        $heuresCalc  = 0.0;

        $out  = '<div class="row" style="margin-bottom:.5em">';
        $out .= '<div class="col-xs-1">';
        $out .= '<h4>'.$mpl->getTypeHeures()->toHtml().'</h4>';
        $out .= '</div>';
        $out .= '<div class="col-xs-11">';
        $out .= $this->getView()->form()->openTag($form);
        $out .= $this->getView()->formHidden($form->get('formule-resultat-service'));
        $out .= $this->getView()->formHidden($form->get('formule-resultat-service-referentiel'));
        $out .= '<table class="table table-condensed table-extra-condensed" style="margin:auto;width:90%">';
        $out .= '<thead><tr>';
        $out .= '<th style="width:6em">Heures</th>';
        $out .= '<th style="width:50%">Centre de coût</th>';
        $out .= '<th>&nbsp;</th>';
        $out .= '</tr></thead>';
        $centresCout = $mpl->getCentresCout();
        foreach( $centresCout as $centreCout ){
            $l = $mpl->getChild()->setCentreCout($centreCout);
            $heures = $l->getHeures();
            $heuresCalc += $heures;
            $out .= '<tr>';
            $out .= '<td style="text-align:right;padding-right:10pt">'.\Common\Util::formattedHeures($heures).'</td>';
            $out .= '<td>'.$centreCout.'</td>';
            $out .= '<td>&nbsp;</td>';
            $out .= '</tr>';
        }
        if ($heuresTotal !== $heuresCalc){
            $heuresValue = round($heuresTotal - $heuresCalc,2);
            $form->get('heures')->setValue( $heuresValue );
            $form->get('heures')->setAttribute( 'max', $heuresValue );
            $out .= '<tr>';
            $out .= '<td style="text-align:right;padding-right:10pt">'.$this->getView()->formNumber($form->get('heures')).'</td>';
            $out .= '<td>'.$this->getView()->formSelect($form->get('centre-cout')).'</td>';
            $out .= '<td>&nbsp;</td>';
            $out .= '</tr>';
        }
        $out .= '<tfoot><tr class="active">';
        $out .= '<td style="text-align:right;padding-right:10pt">'.\Common\Util::formattedHeures($heuresTotal).'</td>';
        $out .= '<th>Total</th>';
        $out .= '<td>&nbsp;</td>';
        $out .= '</tr></tfoot>';
        $out .= '</table>';
        $out .= $this->getView()->form()->closeTag();
        $out .= '</div>';
        $out .= '</div>';
        return $out;
    }

    public function renderForm()
    {
        
        

        $out .= '<tr>';
        $out .= '<td style="text-align:right;padding-right:10pt">'.\Common\Util::formattedHeures($l->getHeures()).'</td>';
        $out .= '<td>'.$centreCout.'</td>';
        $out .= '<td>&nbsp;</td>';
        $out .= '</tr>';


        
        $out .= $this->getView()->formRow($this->form->get('submit'));
        
        return $out;
    }

    /**
     * Retourne le formulaire de modif de Volume Horaire.
     *
     * @return \Application\Form\Paiement\MiseEnPaiementSaisieForm
     */
    protected function getMiseEnPaiementSaisieForm()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('FormElementManager')->get('MiseEnPaiementSaisie');
    }

    /**
     * @return \Application\Service\TypeHeures
     */
    protected function getServiceTypeHeures()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeHeures');
    }
}