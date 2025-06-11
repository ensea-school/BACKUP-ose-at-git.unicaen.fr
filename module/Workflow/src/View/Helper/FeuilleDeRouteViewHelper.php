<?php

namespace Workflow\View\Helper;

use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Laminas\View\Helper\AbstractHtmlElement;
use UnicaenApp\Util;
use UnicaenApp\View\Helper\TagViewHelper;
use Workflow\Entity\Db\TblWorkflow;
use Workflow\Entity\Db\WfDepBloquante;
use Workflow\Entity\Db\WfEtape;
use Workflow\Entity\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of FeuilleDeRouteViewHelper
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FeuilleDeRouteViewHelper extends AbstractHtmlElement
{
    use WorkflowServiceAwareTrait;
    use IntervenantAwareTrait;
    use ContextServiceAwareTrait;


    /**
     *
     * @return self
     */
    public function __invoke(?Intervenant $intervenant = null)
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
        $tag = $this->getView()->tag();
        /* @var $tag TagViewHelper */

        $res .= $tag('div', ['class' => 'feuille-de-route row no-intranavigation']);
        $res .= $tag('div', ['class' => 'col-md-9']);

        if (!empty($feuilleDeRoute)) {
            $res             .= $tag('ul', ['class' => 'list-group']);
            $index           = 0;
            $isAfterCourante = false;

            foreach ($feuilleDeRoute as $etape) {
                $index++;

                $res .= $this->renderEtape($etape, $index, $isAfterCourante);

                if ($etape->isCourante()) {
                    $isAfterCourante = true;
                }
            }
            $res .= $tag('ul')->close();
        } else {
            $res .= $tag('div', ['class' => 'alert alert-info'])->html(
                'La feuille de route ne comporte aucune étape pour ' . $this->getIntervenant()
            );
        }

        $res .= $tag('div')->close();
        $res .= $tag('div')->close();

        return $res;
    }



    protected function renderEtape(WorkflowEtape $etape, $index, $isAfterCourante)
    {
        $res = '';
        $tag = $this->getView()->tag();
        /* @var $tag TagViewHelper */

        $attrs = ['class' => 'list-group-item'];
        if (!$etape->isAtteignable() && $isAfterCourante) {
            $attrs['class'] .= ' after-courante';

            if ($naDesc = $this->getWhyNonAtteignable($etape)) {
                $attrs['title'] = $naDesc;
            }
        }

        if ($etape->isCourante()) {
            $attrs['class'] .= ' list-group-item-warning';
        }

        if (count($etape->getEtapes()) > 1) {
            $collapseId = 'collapse-' . $this->getIntervenant()->getId() . '-' . $etape->getEtape()->getId();

            $detailsLink = $tag('a', ['data-bs-toggle' => 'collapse', 'href' => '#' . $collapseId]);
            $detailsRes  = $this->renderDetails($etape, $collapseId);
        } else {
            $detailsLink = null;
            $detailsRes  = '';
        }

        $res .= $tag('li', $attrs);
        $res .= $tag('span', ['class' => 'label label-primary'])->text($index);

        $res .= $this->renderEtapeLink($etape, [], true);

        $res .= $this->renderEtapeIndicateur($etape, $detailsLink);
        $res .= $detailsRes;

        $res .= $tag('li')->close();

        return $res;
    }



    public function renderEtapeLink($etape, array $attributes = [], $forceDisplay = false)
    {
        if ($etape == null || $etape === WfEtape::CURRENT) {
            $etape = $this->getServiceWorkflow()->getEtapeCourante($this->getIntervenant());
        } elseif ($etape === WfEtape::NEXT) {
            $etape = $this->getServiceWorkflow()->getNextEtape(null, $this->getIntervenant());
        }
        $res = '';
        $tag = $this->getView()->tag();
        /* @var $tag TagViewHelper */
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($etape->getUrl() && $etape->isAtteignable()) {
            if ($this->getServiceWorkflow()->isAllowed($etape)) {
                if (!isset($attributes['title'])) {
                    $attributes['title'] = 'Cliquez sur ce lien pour accéder à la page correspondant à cette étape';
                }
                $attributes['href'] = $etape->getUrl();
                $res                .= $tag('a', $attributes)->text($etape->getEtape()->getLibelle($role));
            } else {
                if (!str_contains($attributes['class'] ?? '', 'btn')) {
                    $attrs = ['title' => 'Vous n\'avez pas les droits requis pour accéder à cette étape.'];
                    $res   .= $tag('span', $attrs)->text($etape->getEtape()->getLibelle($role));
                }
            }
        } elseif ($forceDisplay) {
            $attrs = [];
            $na    = $this->getWhyNonAtteignable($etape);
            if ($na) {
                $t              = 'abbr';
                $attrs['title'] = $na;
            } else {
                $t = 'span';
            }
            $res .= $tag($t, $attrs)->text($etape->getEtape()->getLibelle($role));
        }

        return $res;
    }



    /**
     * @param $etape
     */
    public function renderNavBtn($etape)
    {
        $nextEtape = $this->getServiceWorkflow()->getNextAccessibleEtape($etape, $this->getIntervenant());

        if ($nextEtape) {
            return $this->renderEtapeLink($nextEtape, ['class' => 'btn btn-primary']);
        } else {
            return '';
        }
    }



    public function renderNav(string $etape): string
    {
        $btnNextUrl = $this->getView()->url('workflow/feuille-de-route-btn-next', ['intervenant' => $this->getIntervenant()->getId(), 'wfEtapeCode' => $etape]);

        return $this->getView()->tag('div', ['id' => 'feuille-de-route-btn-next', 'data-url' => $btnNextUrl])->html(
            $this->renderNavBtn($etape)
        );
    }



    public function renderCurrentBtn()
    {
        $etape = $this->getServiceWorkflow()->getEtapeCourante($this->getIntervenant());

        if ($etape) {
            return $this->renderEtapeLink($etape, ['class' => 'btn btn-primary']);
        } else {
            return '';
        }
    }



    protected function renderEtapeIndicateur($etape, $detailsLink = null)
    {

        $objectif = null;
        if ($etape instanceof TblWorkflow) {
            $franchissement = $etape->getFranchie();
            $objectif       = $etape->getObjectif();
        } elseif ($etape instanceof WorkflowEtape) {
            $franchissement = $etape->getFranchie();
            $objectif       = $etape->getObjectif();
        }

        $res = '';
        $tag = $this->getView()->tag();
        /* @var $tag TagViewHelper */

        switch (true) {
            case $franchissement == 1:
                $attrs   = [
                    'title' => 'Fait',
                    'class' => 'text-success float-end',
                ];
                $content = $tag('span', ['class' => 'text-success fas fa-check']);
            break;
            case $franchissement == 0:
                if ($objectif === .0) {
                    $attrs   = [
                        'title' => 'À faire',
                        'class' => 'text-danger float-end',
                    ];
                    $content = $tag('span', ['class' => 'fas fa-xmark text-danger']);
                } else {
                    $attrs   = [
                        'title' => 'À faire',
                        'class' => 'text-danger float-end',
                    ];
                    $content = Util::formattedPourcentage($franchissement, true);
                }
            break;
            default:
                $attrs   = [
                    'title' => 'En cours',
                    'class' => 'text-warning float-end',
                ];
                $content = Util::formattedPourcentage($franchissement, true);
        }

        if ($detailsLink instanceof TagViewHelper) {
            $content        = $detailsLink->html($tag('span', ['class' => 'fas fa-eye'])->openClose() . ' ' . $content);
            $attrs['title'] .= ' (cliquez pour afficher le détail par composante)';
        }

        $res .= $tag('span', $attrs)->html($content);

        return $res;
    }



    public function renderDetails(WorkflowEtape $etape, $collapseId)
    {
        $res = '';
        $tag = $this->getView()->tag();
        /* @var $tag TagViewHelper */

        $res .= $tag('div', ['class' => 'row collapse', 'id' => $collapseId]);
        $res .= $tag('div', ['class' => 'col-md-4 col-md-offset-8']);
        $res .= $tag('ul', ['class' => 'list-group']);
        foreach ($etape->getEtapes() as $sEtape) {
            $attrs     = ['class' => 'list-group-item'];
            $spanTag   = 'span';
            $spanTitle = '';
            if (!$sEtape->getAtteignable()) {
                if ($naDesc = $this->getWhyNonAtteignable($sEtape)) {
                    $attrs['title'] = $naDesc;
                    $spanTag        = 'abbr';
                    $spanTitle      = $naDesc;
                }
                $attrs['class'] .= ' after-courante';
            }
            $res .= $tag('li', $attrs);
            $res .= $tag($spanTag, ['title' => $spanTitle])->text($sEtape->getStructure());

            $res .= $this->renderEtapeIndicateur($sEtape);

            $res .= $tag('li')->close();
        }
        $res .= $tag('ul')->close();
        $res .= $tag('div')->close();
        $res .= $tag('div')->close();

        return $res;
    }



    public function getWhyNonAtteignable($etape, $toArray = false)
    {
        if ($etape instanceof TblWorkflow) {
            $etapes = [$etape];
        } elseif ($etape instanceof WorkflowEtape) {
            $etapes = $etape->getEtapes();
        } else {
            throw new \LogicException('La classe de l\'étape fournie ne peut pas être prise en compte');
        }
        /* @var $etapes TblWorkflow[] */
        $naDesc = $toArray ? [] : '';
        foreach ($etapes as $etp) {
            if ($etp->getObjectif() == 0 && $etp->getEtape()->getDescSansObjectif()) {
                if ($toArray) {
                    $naDesc[] = $etp->getEtape()->getDescSansObjectif();
                } else {
                    $naDesc .= ' - ' . $etp->getEtape()->getDescSansObjectif() . "\n";
                }
            }
            $deps = $etp->getEtapeDeps();
            foreach ($deps as $ed) {
                /* @var $ed WfDepBloquante */
                if ($toArray) {
                    $naDesc[] = $ed->getWfEtapeDep()->getEtapePrec()->getDescNonFranchie();
                } else {
                    $naDesc .= ' - ' . $ed->getWfEtapeDep()->getEtapePrec()->getDescNonFranchie() . "\n";
                }
            }
            break; // pour ne pas répéter!!!
        }

        if (!$toArray && $naDesc) {
            $naDesc = "Non atteignable : \n" . $naDesc;
        }

        return $naDesc;
    }



    public function renderWhyNonAtteignable($etape, $structure = null)
    {
        if (is_string($etape)) {
            $etape = $this->getServiceWorkflow()->getEtape($etape, $this->getIntervenant());
        }

        if ($etape instanceof WorkflowEtape) {
            $etape = $etape->getStructureEtape($structure);
        }

        if (!$etape instanceof TblWorkflow) {
            throw new \LogicException('L\'étape est inexistante ou est fournie dans un format qui ne peut pas être pris en compte');
        }

        /* @var $etape TblWorkflow */
        $deps = $etape->getEtapeDeps();
        foreach ($deps as $dep) {
            $desc = $dep->getWfEtapeDep()->getEtapePrec()->getDescNonFranchie();
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?= $desc ?>
            </div>
            <?php
        }
    }
}