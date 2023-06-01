<?php

namespace Application\Service;

use Application\Acl\Role;
use Application\Connecteur\Traits\LdapConnecteurAwareTrait;
use Application\Entity\Db\Affectation;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Parametre;
use Application\Entity\Db\Structure;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Utilisateur;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use UnicaenAuthentification\Service\Traits\UserContextServiceAwareTrait;

/**
 * Service fournissant les différents contextes de fonctionnement de l'application.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ContextService extends AbstractService
{
    use Traits\EtablissementServiceAwareTrait;
    use Traits\AnneeServiceAwareTrait;
    use Traits\ParametresServiceAwareTrait;
    use Traits\StructureServiceAwareTrait;
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



    public function getUtilisateur(): ?Utilisateur
    {
        return $this->getConnecteurLdap()->getUtilisateurCourant();
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