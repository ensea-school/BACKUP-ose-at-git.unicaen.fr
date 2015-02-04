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

        $sclass = 'service-a-payer-';
        if     ($sap instanceof FormuleResultatService              ) $sclass .= 'service';
        elseif ($sap instanceof FormuleResultatServiceReferentiel   ) $sclass .= 'referentiel';

        $out  = '<div class="service-a-payer '.$sclass.'" id="'.$sap->getId().'">';
        $out .= '<div class="head">'.$this->renderHead().'</div>';
        $out .= '<div class="row">';
        $out .= '<div class="col-md-2">';
        $out .= '<table class="table">';
        if ($sap->getHeuresComplFi() > 0){
            $out .= '<tr><th>FI</th><td style="text-align:right">'.\Common\Util::formattedHeures( $sap->getHeuresComplFi() ).'</td></tr>';
        }
        if ($sap->getHeuresComplFa() > 0){
            $out .= '<tr><th>FA</th><td style="text-align:right">'.\Common\Util::formattedHeures( $sap->getHeuresComplFa() ).'</td></tr>';
        }
        if ($sap->getHeuresComplFc() + $sap->getHeuresComplFcMajorees() > 0){
            $out .= '<tr><th>FC</th><td style="text-align:right">'.\Common\Util::formattedHeures( $sap->getHeuresComplFc() + $sap->getHeuresComplFcMajorees() ).'</td></tr>';
        }
        if ($sap->getHeuresComplReferentiel() > 0){
            $out .= '<tr><th><abbr title="référentiel">Réf.</abbr></th><td style="text-align:right">'.\Common\Util::formattedHeures( $sap->getHeuresComplReferentiel() ).'</td></tr>';
        }
        $out .= '</table>';
        $out .= '</div>';
        $out .= '<div class="col-md-10">&nbsp;';

        $out .= '</div>';
        $out .= '</div>';
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
}