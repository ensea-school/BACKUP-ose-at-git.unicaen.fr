<?php

namespace Application\View\Helper\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
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
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $res = '';
        $tag = $this->getView()->tag(); /* @var $tag TagViewHelper */

        $res .= $tag('div', ['class' => 'feuille-de-route row']);
        $res .= $tag('div', ['class' => 'col-md-9']);
        $res .= $tag('ul', ['class' => 'list-group']);
        $index = 0;
        $isAfterCourante = false;

        foreach ($feuilleDeRoute as $etape){
            $index++;

            $attrs = ['class' => 'list-group-item'];
            if (!$etape->isAtteignable() && $isAfterCourante){
                $attrs['class'] .= ' after-courante';
            }
            if ($etape->isCourante()){
                $attrs['class'] .= ' list-group-item-warning';
                $isAfterCourante = true;
            }
            $res .= $tag('li', $attrs);
            $res .= $tag('span', ['class' => 'label label-primary'])->text($index);

            if ($etape->getUrl() && $etape->isAtteignable()){
                $res .= $tag('a', [
                    'title' => 'Cliquez sur ce lien pour accéder à la page correspondant à cette étape',
                    'href'  => $etape->getUrl(),
                ])->text( $etape->getEtape()->getLibelle($role) );
            }else{
                $res .= $etape->getEtape()->getLibelle($role);
            }
            if ($etape->getFranchie() == 1){
                $res .= $tag('span', ['title' => 'Fait', 'class' => 'glyphicon glyphicon-ok text-success pull-right'])->text('');
            }elseif($etape->getFranchie() > 0 && count($etape->getEtapes()) > 1) {
                $collapseId = 'collapse-' . $this->getIntervenant()->getId() . '-' . $etape->getEtape()->getId();
                $res .= $tag('span', ['title' => 'En cours (cliquez pour afficher le détail par composante)', 'class' => 'text-warning pull-right'])->html(
                    $tag('a', ['data-toggle' => 'collapse', 'href' => '#' . $collapseId])->text(
                        Util::formattedPourcentage($etape->getFranchie(), true)
                    )
                );
                $res .= $tag('div', ['class' => 'row collapse', 'id' => $collapseId]);
                $res .= $tag('div', ['class' => 'col-md-4 col-md-offset-8']);
                $res .= $tag('ul', ['class' => 'list-group']);
                foreach ($etape->getEtapes() as $sEtape) {
                    $res .= $tag('li', ['class' => 'list-group-item']);
                    $res .= $tag('span')->text($sEtape->getStructure());
                    if ($sEtape->getFranchie() == 1) {
                        $res .= $tag('span', ['title' => 'Fait', 'class' => 'glyphicon glyphicon-ok text-success pull-right'])->text('');
                    } elseif($sEtape->getFranchie() == 0) {
                        $res .= $tag('span', ['title' => 'À faire', 'class' => 'glyphicon glyphicon-remove text-danger pull-right'])->text('');
                    } else {
                        $res .= $tag('span', ['title' => 'En cours', 'class' => 'text-warning pull-right'])->html(
                            Util::formattedPourcentage($sEtape->getFranchie(), true)
                        );
                    }

                    $res .= $tag('li')->close();
                }
                $res .= $tag('ul')->close();
                $res .= $tag('div')->close();
                $res .= $tag('div')->close();
            }elseif($etape->getFranchie() > 0){
                $res .= $tag('span', ['title' => 'En cours', 'class' => 'text-warning pull-right'])->html(
                    Util::formattedPourcentage($etape->getFranchie(), true)
                );
            }else{
                $res .= $tag('span', ['title' => 'À faire', 'class' => 'glyphicon glyphicon-remove text-danger pull-right'])->text('');
            }

            $res .= $tag('li')->close();
        }
        $res .= $tag('ul')->close();
        $res .= $tag('div')->close();
        $res .= $tag('div')->close();

        return $res;
    }

}