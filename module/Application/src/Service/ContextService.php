<?php

namespace Application\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Annee;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Etablissement;
use Lieu\Entity\Db\Structure;
use Lieu\Service\EtablissementServiceAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use UnicaenAuthentification\Service\Traits\UserContextServiceAwareTrait;
use Utilisateur\Acl\Role;
use Utilisateur\Connecteur\LdapConnecteurAwareTrait;
use Utilisateur\Entity\Db\Affectation;
use Utilisateur\Entity\Db\Utilisateur;

/**
 * Service fournissant les différents contextes de fonctionnement de l'application.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ContextService extends AbstractService
{
    use EtablissementServiceAwareTrait;
    use Traits\AnneeServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use StructureServiceAwareTrait;
    use SessionContainerTrait;
    use UserContextServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use LdapConnecteurAwareTrait;
    use LocalContextServiceAwareTrait;


    protected ?Role          $selectedIdentityRole = null;

    protected ?Etablissement $etablissement        = null;

    protected ?Annee         $annee                = null;

    protected ?Annee         $anneeImport          = null;

    protected ?Intervenant   $intervenant          = null;

    protected bool           $intervenantInit      = false;

    protected ?Annee         $anneePrecedente      = null;

    protected ?Annee         $anneeSuivante        = null;

    protected ?Structure     $structure            = null;

    protected ?Utilisateur   $utilisateur          = null;

    protected bool           $inInit               = false;



    public function getSelectedIdentityRole(): ?Role
    {
        if (null === $this->selectedIdentityRole) {
            if ($this->serviceUserContext->getIdentity()) {
                $this->selectedIdentityRole = $this->serviceUserContext->getSelectedIdentityRole();
                if (!$this->selectedIdentityRole instanceof Role) $this->selectedIdentityRole = new Role();
            }
        }

        return $this->selectedIdentityRole;
    }



    public function refreshRoleStatut (Statut $statut): void
    {
        $this->serviceUserContext->clearIdentityRoles();
        \Framework\Application\Application::getInstance()->container()->get(\Utilisateur\Provider\IdentityProvider::class)->clearIdentityRoles();
        \Framework\Application\Application::getInstance()->container()->get(\Utilisateur\Provider\RoleProvider::class)->clearRoles();
        $this->serviceUserContext->setSelectedIdentityRole($statut->getRoleId());
    }



    public function getUtilisateur(): ?Utilisateur
    {
        if (null === $this->utilisateur) {
            $this->utilisateur = $this->getConnecteurLdap()->getUtilisateurCourant();
        }
        return $this->utilisateur;
    }



    public function getIntervenant(): ?Intervenant
    {
        if (!$this->intervenantInit || $this->serviceUserContext->getNextSelectedIdentityRole()) {
            $this->intervenantInit = true;

            $sc = $this->getSessionContainer();
            if (!$this->intervenant && ($id = $sc->intervenantId)) {
                $this->intervenant = $this->getServiceIntervenant()->get($id);
            } else {
                $utilisateurCode = $this->getConnecteurLdap()->getUtilisateurCourantCode();
                if ($utilisateurCode) {
                    $this->intervenant = $this->getServiceIntervenant()->getByUtilisateurCode($utilisateurCode);
                } else {
                    return null;
                }
            }
        }

        return $this->intervenant;
    }



    public function getAffectation(): ?Affectation
    {
        $role        = $this->getSelectedIdentityRole();
        $utilisateur = $this->getUtilisateur();

        if (!$role) return null;
        if (!$utilisateur) return null;

        $params      = [
            'utilisateur'      => $utilisateur,
            'role'             => $role->getDbRole(),
            'structure'        => $role->getPerimetre()->isComposante() ? $this->getStructure() : null,
            'histoDestruction' => null,
        ];
        $affectation = $this->getEntityManager()->getRepository(Affectation::class)->findOneBy($params);

        return $affectation;
    }



    public function setIntervenant(?Intervenant $intervenant): self
    {
        $this->intervenant = $intervenant;
        $sc                = $this->getSessionContainer();
        $sc->intervenantId = $intervenant ? $intervenant->getId() : null;

        return $this;
    }



    public function getEtablissement(): ?Etablissement
    {
        if (!$this->etablissement) {
            $sc = $this->getSessionContainer();
            if (!$sc->offsetExists('etablissement')) {
                $sc->etablissement = (int)$this->getServiceParametres()->etablissement;
            }
            $this->etablissement = $this->getServiceEtablissement()->get($sc->etablissement);
        }

        return $this->etablissement;
    }



    /**
     * Retourne l'année courante.
     * C'est à dire :
     * - celle mémorisée en session (car sélectionnée par l'utilisateur) si elle existe ;
     * - ou sinon celle spécifiée dans les paramètres de l'appli.
     */
    public function getAnnee(): Annee
    {
        if (!$this->annee) {
            $sc = $this->getSessionContainer();
            if (!$sc->offsetExists('annee')) {
                $sc->annee = (int)$this->getServiceParametres()->annee;
            }

            $this->annee = $this->getServiceAnnee()->get($sc->annee);
        }

        return $this->annee;
    }



    /**
     * Retourne l'année courante d'import.
     * C'est à dire :
     * - celle mémorisée en session (car sélectionnée par l'utilisateur) si elle existe ;
     * - ou sinon celle spécifiée dans les paramètres de l'appli.
     */
    public function getAnneeImport(): Annee
    {
        if (!$this->anneeImport) {
            $sc = $this->getSessionContainer();
            if (!$sc->offsetExists('anneeImport')) {
                $sc->anneeImport = (int)$this->getServiceParametres()->get('annee_import');
            }

            $this->anneeImport = $this->getServiceAnnee()->get($sc->anneeImport);
        }

        return $this->anneeImport;
    }



    public function getAnneePrecedente(): Annee
    {
        if (!$this->anneePrecedente) {
            $this->anneePrecedente = $this->getServiceAnnee()->getPrecedente($this->getAnnee());
        }

        return $this->anneePrecedente;
    }



    public function getAnneeSuivante(): Annee
    {
        if (!$this->anneeSuivante) {
            $this->anneeSuivante = $this->getServiceAnnee()->getSuivante($this->getAnnee());
        }

        return $this->anneeSuivante;
    }



    public function setAnnee(Annee $annee): self
    {
        $this->annee                        = $annee;
        $this->getSessionContainer()->annee = $annee->getId();

        /* Rafraîchit les années précédentes et suivantes par la même occasion!! */
        $this->getAnneePrecedente();
        $this->getAnneeSuivante();

        return $this;
    }



    public function getStructure(bool $checkInRole = true): ?Structure
    {
        if ($checkInRole && !$this->isInInit()) {
            $role = $this->getSelectedIdentityRole();
            if ($role && $role->getStructure()) {
                return $role->getStructure();
            }
        }

        if (!$this->structure) {
            $sc          = $this->getSessionContainer();
            $structureId = $sc->structure;
            if ($structureId) {
                $this->structure = $this->getServiceStructure()->get($structureId);
            }
        }

        return $this->structure;
    }



    public function setStructure(?Structure $structure): self
    {
        if ($structure instanceof Structure) {
            $this->structure                        = $structure;
            $this->getSessionContainer()->structure = $structure->getId();
        } else {
            $this->structure                        = null;
            $this->getSessionContainer()->structure = null;
        }

        return $this;
    }



    public function isInInit(): bool
    {
        return $this->inInit;
    }



    public function setInInit(bool $inInit): ContextService
    {
        $this->inInit = $inInit;

        return $this;
    }

}