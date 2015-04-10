<?php

namespace Application\Service;

use \Application\Acl\Role;
use Application\Entity\Db\Etablissement as EntityEtablissement;
use Application\Entity\Db\Annee         as AnneeEntity;
use Application\Entity\Db\Structure     as StructureEntity;
use \DateTime;
use Zend\Session\Container;
use \Application\Interfaces\StructureAwareInterface;

/**
 * Service fournissant les différents contextes de fonctionnement de l'application.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Context extends AbstractService
{
    use Traits\EtablissementAwareTrait,
        Traits\AnneeAwareTrait,
        Traits\IntervenantAwareTrait,
        Traits\ParametresAwareTrait,
        Traits\StructureAwareTrait
    ;

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
     * @var Container
     */
    protected $sessionContainer;


    


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
                $utilisateur = $authUserContext->getDbUser();
                if ($utilisateur){
                    if ($this->selectedIdentityRole instanceof IntervenantAwareInterface) {
                        $intervenant = $utilisateur->getIntervenant();
                        if (! $intervenant){ // sinon import automatique
                            $iSourceCode = (int)$authUserContext->getLdapUser()->getSupannEmpId();
                            $intervenant = $this->getServiceIntervenant()->importer($iSourceCode);
                        }
                        $this->selectedIdentityRole->setIntervenant( $intervenant );
                    }
                    if ($this->selectedIdentityRole instanceof PersonnelAwareInterface){
                        $this->selectedIdentityRole->setPersonnel( $utilisateur->getPersonnel() );
                    }
                }
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
        if (! $this->etablissement){
            $sc = $this->getSessionContainer();
            if (! $sc->offsetExists('etablissement')){
                $sc->etablissement = (int)$this->getServiceParametres()->etablissement;
            }
            $this->etablissement = $this->getServiceEtablissement()->get( $sc->etablissement);
        }
        return $this->etablissement;
    }

    /**
     *
     * @param EntityEtablissement $etablissement
     * @param boolean $updateParametres
     * @return self
     */
    public function setEtablissement( EntityEtablissement $etablissement, $updateParametres=false )
    {
        $this->etablissement = $etablissement;
        $this->getSessionContainer()->etablissement = $etablissement->getId();
        if ($updateParametres){
            $this->getServiceParametres()->etablissement = (string)$etablissement->getId();
        }
        return $this;
    }

    /**
     *
     * @return AnneeEntity
     */
    public function getAnnee()
    {
        if (! $this->annee){
            $sc = $this->getSessionContainer();
            if (! $sc->offsetExists('annee')){
                $sc->annee = (int)$this->getServiceParametres()->annee;
            }

            $this->annee = $this->getServiceAnnee()->get( $sc->annee);
        }
        return $this->annee;
    }

    /**
     *
     * @return AnneeEntity
     */
    public function getAnneePrecedente()
    {
        if (! $this->anneePrecedente){
            $this->anneePrecedente = $this->getServiceAnnee()->getPrecedente( $this->getAnnee() );
        }
        return $this->anneePrecedente;
    }

    /**
     *
     * @return AnneeEntity
     */
    public function getAnneeSuivante()
    {
        if (! $this->anneeSuivante){
            $this->anneeSuivante = $this->getServiceAnnee()->getSuivante( $this->getAnnee() );
        }
        return $this->anneeSuivante;
    }

    /**
     *
     * @param AnneeEntity $annee
     * @param boolean $updateParametres
     * @return self
     */
    public function setAnnee( AnneeEntity $annee, $updateParametres=false )
    {
        $this->annee = $annee;
        $this->getSessionContainer()->annee = $annee->getId();
        if ($updateParametres){
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
        if (! $sc->offsetExists('dateObservation')){
            $sc->dateObservation = null;
        }
        return $sc->dateObservation;
    }

    /**
     *
     * @param DateTime $dateObservation
     * @return self
     */
    public function setDateObservation( DateTime $dateObservation )
    {
        $sc->dateObservation = $dateObservation;
        return $this;
    }

    /**
     * 
     * @return DateTime
     */
    function getDateFinSaisiePermanents()
    {
        $sc = $this->getSessionContainer();
        if (! $sc->offsetExists('dateFinSaisiePermanents')){
            $sc->dateFinSaisiePermanents = DateTime::createFromFormat(
                                                'd/m/Y',
                                                $this->getServiceParametres()->date_fin_saisie_permanents
                                           );
        }
        return $sc->dateFinSaisiePermanents;
    }

    /**
     *
     * @param DateTime $dateFinSaisiePermanents
     * @return self
     */
    public function setDateFinSaisiePermanents( DateTime $dateFinSaisiePermanents, $updateParametres=false )
    {
        $sc->dateFinSaisiePermanents = $dateFinSaisiePermanents;
        if ($updateParametres){
            $this->getServiceParametres()->date_fin_saisie_permanents = $sc->dateFinSaisiePermanents->format('d/m/Y');
        }
        return $this;
    }

    /**
     *
     * @return StructureEntity
     */
    public function getStructure($initializing=false)
    {
        if (! $this->structure){
            $sc = $this->getSessionContainer();
            if (! $sc->offsetExists('structure')){
                if ($initializing){
                    $sc->structure = null;
                }else{
                    $role = $this->getSelectedIdentityRole();
                    if ($role instanceof StructureAwareInterface && $role->getStructure()){
                        $sc->structure = $role->getStructure()->getId();
                    }else{
                        $sc->structure = null;
                    }
                }
            }
            $this->structure = $this->getServiceStructure()->get( $sc->structure);
        }
        return $this->structure;
    }

    /**
     *
     * @param StructureEntity $structure
     * @param boolean $updateParametres
     * @return self
     */
    public function setStructure( $structure )
    {
        if ($structure instanceof StructureEntity){
            $this->structure = $structure;
            $this->getSessionContainer()->structure = $structure->getId();
        }else{
            $this->structure = null;
            $this->getSessionContainer()->structure = null;
        }
        return $this;
    }

    /**
     * @return Container
     */
    protected function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            $this->sessionContainer = new Container(__CLASS__);
        }
        return $this->sessionContainer;
    }

}