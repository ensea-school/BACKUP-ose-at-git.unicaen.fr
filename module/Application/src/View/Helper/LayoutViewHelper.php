<?php

namespace Application\View\Helper;

use Application\Acl\Role;
use Application\Entity\Db\Utilisateur;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\View\Helper\AbstractHtmlElement;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Description of UtilisateurViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class LayoutViewHelper extends AbstractHtmlElement
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use SessionContainerTrait;

    private bool $usurpationEnabled = false;
    private bool $usurpationEnCours = false;



    /**
     *
     * @param Utilisateur $utilisateur
     * @param string      $title
     * @param string      $subject
     * @param string      $body
     *
     * @return string
     */
    public function __invoke()
    {
        return $this;
    }



    public function connexionData(): array
    {
        $utilisateur = $this->getServiceContext()->getUtilisateur();

        if (!$utilisateur) {
            return ['connecte' => false];
        }

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $roleNom = $role->getRoleId() == 'role' ? 'Authentifié(e)' : $role->getRoleName();

        /** @var Role $sRoles */
        $sRoles         = $this->getServiceContext()->getServiceUserContext()->getSelectableIdentityRoles();
        $roles          = [];
        $needStructures = false;
        foreach ($sRoles as $r) {
            if (!$needStructures && $r->getPeutChangerStructure()) {
                $needStructures = true;
            }
            $roles[$r->getRoleId()] = [
                'libelle'              => $r->getRoleName(),
                'peutChangerStructure' => $r->getPeutChangerStructure(),
            ];
        }

        $structures = [];
        if ($needStructures) {
            $structures = $this->getStructures();
        }

        return [
            'utilisateurNom'    => $utilisateur->getDisplayName(),
            'roleNom'           => $roleNom,
            'roleId'            => $role->getRoleId(),
            'connecte'          => true,
            'usurpationEnabled' => $this->isUsurpationEnabled(),
            'usurpationEnCours' => $this->isUsurpationEnCours(),
            'roles'             => $roles,
            'structureId'       => null,
            'structures'        => $structures,
        ];
    }



    /**
     * Retourne la liste des structures associées à des rôles.
     *
     * @return array
     */
    private function getStructures()
    {
        $session = $this->getSessionContainer();
        if (!isset($session->structures)) {
            $qb                  = $this->getServiceStructure()->finderByHistorique();
            $s                   = $this->getServiceStructure()->getList($qb);
            $session->structures = [];
            foreach ($s as $structure) {
                if ($structure->getLevel() > 0) {
                    $session->structures[$structure->getId()] = str_repeat('&nbsp;', $structure->getLevel() * 4) . (string)$structure;
                } else {
                    $session->structures[$structure->getId()] = (string)$structure;
                }
            }
        }

        return $session->structures;
    }



    public function isUsurpationEnabled(): bool
    {
        return $this->usurpationEnabled;
    }



    public function setUsurpationEnabled(bool $usurpationEnabled): LayoutViewHelper
    {
        $this->usurpationEnabled = $usurpationEnabled;
        return $this;
    }



    public function isUsurpationEnCours(): bool
    {
        return $this->usurpationEnCours;
    }



    public function setUsurpationEnCours(bool $usurpationEnCours): LayoutViewHelper
    {
        $this->usurpationEnCours = $usurpationEnCours;
        return $this;
    }


}