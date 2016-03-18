<?php

namespace Application\View\Helper\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\WfDepBloquante;
use Application\Entity\Db\WfEtapeDep;
use Application\Entity\WorkflowEtape;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenApp\View\Helper\TagViewHelper;
use Zend\View\Helper\AbstractHtmlElement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of FeuilleDeRouteViewHelper
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FeuilleDeRouteViewHelper extends AbstractHtmlElement implements ServiceLocatorAwareInterface{
    use ServiceLocatorAwareTrait;
    use WorkflowServiceAwareTrait;
    use IntervenantAwareTrait;
    use ContextAwareTrait;

    /**
     *
     * @return self
     */
    public function __invoke( Intervenant $intervenant=null )
    {
        if ($intervenant) $this->setIntervenant($intervenant);

        return $this;
    }



    /**
     * Retourne le code HTML.
     *
     * @return string Code HTML
     */
    public function __toString()
    {
        return $this->render();
    }



    /**
     *
     *
     * @return string Code HTML
     */
    public function render()
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($this->getIntervenant());

        $res = '';
        $tag = $this->getView()->tag(); /* @var $tag TagViewHelper */

        $res .= $tag('div', ['class' => 'feuille-de-route row']);
        $res .= $tag('div', ['class' => 'col-md-9']);
        $res .= $tag('ul', ['class' => 'list-group']);
        $index = 0;
        $isAfterCourante = false;

        foreach ($feuilleDeRoute as $etape){
            $index++;

            $res .= $this->renderEtape( $etape, $index, $isAfterCourante );

            if ($etape->isCourante()){
                $isAfterCourante = true;
            }
        }
        $res .= $tag('ul')->close();
        $res .= $tag('div')->close();
        $res .= $tag('div')->close();

        return $res;
    }



    public function renderEtape( WorkflowEtape $etape, $index, $isAfterCourante )
    {
        $res = '';
        $tag = $this->getView()->tag(); /* @var $tag TagViewHelper */

        $attrs = ['class' => 'list-group-item'];
        if (!$etape->isAtteignable() && $isAfterCourante){
            $attrs['class'] .= ' after-courante';

            $naDesc = '';
            foreach( $etape->getEtapes() as $sEtape) {
                foreach ($sEtape->getEtapeDeps() as $ed) {
                    /* @var $ed WfDepBloquante */
                    $naDesc .= ' - ' . $ed->getWfEtapeDep()->getEtapePrec()->getDescNonAtteignable() . "\n";
                }
                break; // affichage une seule fois des msg pour les étapes se déclinant par composante...
            }

            if ($naDesc){
                $attrs['title'] = "Non atteignable : \n".$naDesc;
            }
        }

        if ($etape->isCourante()){
            $attrs['class'] .= ' list-group-item-warning';
        }

        if (count($etape->getEtapes()) > 1){
            $collapseId = 'collapse-' . $this->getIntervenant()->getId() . '-' . $etape->getEtape()->getId();

            $detailsLink = $tag('a', ['data-toggle' => 'collapse', 'href' => '#' . $collapseId]);
            $detailsRes = $this->renderDetails( $etape, $collapseId );
        }else{
            $detailsLink = null;
            $detailsRes = '';
        }

        $res .= $tag('li', $attrs);
        $res .= $tag('span', ['class' => 'label label-primary'])->text($index);

        $res .= $this->renderEtapeLink($etape);

        $res .= $this->renderEtapeIndicateur($etape->getFranchie(), $detailsLink);
        $res .= $detailsRes;

        $res .= $tag('li')->close();

        return $res;
    }



    public function renderEtapeLink( WorkflowEtape $etape )
    {
        $res = '';
        $tag = $this->getView()->tag(); /* @var $tag TagViewHelper */
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($etape->getUrl() && $etape->isAtteignable()){
            $res .= $tag('a', [
                'title' => 'Cliquez sur ce lien pour accéder à la page correspondant à cette étape',
                'href'  => $etape->getUrl(),
            ])->text( $etape->getEtape()->getLibelle($role) );
        }else{
            $res .= $etape->getEtape()->getLibelle($role);
        }

        return $res;
    }



    public function renderEtapeIndicateur( $franchissement, $detailsLink=null )
    {
        $res = '';
        $tag = $this->getView()->tag(); /* @var $tag TagViewHelper */

        switch(true){
            case $franchissement == 1:
                $attrs = [
                    'title' => 'Fait',
                    'class' => 'text-success pull-right'
                ];
                $content = $tag('span', ['class' => 'text-success glyphicon glyphicon-ok']);
            break;
            case $franchissement == 0:
                $attrs = [
                    'title' => 'À faire',
                    'class' => 'text-danger pull-right'
                ];
                $content = $tag('span', ['class' => 'text-danger glyphicon glyphicon-remove']);
            break;
            default:
                $attrs = [
                    'title' => 'En cours',
                    'class' => 'text-warning pull-right'
                ];
                $content = Util::formattedPourcentage($franchissement, true);
        }

        if ($detailsLink instanceof TagViewHelper){
            $content = $detailsLink->html($content);
            $attrs['title'] .= ' (cliquez pour afficher le détail par composante)';
        }

        //$res .= $tag('span', $attrs)->html($content);
        $res .= $tag('span', $attrs)->html($content);

        return $res;
    }



    public function renderDetails( WorkflowEtape $etape, $collapseId )
    {
        $res = '';
        $tag = $this->getView()->tag(); /* @var $tag TagViewHelper */

        $res .= $tag('div', ['class' => 'row collapse', 'id' => $collapseId]);
        $res .= $tag('div', ['class' => 'col-md-4 col-md-offset-8']);
        $res .= $tag('ul', ['class' => 'list-group']);
        foreach ($etape->getEtapes() as $sEtape) {
            $attrs = ['class' => 'list-group-item'];

            if (!$sEtape->getAtteignable()){
                $naDesc = '';
                foreach( $sEtape->getEtapeDeps() as $ed ){
                    /* @var $ed WfDepBloquante */
                    $naDesc .= ' - '.$ed->getWfEtapeDep()->getEtapePrec()->getDescNonAtteignable()."\n";
                }

                if ($naDesc){
                    $attrs['title'] = "Non atteignable : \n".$naDesc;
                }


                $attrs['class'] .= ' after-courante';
            }
            $res .= $tag('li', $attrs);
            $res .= $tag('span')->text($sEtape->getStructure());

            $res .= $this->renderEtapeIndicateur($sEtape->getFranchie());

            $res .= $tag('li')->close();
        }
        $res .= $tag('ul')->close();
        $res .= $tag('div')->close();
        $res .= $tag('div')->close();

        return $res;
    }
}