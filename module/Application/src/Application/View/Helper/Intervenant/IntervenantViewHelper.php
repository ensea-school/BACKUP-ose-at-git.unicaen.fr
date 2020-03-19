<?php

namespace Application\View\Helper\Intervenant;

use Application\Constants;
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
                "N° {$entity->getSource()}" => $entity->getSourceCode(),
                "Affectation principale"    => $entity->getStructure() ?: '<span class="inconnu">(Inconnue)</span>',
                "Affectation recherche"     => count($aff = $entity->getAffectation()) ? implode(" ; ", $aff->toArray()) : '<span class="inconnu">(Inconnue)</span>',
                "Discipline"                => $entity->getDiscipline() ?: '<span class="inconnu">(Inconnue)</span>',
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

        //$html .= $this->getView()->historique($entity); => pas de sens ici

        return $html;
    }



    public function renderLink()
    {
        $intervenant = $this->getIntervenant();
        if (!$intervenant) return '';

        if ($intervenant->getHistoDestruction()) {
            return '<span class="bg-danger"><abbr title="Cet intervenant a été supprimé de OSE">' . $intervenant . '</abbr></span>';
        }

        $pourl = $this->getView()->url('intervenant/voir', ['intervenant' => $intervenant->getRouteParam()]);
        $out   = '<a href="' . $pourl . '" data-po-href="' . $pourl . '" class="ajax-modal">' . $intervenant . '</a>';

        return $out;
    }
}