<?php

namespace Application\Service;

use Application\Acl\Role;
use Application\Connecteur\Traits\LdapConnecteurAwareTrait;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Parametre;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Utilisateur;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use DateTime;
use UnicaenAuth\Service\Traits\UserContextServiceAwareTrait;

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

    /**
     * selectedIdentityRole
     *
     * @var RoleService
     */
    protected $selectedIdentityRole;

    /**
     * @var Etablissement
     */
    protected $etablissement;

    /**
     * @var Annee
     */
    protected $annee;

    /**
     * @var Annee
     */
    protected $anneeImport;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    protected $intervenant = false;

    /**
     * @var Annee
     */
    protected $anneePrecedente;

    /**
     * @var Annee
     */
    protected $anneeSuivante;

    /**
     * @var DateTime
     */
    protected $dateObservation;

    /**
     * @var Structure
     */
    protected $structure;

    /**
     * parametres
     *
     * @var ParametresService
     */
    protected $parametres;

    /**
     * @var bool
     */
    protected $inInit = false;



    /**
     *
     * @return Role
     */
    public function getSelectedIdentityRole()
    {
        if (null === $this->selectedIdentityRole) {
            if ($this->serviceUserContext->getIdentity()) {
                $this->selectedIdentityRole = $this->serviceUserContext->getSelectedIdentityRole();
                if (!$this->selectedIdentityRole instanceof Role) $this->selectedIdentityRole = new Role();
            }
        }

        return $this->selectedIdentityRole;
    }



    /**
     * @return Utilisateur|null
     */
    public function getUtilisateur()
    {
        return $this->getConnecteurLdap()->getUtilisateurCourant();
    }



    /**
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        if (false === $this->intervenant || $this->serviceUserContext->getNextSelectedIdentityRole()) {
            $utilisateurCode = $this->getConnecteurLdap()->getUtilisateurCourantCode();
            if ($utilisateurCode) {
                $this->intervenant = $this->getServiceIntervenant()->getByUtilisateurCode($utilisateurCode);
            } else {
                return null;
            }
        }

        return $this->intervenant;
    }



    /**
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return ContextService
     */
    public function setIntervenant($intervenant)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     *
     * @return Etablissement
     */
    public function getEtablissement()
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
     * @param string|TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return string
     */
    public function getModaliteServices($typeVolumeHoraire = TypeVolumeHoraire::CODE_PREVU): string
    {
        if ($typeVolumeHoraire instanceof TypeVolumeHoraire) {
            $typeVolumeHoraire = $typeVolumeHoraire->getCode();
        }

        if ($typeVolumeHoraire == TypeVolumeHoraire::CODE_REALISE) {
            $prevReal = 'real';
        } else {
            $prevReal = 'prev';
        }

        return $this->getServiceParametres()->get('modalite_services_' . $prevReal . '_ens');
    }



    /**
     * @return bool
     */
    public function isModaliteServicesSemestriel($typeVolumeHoraire = TypeVolumeHoraire::CODE_PREVU): bool
    {
        return $this->getModaliteServices($typeVolumeHoraire) == Parametre::SERVICES_MODALITE_SEMESTRIEL;
    }



    /**
     * @param string|TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return string
     */
    public function getModaliteReferentiel($typeVolumeHoraire = TypeVolumeHoraire::CODE_PREVU): string
    {
        if ($typeVolumeHoraire instanceof TypeVolumeHoraire) {
            $typeVolumeHoraire = $typeVolumeHoraire->getCode();
        }

        if ($typeVolumeHoraire == TypeVolumeHoraire::CODE_REALISE) {
            $prevReal = 'real';
        } else {
            $prevReal = 'prev';
        }

        return $this->getServiceParametres()->get('modalite_services_' . $prevReal . '_ref');
    }



    /**
     * @return bool
     */
    public function isModaliteReferentielSemestriel($typeVolumeHoraire = TypeVolumeHoraire::CODE_PREVU): bool
    {
        return $this->getModaliteReferentiel() == Parametre::SERVICES_MODALITE_SEMESTRIEL;
    }



    /**
     *
     * @param Etablissement $etablissement
     * @param boolean       $updateParametres
     *
     * @return self
     */
    public function setEtablissement(Etablissement $etablissement, $updateParametres = false)
    {
        $this->etablissement                        = $etablissement;
        $this->getSessionContainer()->etablissement = $etablissement->getId();
        if ($updateParametres) {
            $this->getServiceParametres()->etablissement = (string)$etablissement->getId();
        }

        return $this;
    }



    /**
     * Retourne l'année courante.
     * C'est à dire :
     * - celle mémorisée en session (car sélectionnée par l'utilisateur) si elle existe ;
     * - ou sinon celle spécifiée dans les paramètres de l'appli.
     *
     * @return Annee
     */
    public function getAnnee()
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
     *
     * @return Annee
     */
    public function getAnneeImport()
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



    /**
     * Retourne l'année N - x, N étant l'année courante.
     *
     * @param int $x Entier supérieur ou égal à zéro
     *
     * @return Annee
     */
    public function getAnneeNmoins($x)
    {
        return $this->getServiceAnnee()->getNmoins($this->getAnnee(), $x);
    }



    /**
     *
     * @return Annee
     */
    public function getAnneePrecedente()
    {
        if (!$this->anneePrecedente) {
            $this->anneePrecedente = $this->getServiceAnnee()->getPrecedente($this->getAnnee());
        }

        return $this->anneePrecedente;
    }



    /**
     *
     * @return Annee
     */
    public function getAnneeSuivante()
    {
        if (!$this->anneeSuivante) {
            $this->anneeSuivante = $this->getServiceAnnee()->getSuivante($this->getAnnee());
        }

        return $this->anneeSuivante;
    }



    /**
     *
     * @param Annee   $annee
     * @param boolean $updateParametres
     *
     * @return self
     */
    public function setAnnee(Annee $annee, $updateParametres = false)
    {
        $this->annee                        = $annee;
        $this->getSessionContainer()->annee = $annee->getId();
        if ($updateParametres) {
            $this->getServiceParametres()->annee = (string)$annee->getId();
        }
        /* Rafraîchit les années précédentes et suivantes par la même occasion!! */
        $this->getAnneePrecedente();
        $this->getAnneeSuivante();

        return $this;
    }



    /**
     *
     * @return DateTime
     */
    function getDateObservation()
    {
        $sc = $this->getSessionContainer();
        if (!$sc->offsetExists('dateObservation')) {
            $sc->dateObservation = null;
        }

        return $sc->dateObservation;
    }



    /**
     *
     * @param DateTime $dateObservation
     *
     * @return self
     */
    public function setDateObservation(DateTime $dateObservation)
    {
        $sc                  = $this->getSessionContainer();
        $sc->dateObservation = $dateObservation;

        return $this;
    }



    /**
     *
     * @return Structure
     */
    public function getStructure($checkInRole = true)
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



    /**
     *
     * @param Structure $structure
     * @param boolean   $updateParametres
     *
     * @return self
     */
    public function setStructure($structure)
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



    /**
     * Détermine si l'application était opérationnelle l'année spécifiée.
     *
     * On considère que s'il existe des intervenants pour l'année spécifiée, alors
     * l'application était opérationnelle.
     *
     * @param Annee $annee
     *
     * @return boolean
     */
    public function applicationExists(Annee $annee)
    {
        $sql = "
        SELECT
          'OK' ok
        FROM
          intervenant i 
        WHERE
          i.annee_id = " . ((int)$annee->getId()) . "
          AND i.histo_destruction IS NULL
          AND rownum = 1
        ";
        $res = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();

        return count($res) == 1;
    }



    /**
     * @return bool
     */
    public function isInInit(): bool
    {
        return $this->inInit;
    }



    /**
     * @param bool $inInit
     *
     * @return ContextService
     */
    public function setInInit(bool $inInit): ContextService
    {
        $this->inInit = $inInit;

        return $this;
    }

}