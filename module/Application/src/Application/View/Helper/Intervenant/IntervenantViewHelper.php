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
                "NOM prénom"        => $entity,
                "Civilité"          => (string)$entity->getCivilite(),
                "Date de naissance" => (string)$entity->getDateNaissance()->format(Constants::DATE_FORMAT),
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

            $canRestaure = $this->getView()->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_AJOUT_STATUT));
            if ($canRestaure) {
                $msg .= "<br />" . $this->getView()->tag('a', ['href' => $this->getView()->url('intervenant/restaurer', ['intervenant' => $entity->getId()])])->html('Restaurer la fiche');
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

        $canAddIntervenant = $this->getView()->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_AJOUT_STATUT));

        $this->getView()->headTitle()->append($intervenant->getNomUsuel())->append($title);
        $title .= ' <small>' . $intervenant . '</small>';
        if ($intervenant->estHistorise()) {
            $title .= ' ' . $this->getView()->tag('span', ['class' => 'text-danger glyphicon glyphicon-warning-sign', 'title' => 'Intervenant historisé'])->text('');
        }

        echo $this->getView()->tag('h1', ['class' => 'page-header'])->open();
        echo $title . '<br />';
        $statuts = $this->getStatuts();
        if (!empty($statuts)) {
            ?>
            <nav class="navbar navbar-default intervenant-statuts">
                <ul class="nav navbar-nav">
                    <?php foreach ($statuts as $intervenantId => $statut): ?>
                        <li<?= ($statut == $intervenant->getStatut()) ? ' class="active"' : ' title="Cliquez pour afficher"' ?>>
                            <a href="<?= $this->getView()->url(null, ['intervenant' => $intervenantId]); ?>">
                                <span class="type-intervenant"><?= $statut->getTypeIntervenant() ?></span>
                                <span class="validite-intervenant"><?= $intervenant->getValidite(); ?></span><br/>
                                <span class="statut-intervenant"><?= $statut->getLibelle() ?></span>

                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($canAddIntervenant && $intervenant->getId()): ?>
                        <li class="ajout-intervenant">
                            <a href="<?= $this->getView()->url('intervenant/dupliquer', ['intervenant' => $intervenant->getId()]); ?>"
                               title="Ajout d'un nouveau statut à l'intervenant"><span
                                        class="glyphicon glyphicon-plus"></span></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php
        }
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