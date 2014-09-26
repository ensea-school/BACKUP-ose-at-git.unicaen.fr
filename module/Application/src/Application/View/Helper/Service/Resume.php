<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Aide de vue permettant d'afficher un résumé des services
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Resume extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * Filtre de données
     *
     * @var array
     */
    protected $resumeServices;





    /**
     * Helper entry point.
     *
     * @return self
     */
    final public function __invoke( $resumeServices )
    {
        $this->resumeServices = $resumeServices;
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
     * @return \Application\Entity\Db\TypeIntervention[]
     */
    public function getTypesIntervention()
    {
        if ($this->resumeServices){
            $typesIntervention = array();
            foreach( $this->resumeServices as $line ) {
                if (isset($line['service'])){
                    foreach( $line['service'] as $tiId => $null ){
                        $typesIntervention[$tiId] = $tiId;
                    }
                }
            }
            return $this->getServiceTypeIntervention()->get($typesIntervention);
        }else{
            return $this->getServiceTypeIntervention()->getTypesIntervention();
            // Types d'intervention par défaut
        }
    }

    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render()
    {
        $typesIntervention = $this->getTypesIntervention();
        $totaux = array(
            'intervenants'          => 0,
            'heures'                => 0,
            'types_intervention'    => [],
            'referentiel'           => 0,
        );

        $res  = '<table class="table table-hover table-bordered">'."\n";
        $res .= '<thead>'."\n";
        $res .= '<tr>'."\n";
        $res .= '    <th style="width:40%" rowspan="2">Intervenant</th>'."\n";
        $res .= '    <th style="width:40%" colspan="'.count($typesIntervention).'">Enseignements</th>'."\n";
        $res .= '    <th style="width:10%" rowspan="2">Référentiel</th>'."\n";
        $res .= '    <th style="width:10%" rowspan="2">Solde HETD</th>'."\n";
        $res .= '</tr>'."\n";
        $res .= '<tr>'."\n";
        foreach( $typesIntervention as $ti ){
            $res .= '        <th><abbr title="'.$ti->getLibelle().'">'.$ti.'</abbr></th>'."\n";
        }
        $res .= '</tr>'."\n";
        $res .= '</thead>'."\n";
        $res .= '<tbody>'."\n";
        foreach( $this->resumeServices as $intervenantId => $line ) {
            $na = '<abbr title="Non applicable (intervenant vacataire))">NA</abbr>';
            $intervenantPermanent = $line['intervenant']['TYPE_INTERVENANT_CODE'] === \Application\Entity\Db\TypeIntervenant::CODE_PERMANENT;

            if (isset($line['intervenant']['HEURES_COMP'])){
                $solde = (float)$line['intervenant']['HEURES_COMP'];
            }else{
                $solde = 0;
            }
            if (! $intervenantPermanent){
                $solde = $na;
            }

            $res .= '<tr>'."\n";
            $url = $this->getView()->url('intervenant/services', array('intervenant' => $line['intervenant']['SOURCE_CODE']));

            $res .= '<td><a href="'.$url.'">'.strtoupper($line['intervenant']['NOM_USUEL']) . ' ' . $line['intervenant']['PRENOM'].'</a></td>'."\n";
            $totaux['intervenants']++;
            foreach( $typesIntervention as $ti ){
                if (! isset($totaux['types_intervention'][$ti->getId()])){
                    $totaux['types_intervention'][$ti->getId()] = 0;
                }
                if (isset($line['service'][$ti->getId()])){
                    $totaux['types_intervention'][$ti->getId()] += $line['service'][$ti->getId()];
                    $totaux['heures'] += $line['service'][$ti->getId()];
                    $res .= '<td style="text-align:right">'.$this->formatHeures($line['service'][$ti->getId()]).'</td>'."\n";
                }else{
                    $res .= '<td style="text-align:right">'.$this->formatHeures(0).'</td>'."\n";
                }
            }
            if (array_key_exists('referentiel', $line)){
                $totaux['referentiel'] += $line['referentiel'];
                $res .= '<td style="text-align:right">'.$this->formatHeures($line['referentiel']).'</td>'."\n";
            }else{
                $res .= '<td style="text-align:right">'.($intervenantPermanent ? $this->formatHeures(0) : $na).'</td>'."\n";
            }
            $res .= $this->renderSoldeHetd($solde);
            $res .= '</tr>'."\n";
        }
        $res .= '</tbody>'."\n";
        $res .= '<tfoot>'."\n";
        $res .= '<tr>'."\n";
        $res .= '<th rowspan="2" style="text-align:right">'.$totaux['intervenants'].' intervenants</th>'."\n";
        foreach( $typesIntervention as $ti ){
            $heures = isset($totaux['types_intervention'][$ti->getId()]) ? $totaux['types_intervention'][$ti->getId()] : 0;
            $res .= '        <th style="text-align:right"><abbr title="'.$ti->getLibelle().'">'.$this->formatHeures($heures).'</abbr></th>'."\n";
        }
        $res .= '<th rowspan="2" style="text-align:right">'.$this->formatHeures($totaux['referentiel']).'</th>'."\n";
        $res .= '<th rowspan="2">&nbsp;</th>'."\n";
        $res .= '</tr>'."\n";
        $res .= '<tr>'."\n";
        $res .= '<th colspan="'.count($typesIntervention).'" style="text-align:right">Total des heures de service : '.$this->formatHeures($totaux['heures']).'</th>'."\n";
        $res .= '</tr>'."\n";
        $res .= '</tfoot>'."\n";
        $res .= '</table>'."\n";
        return $res;
    }

    protected function renderSoldeHetd( $solde )
    {
        $class = '';
        $plus = '';
        if (is_numeric($solde)){
            if ($solde > 0){ $class = ' class="bg-warning"'; $plus = '+';}
            if ($solde < 0) $class = ' class="bg-danger"';
            $solde = $plus.$this->formatHeures($solde);
        }

        $res = '<td style="text-align:right"'.$class.'>'.$solde.'</td>'."\n";
        return $res;
    }

    /**
     *
     * @param float $heures
     * @return string
     */
    protected function formatHeures($heures)
    {
        $heures = round( (float)$heures, 2);
        $heures = \UnicaenApp\Util::formattedFloat($heures, \NumberFormatter::DECIMAL, 2);
        $heures = str_replace( ',00', '<span style="color:gray">,00</span>', $heures );
        return $heures;
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
    }
}