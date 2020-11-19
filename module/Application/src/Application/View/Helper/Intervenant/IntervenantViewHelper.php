<?php

namespace Application\View\Helper\Intervenant;

use Application\Constants;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\IntervenantAwareTrait;

/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantViewHelper extends AbstractHtmlElement
{
    use IntervenantAwareTrait;
    use ContextServiceAwareTrait;
    use IntervenantServiceAwareTrait;


    /**
     *
     * @param Intervenant $intervenant
     *
     * @return self
     */
    public function __invoke(Intervenant $intervenant = null)
    {
        if ($intervenant) $this->setIntervenant($intervenant);

        return $this;
    }



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
        $entity = $this->getIntervenant();

        if (!$entity) {
            return '';
        }

        $vars = [
            'identite'    => [
                "NOM prénom"           => $entity,
                "Civilité"             => (string)$entity->getCivilite(),
                "Date de naissance"    => (string)$entity->getDateNaissance()->format(Constants::DATE_FORMAT),
                "Commune de naissance" => (string)$entity->getCommuneNaissance() ?: '<span class="inconnu">(Inconnue)</span>',
                "Pays de naissance"    => (string)$entity->getPaysNaissance(),
            ],
            'coordonnees' => [
                "Email"           => $entity->getEmailPro() ?: '<span class="inconnu">(Inconnu)</span>',
                "Téléphone perso" => $entity->getTelPerso() ?: '<span class="inconnu">(Inconnu)</span>',
                "Téléphone pro"   => $entity->getTelPro() ?: '<span class="inconnu">(Inconnu)</span>',
                "Adresse"         => nl2br($entity->getAdresse(false)),
            ],
            'metier'      => [
                "Type d'intervenant"        => $entity->getStatut()->getTypeIntervenant(),
                "Statut de l'intervenant"   => $entity->getStatut(),
                "N° {$entity->getSource()}" => $entity->getCode(),
                "Affectation principale"    => $entity->getStructure() ?: '<span class="inconnu">(Inconnue)</span>',
                "Montant de l'indemnité FC" => $entity->getMontantIndemniteFc() !== null ? \UnicaenApp\Util::formattedEuros($entity->getMontantIndemniteFc()) : '<span class="inconnu">(Inconnue)</span>',
            ],
            'divers'      => [
                "Id" => $entity->getId(),
                //"Id de connexion" => ($u = $entity->getUtilisateur()) ? $u->getUsername() : "(Aucun)",
            ],
        ];

        $html = '';
        foreach ($vars as $bloc => $vvs) {
            $html .= "<dl class=\"intervenant intervenant-$bloc dl-horizontal\">\n";
            foreach ($vvs as $key => $value) {
                $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
            }
            $html .= "</dl>";
        }

        if ($entity->getHistoDestruction()) {
            $msg = 'Cet intervenant a été supprimé de OSE le ' . $entity->getHistoDestruction()->format(Constants::DATE_FORMAT) . '.';

            if ($entity->getSource()->getCode() !== \Application\Service\SourceService::CODE_SOURCE_OSE) {
                $msg .= ' Sa fiche ne remonte plus depuis l\'application ' . $entity->getSource() . '.';
            }

            $html .= '<div class="alert alert-danger">' . $msg . '</div>';
        }

        return $html;
    }



    public function renderLink()
    {
        $intervenant = $this->getIntervenant();
        if (!$intervenant) return '';

        if ($intervenant->getHistoDestruction()) {
            return '<span class="bg-danger"><abbr title="Cet intervenant a été supprimé de OSE">' . $intervenant . '</abbr></span>';
        }

        $pourl = $this->getView()->url('intervenant/voir', ['intervenant' => $intervenant->getId()]);
        $out   = '<a href="' . $pourl . '" data-po-href="' . $pourl . '" class="ajax-modal">' . $intervenant . '</a>';

        return $out;
    }



    public function renderTitle(?string $title)
    {
        $title       = $title;
        $intervenant = $this->getIntervenant();

        /*if ($this->getServiceContext()->getIntervenant() == $intervenant) {

        } else {

        }*/

        //echo $intervenant . ' <small>' . $intervenant->getStatut() . '</small>';

        $canAddIntervenant = $this->getView()->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_AJOUT_STATUT));

        $this->getView()->headTitle()->append($intervenant->getNomUsuel())->append($title);
        $title .= ' <small>' . $intervenant . '</small>';

        echo $this->getView()->tag('h1', ['class' => 'page-header'])->open();
        echo $title . '<br />';
        $statuts = $this->getStatuts();
        ?>
        <nav class="navbar navbar-default intervenant-statuts">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Statuts</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <span class="navbar-brand" href="#">Statut<?= (count($statuts) > 1) ? 's' : '' ?></span>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php foreach ($statuts as $intervenantId => $statut): ?>
                            <li<?= ($statut == $intervenant->getStatut()) ? ' class="active"' : '' ?>>
                                <a href="<?= $this->getView()->url(null, ['intervenant' => $intervenantId]); ?>"><span
                                            class="type-intervenant"><?= $statut->getTypeIntervenant() . '</span><br />' . $statut->getLibelle() ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <?php if ($canAddIntervenant): ?>
                            <li class="ajout-intervenant">
                                <a href="<?= $this->getView()->url('intervenant/dupliquer', ['intervenant' => $intervenantId]); ?>"
                                   title="Ajout d'un nouveau statut à l'intervenant"><span
                                            class="glyphicon glyphicon-plus"></span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <?php
        echo $this->getView()->tag('h1')->close();
    }



    protected function getStatuts()
    {
        $intervernants = $this->getServiceIntervenant()->getIntervenants($this->getIntervenant());
        $statuts       = [];
        foreach ($intervernants as $intervenant) {
            if ($intervenant->estNonHistorise() && $intervenant->getStatut()) {
                $statuts[$intervenant->getId()] = $intervenant->getStatut();
            }
        }
        uasort($statuts, function ($a, $b) {
            return $a->getOrdre() > $b->getOrdre();
        });

        return $statuts;
    }
}