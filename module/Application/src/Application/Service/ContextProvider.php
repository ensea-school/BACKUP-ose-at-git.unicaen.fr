<?php

namespace Application\Service;

use Application\Service\Parametres;
use Application\Acl\IntervenantRole;
use Application\Interfaces\IntervenantAwareInterface;
use Application\Interfaces\PersonnelAwareInterface;
use Application\Acl\Role;

/**
 * Service fournissant les différents contextes de fonctionnement de l'application.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContextProvider extends AbstractService
{
    /**
     * @var Parametres
     */
    protected $parametres;
    
    /**
     * @var GlobalContext
     */
    protected $globalContext;
    
    /**
     * @var LocalContext
     */
    protected $localContext;
    
    /**
     * @var \Zend\Permissions\Acl\Role\RoleInterface
     */
    protected $selectedIdentityRole;
    
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

            $sAnnee = $this->getServiceLocator()->get('applicationAnnee');
            /* @var $sAnnee Annee */

            $sEtablissement = $this->getServiceLocator()->get('applicationEtablissement');
            /* @var $sEtablissement Etablissement */

            $annee       = $sAnnee->get( $this->getParametres()->annee     );
            $anneePrec   = $sAnnee->get( $this->getParametres()->annee - 1 );
            $anneeSuiv   = $sAnnee->get( $this->getParametres()->annee + 1 );
            $etab        = $sEtablissement->get( $this->getParametres()->etablissement );
            $utilisateur = null;
            $intervenant = null;
            $personnel   = null;

            if (null != $this->getParametres()->date_fin_saisie_permanents){
                list( $dfspJour, $dfspMois, $dfspAnnee ) = explode( '/', $this->getParametres()->date_fin_saisie_permanents );
                $dateFinSaisiePermanents = new \DateTime( $dfspAnnee.'-'.$dfspMois.'-'.$dfspJour );
            }else{
                $dateFinSaisiePermanents = null;
            }
            
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
                    ->setAnnee($annee)
                    ->setAnneePrecedente($anneePrec)
                    ->setAnneeSuivante($anneeSuiv)
                    ->setEtablissement($etab)
                    ->setDateFinSaisiePermanents($dateFinSaisiePermanents)
            ;
        }

        return $this->globalContext;
    }

    /**
     * Retourne le contexte local (filtres, etc.)
     * 
     * @return LocalContext
     */
    public function getLocalContext()
    {
        if (null === $this->localContext) {
            $this->localContext = new LocalContext;
            $this->localContext->setServiceLocator($this->getServiceLocator());
        }

        if (! $this->localContext->getAnnee()){
            $sAnnee = $this->getServiceLocator()->get('applicationAnnee');
            /* @var $sAnnee Annee */

            // peuplement obligatoire de l'année en cours!!
            $this->localContext->setAnnee( $sAnnee->get( $this->getParametres()->annee ) );
        }

        return $this->localContext;
    }

    /**
     * 
     * @return Role|IntervenantRole
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
     * @return Parametres
     */
    protected function getParametres()
    {
        if (null === $this->parametres) {
            $this->parametres = $this->getServiceLocator()->get('ApplicationParametres');
        }
        
        return $this->parametres;
    }
}