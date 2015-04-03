<?php

namespace Application\Service;

use \Application\Acl\Role;
use Application\Entity\Db\Etablissement as EntityEtablissement;
use Application\Entity\Db\Annee         as AnneeEntity;
use \DateTime;
use Zend\Session\Container;

/**
 * Service fournissant les différents contextes de fonctionnement de l'application.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Context extends AbstractService
{
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

            $this->selectedIdentityRole = $authUserContext->getSelectedIdentityRole();

            if ($this->selectedIdentityRole instanceof IntervenantAwareInterface) {
                $this->selectedIdentityRole->setIntervenant($this->getGlobalContext()->getIntervenant());
            }
            if ($this->selectedIdentityRole instanceof PersonnelAwareInterface){
                $this->selectedIdentityRole->setPersonnel($this->getGlobalContext()->getPersonnel());
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
                $sc->etablissement = (int)$this->getParametres()->etablissement;
            }
            $sEtablissement = $this->getServiceLocator()->get('applicationEtablissement');
            /* @var $sEtablissement Etablissement */

            $this->etablissement = $sEtablissement->get( $sc->etablissement);
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
            $this->getParametres()->etablissement = (string)$etablissement->getId();
        }
        return $this;
    }

    /**
     *
     * @return EntityAnnee
     */
    public function getAnnee()
    {
        if (! $this->annee){
            $sc = $this->getSessionContainer();
            if (! $sc->offsetExists('annee')){
                $sc->annee = (int)$this->getParametres()->annee;
            }
            $sAnnee = $this->getServiceLocator()->get('applicationAnnee');
            /* @var $sAnnee Annee */

            $this->annee = $sAnnee->get( $sc->annee);
        }
        return $this->annee;
    }

    /**
     *
     * @return EntityAnnee
     */
    public function getAnneePrecedente()
    {
        if (! $this->anneePrecedente){
            $sAnnee = $this->getServiceLocator()->get('applicationAnnee');
            /* @var $sAnnee Annee */

            $this->anneePrecedente = $sAnnee->getPrecedente( $this->getAnnee() );
        }
        return $this->anneePrecedente;
    }

    /**
     *
     * @return EntityAnnee
     */
    public function getAnneeSuivante()
    {
        if (! $this->anneeSuivante){
            $sAnnee = $this->getServiceLocator()->get('applicationAnnee');
            /* @var $sAnnee Annee */

            $this->anneeSuivante = $sAnnee->getSuivante( $this->getAnnee() );
        }
        return $this->anneeSuivante;
    }

    /**
     *
     * @param EntityAnnee $annee
     * @param boolean $updateParametres
     * @return self
     */
    public function setAnnee( EntityAnnee $annee, $updateParametres=false )
    {
        $this->annee = $annee;
        $this->getSessionContainer()->annee = $annee->getId();
        if ($updateParametres){
            $this->getParametres()->annee = (string)$annee->getId();
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
    function getDateFinSaisiePermanents()
    {
        $sc = $this->getSessionContainer();
        if (! $sc->offsetExists('dateFinSaisiePermanents')){
            $sc->dateFinSaisiePermanents = DateTime::createFromFormat(
                                                'd/m/Y',
                                                $this->getParametres()->date_fin_saisie_permanents
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
            $this->getParametres()->date_fin_saisie_permanents = $sc->dateFinSaisiePermanents->format('d/m/Y');
        }
        return $this;
    }

    /**
     * @return Container
     */
    function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            $this->sessionContainer = new Container(__CLASS__);
        }
        return $this->sessionContainer;
    }

    /**
     *
     * @return Parametres
     */
    protected function getParametres()
    {
        if (null === $this->parametres) {
            $this->parametres = $this->getServiceLocator()->get('ApplicationParametres');
        }
        return $this->parametres;
    }












    

    /**
     * Retourne le contexte global.
     *
     * @return GlobalContext
     */
    public function getGlobalContext()
    {
        if (null === $this->globalContext) {
            $authUserContext = $this->getServiceLocator()->get('authUserContext');
            /* @var $authUserContext \UnicaenAuth\Service\UserContext */

            $utilisateur = null;
            $intervenant = null;
            $personnel   = null;



            if ($authUserContext->getIdentity()) {
                $utilisateur = $authUserContext->getDbUser();
                if ($utilisateur){
                    $intervenant = $utilisateur->getIntervenant();
                    $personnel   = $utilisateur->getPersonnel();
                }
                if (null === $intervenant) {
                    $ldapUser = $authUserContext->getLdapUser();

                    $sIntervenant = $this->getServiceLocator()->get('applicationIntervenant');
                    /* @var $sIntervenant Intervenant */

                    $intervenant = $sIntervenant->importer((int) $ldapUser->getSupannEmpId());
                }
            }

            /**
             * @todo Faire du GlobalContext un service !
             */
            $this->globalContext = new GlobalContext;
            $this->globalContext
                    ->setServiceLocator($this->getServiceLocator())
                    ->setUtilisateur($utilisateur)
                    ->setIntervenant($intervenant)
                    ->setPersonnel($personnel)
            ;
        }

        return $this->globalContext;
    }
}