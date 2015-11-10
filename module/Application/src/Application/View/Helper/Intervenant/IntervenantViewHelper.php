<?php

namespace Application\View\Helper\Intervenant;

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

        $adresse = $entity->getAdressePrincipale();

        $vars = [
            'identite'    => [
                "NOM prénom"         => $entity,
                "Civilité"           => $entity->getCiviliteToString(),
                "Date de naissance"  => $entity->getDateNaissanceToString(),
                "Ville de naissance" => $entity->getVilleNaissanceLibelle() ?: "(Inconnue)",
                "Pays de naissance"  => $entity->getPaysNaissanceLibelle(),
                "N° INSEE"           => $entity->getNumeroInsee(),
            ],
            'coordonnees' => [
                "Email"            => $entity->getEmail() ?: "(Inconnu)",
                "Téléphone mobile" => $entity->getTelMobile() ?: "(Inconnu)",
                "Téléphone pro"    => $entity->getTelPro() ?: "(Inconnu)",
                "Adresse"          => nl2br($entity->getAdressePrincipale()),
            ],
            'metier'      => [
                "Type d'intervenant"                => $entity->getType(),
                "Statut de l'intervenant"           => $entity->getStatut(),
                "N° {$entity->getSource()}"         => $entity->getSourceCode(),
                "Affectation principale"            => $entity->getStructure() ?: "(Inconnue)",
                "Affectation recherche"             => count($aff = $entity->getAffectation()) ? implode(" ; ", $aff->toArray()) : "(Inconnue)",
                "Discipline"                        => $entity->getDiscipline() ?: "(Inconnue)",
                "Grade"                             => $entity->getGrade() ?: "(Aucun ou inconnu)",
            ],
            'divers'      => [
                "Id"              => $entity->getId(),
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

        $pourl = $this->getView()->url('intervenant/default', ['action' => 'apercevoir', 'intervenant' => $intervenant->getSourceCode()]);
        $out   = '<a href="' . $pourl . '" data-po-href="' . $pourl . '" class="ajax-modal">' . $intervenant . '</a>';

        return $out;
    }
}