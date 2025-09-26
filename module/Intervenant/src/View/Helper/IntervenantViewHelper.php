<?php

namespace Intervenant\View\Helper;

use Application\Constants;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Laminas\View\Helper\AbstractHtmlElement;
use Utilisateur\Connecteur\LdapConnecteurAwareTrait;

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
    use LdapConnecteurAwareTrait;


    /**
     *
     * @param Intervenant $intervenant
     *
     * @return self
     */
    public function __invoke(?Intervenant $intervenant = null)
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

        $code = $entity->getCode();

        $systemeInformationUrl = $this->getServiceIntervenant()->getSystemeInformationUrl($entity);
        if ($systemeInformationUrl && $this->getView()->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_LIEN_SYSTEME_INFORMATION))) {
            $code = $this->getView()->tag('a', ['href' => $systemeInformationUrl, 'target' => '_blank'])->text($code);
        }

        $statut = $entity->getStatut();
        if ($this->getView()->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_STATUT_VISUALISATION))) {
            $statutUrl = $this->getView()->url('statut/saisie', ['statut' => $statut->getId()]);
            $statut    = $this->getView()->tag('a', ['href' => $statutUrl, 'target' => '_blank'])->text((string)$statut);
        }

        $vars = [
            'identite'     => [
                "Civilité"   => (string)($entity->getCivilite()) ? $entity->getCivilite()->getLibelleLong() : '<span class="inconnu">(Inconnu)</span>',
                "NOM prénom" => $entity,
                //"Date de naissance" => (string)$entity->getDateNaissance()->format(Constants::DATE_FORMAT),
            ],
            'coordonnees'  => [
                "Email"           => $entity->getEmailPro() ?: '<span class="inconnu">(Inconnu)</span>',
                "Téléphone perso" => $entity->getTelPerso() ?: '<span class="inconnu">(Inconnu)</span>',
                "Téléphone pro"   => $entity->getTelPro() ?: '<span class="inconnu">(Inconnu)</span>',
                "Adresse"         => nl2br($entity->getAdresse(false) ?? ''),
            ],
            'metier'       => [
                "Type d'intervenant"       => $entity->getStatut()->getTypeIntervenant(),
                "Statut de l'intervenant"  => $statut,
                "Composante d'affectation" => $entity->getStructure() ?: '<span class="inconnu">(Inconnue)</span>',
                "Grade"                    => $entity->getGrade() ?: '<span class="inconnu">(Inconnue)</span>',
                "Discipline"               => (!empty($entity->getDiscipline()) && $entity->getDiscipline() != '00 Non renseignée') ? $entity->getDiscipline() : '<span class="inconnu">(Inconnue)</span>',
                "Dernière modification le" => $entity->getHistoModification()->format(Constants::DATE_FORMAT),
            ],
            'identifiants' => [
                "Id"                           => $entity->getId(),
                "Code " . $entity->getSource() => $code,
                "Code RH"                      => ($entity->getCodeRh()) ? $entity->getCodeRh() : '<span class="inconnu">(Inconnu)</span>',
                "Login"                        => $this->getConnecteurLdap()->intervenantGetLogin($entity) ?: '<span class="inconnu">(Inconnu)</span>',
            ],
        ];

        $canViewAdresseIntervenant = $this->getView()->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_ADRESSE));
        if (!$canViewAdresseIntervenant) {
            unset($vars['coordonnees']);
        }


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
                $msg .= "<br />" . $this->getView()->tag('a', ['class' => 'no-intranavigation', 'href' => $this->getView()->url('intervenant/restaurer', ['intervenant' => $entity->getId()])])->html('Restaurer la fiche');
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
        $out   = '<a href="' . $pourl . '" data-po-href="' . $pourl . '" class="mod-ajax">' . $intervenant . '</a>';

        return $out;
    }



    public function menuUrl(): string
    {
        $intervenant = $this->getIntervenant();

        if (!$intervenant) return '';

        return (string)$this->getView()->url(null, ['intervenant' => $intervenant->getId()], ['query' => ['menu' => 1]]);

    }



    public function renderTitle(?string $title)
    {
        $title       = $title;
        $intervenant = $this->getIntervenant();
        $v           = $this->getView();

        $canAddIntervenant = $v->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_AJOUT_STATUT));
        $canShowHistorises = $v->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_VISUALISATION_HISTORISES));

        $v->headTitle()->append($intervenant->getNomUsuel())->append($title);
        $title .= ' <small>' . $intervenant . '</small>';

        echo $v->tag('h1', ['class' => 'page-header'])->open();
        echo $title . '<br />';
        $statuts = $this->getStatuts();
        if (!empty($statuts)) {
            echo $v->tag('div', ['class' => 'nav-local intervenant-statuts'])->open();
            echo $v->tag('ul')->open();
            foreach ($statuts as $intervenantId => $iStatut) {
                if ($canShowHistorises || $iStatut->estNonHistorise() || $iStatut == $intervenant) {

                    $liattrs = ['class' => 'nav-item'];
                    $aattrs  = ['class' => 'nav-link', 'href' => $v->url('intervenant/voir', ['intervenant' => $intervenantId])];
                    if ($iStatut == $intervenant) {
                        $liattrs['class'] .= " active";
                    } else {
                        $liattrs['title'] = 'Cliquez pour afficher';
                    }
                    if ($iStatut->estHistorise()) {
                        $liattrs['class'] .= ' historise';
                    }
                    echo $v->tag('li', $liattrs)->open();
                    echo $v->tag('a', $aattrs)->open();
                    echo $v->tag('span', ['class' => 'type-intervenant'])->html($iStatut->getStatut()->getTypeIntervenant());
                    echo $v->tag('span', ['class' => 'validite-intervenant'])->html($iStatut->getValidite());
                    echo '<br />';
                    echo $v->tag('span', ['class' => 'statut'])->html($iStatut->getStatut()->getLibelle());
                    if ($iStatut->estHistorise()) echo $v->tag('i', ['class' => 'text-danger fas fa-triangle-exclamation', 'title' => 'Intervenant historisé'])->text('');
                    echo $v->tag('a')->close();
                    echo $v->tag('li')->close();
                }
            }

            if ($canAddIntervenant && $intervenant->getId()) {
                echo $v->tag('li', ['class' => 'ajout-intervenant float-end'])->html(
                    $v->tag('a', [
                            'href'  => $v->url('intervenant/dupliquer', ['intervenant' => $intervenant->getId()]),
                            'title' => 'Ajout d\'un nouveau statut à l\'intervenant',
                        ]
                    )->html($v->tag('i', ['class' => 'fas fa-plus'])->html(''))
                );
            }
            echo $v->tag('ul')->close();
            echo $v->tag('div', ['style' => 'clear:both'])->html();
            echo $v->tag('div')->close();
        }
        echo $v->tag('h1')->close();
    }



    protected function getStatuts()
    {
        $intervernants = $this->getServiceIntervenant()->getIntervenants($this->getIntervenant());
        $statuts       = [];
        foreach ($intervernants as $intervenant) {
            if ($intervenant->getStatut()) {
                $statuts[$intervenant->getId()] = $intervenant;
            }
        }
        uasort($statuts, function ($a, $b) {
            return $a->getStatut()->getOrdre() - $b->getStatut()->getOrdre();
        });

        return $statuts;
    }

}