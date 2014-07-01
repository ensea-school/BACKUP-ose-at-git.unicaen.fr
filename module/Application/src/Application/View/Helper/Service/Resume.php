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
     * @var Stdclass
     */
    protected $filter;





    /**
     * Helper entry point.
     *
     * @return self
     */
    final public function __invoke()
    {
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
        return $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeIntervention')->getTypesIntervention();
    }

    /**
     * @return Stdclass
     */
    protected function getFilter()
    {
        return $this->filter;
    }

    /**
     *
     * @param Stdclass $filter
     * @return self
     */
    public function setFilter( $filter )
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Retourne les données
     *
     * @return array
     */
    protected function getData()
    {
        $filter = $this->getFilter();
        return $this->getServiceLocator()->getServiceLocator()->get('ApplicationService')->getResumeService($filter);
    }

    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render()
    {
        $typesIntervention = $this->getTypesIntervention();
        $data              = $this->getData();

        $res  = '<table class="table table-hover table-bordered">'."\n";
        $res .= '<thead>'."\n";
        $res .= '<tr>'."\n";
        $res .= '    <th style="width:40%" rowspan="2">Intervenant</th>'."\n";
        $res .= '    <th style="width:40%" colspan="'.count($typesIntervention).'">Enseignements</th>'."\n";
        $res .= '    <th style="width:10%" rowspan="2">Référentiel</th>'."\n";
        $res .= '    <th style="width:10%" rowspan="2">Heures &Eacute;q. TD</th>'."\n";
        $res .= '</tr>'."\n";
        $res .= '<tr>'."\n";
        foreach( $typesIntervention as $ti ){
            $res .= '        <th title="'.$ti->getLibelle().'">'.$ti.'</th>'."\n";
        }
        $res .= '</tr>'."\n";
        $res .= '</thead>'."\n";
        $res .= '<tbody>'."\n";
        foreach( $data as $intervenantId => $line ) {
            $intervenantPermanent = $line['intervenant']['TYPE_INTERVENANT_CODE'] === \Application\Entity\Db\TypeIntervenant::CODE_PERMANENT;


            if (isset($line['intervenant']['TOTAL_HETD'])){
                $hetd = (float)$line['intervenant']['TOTAL_HETD'];
            }else{
                $hetd = 0;
            }

            if (isset($line['intervenant']['HEURES_COMP'])){
                $heuresComp = (float)$line['intervenant']['HEURES_COMP'];
                $msg = '';
                if ($heuresComp < 0){
                    $msg = ' class="bg-danger" title="Sous-service ('.number_format($heuresComp*-1,2,',',' ').' heures manquantes)"';
                }
                if ($heuresComp > 0 && $intervenantPermanent){
                    $msg = ' class="bg-warning" title="Sur-service ('.number_format($heuresComp,2,',',' ').' heures complémentaires positionnées)"';;
                }
            }else{
                $heuresComp = 0;
                $msg = '';
            }

            $res .= '<tr>'."\n";
            $url = $this->getView()->url('intervenant/services', array('intervenant' => $line['intervenant']['SOURCE_CODE']));
            $na = '<span title="Non applicable (intervenant vacataire))">NA</span>';

            $res .= '<td><a href="'.$url.'">'.strtoupper($line['intervenant']['NOM_USUEL']) . ' ' . $line['intervenant']['PRENOM'].'</a></td>'."\n";
            foreach( $typesIntervention as $ti ){
                $res .= '<td>'.(isset($line['service'][$ti->getId()]) ? $line['service'][$ti->getId()] : '0').'</td>'."\n";
            }
            $res .= '<td>'.(array_key_exists('referentiel', $line) ? $line['referentiel'] : ($intervenantPermanent ? 0 : $na)).'</td>'."\n";
            $res .= '<td'.$msg.'>'.number_format($hetd,2,',',' ').'</td>'."\n";
            $res .= '</tr>'."\n";
        }
        $res .= '</tbody>'."\n";
        $res .= '</table>'."\n";
        return $res;
    }

}