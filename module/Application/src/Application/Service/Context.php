<?php

namespace Application\Service;

use Application\Acl\Role;
use Application\Entity\Db\Etablissement as EntityEtablissement;
use Application\Entity\Db\Annee as AnneeEntity;
use Application\Entity\Db\Structure as StructureEntity;
use UnicaenApp\Traits\SessionContainerTrait;
use DateTime;

/**
 * Service fournissant les différents contextes de fonctionnement de l'application.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Context extends AbstractService
{
    use Traits\EtablissementAwareTrait;
    use Traits\AnneeAwareTrait;
    use Traits\IntervenantAwareTrait;
    use Traits\ParametresAwareTrait;
    use Traits\StructureAwareTrait;
    use SessionContainerTrait;

    /**
     * selectedIdentityRole
     *
     * @var Role
     */
    protected $selectedIdentityRole;

    /**
     * @var EntityEtablissement
     */
    protected $etablissement;

    /**
     * @var AnneeEntity
     */
    protected $annee;

    /**
     * @var AnneeEntity
     */
    protected $anneePrecedente;

    /**
     * @var AnneeEntity
     */
    protected $anneeSuivante;

    /**
     * @var DateTime
     */
    protected $dateObservation;

    /**
     * @var StructureEntity
     */
    protected $structure;

    /**
     * parametres
     *
     * @var Parametres
     */
    protected $parametres;




    /**
     *
     * @return Role
     */
    public function getSelectedIdentityRole()
    {
        if (null === $this->selectedIdentityRole) {
            $authUserContext = $this->getServiceLocator()->get('authUserContext');
            /* @var $authUserContext \UnicaenAuth\Service\UserContext */

            if ($authUserContext->getIdentity()) {
                $this->selectedIdentityRole = $authUserContext->getSelectedIdentityRole();
                if (! $this->selectedIdentityRole instanceof Role) $this->selectedIdentityRole = new Role();
            }
        }

        return $this->selectedIdentityRole;
    }



    /**
     *
     * @return EntityEtablissement
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
     *
     * @param EntityEtablissement $etablissement
     * @param boolean             $updateParametres
     *
     * @return self
     */
    public function setEtablissement(EntityEtablissement $etablissement, $updateParametres = false)
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
     * @return AnneeEntity
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
     * Retourne l'année N - x, N étant l'année courante.
     *
     * @param int $x Entier supérieur ou égal à zéro
     *
     * @return AnneeEntity
     */
    public function getAnneeNmoins($x)
    {
        return $this->getServiceAnnee()->getNmoins($this->getAnnee(), $x);
    }



    /**
     *
     * @return AnneeEntity
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
     * @return AnneeEntity
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
     * @param AnneeEntity $annee
     * @param boolean     $updateParametres
     *
     * @return self
     */
    public function setAnnee(AnneeEntity $annee, $updateParametres = false)
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
        $sc->dateObservation = $dateObservation;

        return $this;
    }



    /**
     *
     * @return StructureEntity
     */
    public function getStructure($initializing = false)
    {
        if (!$this->structure) {
            $sc = $this->getSessionContainer();
            if (!$sc->offsetExists('structure')) {
                if ($initializing) {
                    $sc->structure = null;
                } else {
                    $role = $this->getSelectedIdentityRole();
                    if ($role && $role->getStructure()) {
                        $sc->structure = $role->getStructure()->getId();
                    } else {
                        $sc->structure = null;
                    }
                }
            }
            $this->structure = $this->getServiceStructure()->get($sc->structure);
        }

        return $this->structure;
    }



    /**
     *
     * @param StructureEntity $structure
     * @param boolean         $updateParametres
     *
     * @return self
     */
    public function setStructure($structure)
    {
        if ($structure instanceof StructureEntity) {
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
     * @param AnneeEntity $annee
     *
     * @return boolean
     */
    public function applicationExists(AnneeEntity $annee)
    {
        $sql = "
        SELECT
          'OK' ok
        FROM
          intervenant i 
        WHERE
          i.annee_id = ".((int)$annee->getId())."
          AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
          AND rownum = 1
        ";
        $res = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();

        return count($res) == 1;
    }
}