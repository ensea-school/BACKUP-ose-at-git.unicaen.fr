<?php

namespace Application\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Annee;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Framework\User\UserManager;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Etablissement;
use Lieu\Entity\Db\Structure;
use Lieu\Service\EtablissementServiceAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
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
    use IntervenantServiceAwareTrait;
    use LdapConnecteurAwareTrait;
    use LocalContextServiceAwareTrait;


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



    public function __construct(
        private readonly UserManager $userManager,
    )
    {
    }



    public function getUtilisateur(): ?Utilisateur
    {
        return $this->userManager->getUser();
    }



    public function getIntervenant(): ?Intervenant
    {
        $profile = $this->userManager->getProfile();

        return $profile?->getContext('intervenant');
    }



    public function getAffectation(): ?Affectation
    {
        $profile = $this->userManager->getProfile();

        return $profile?->getContext('affectation');
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



    public function getStructure(): ?Structure
    {
        $profile = $this->userManager->getProfile();

        return $profile?->getContext('structure');
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