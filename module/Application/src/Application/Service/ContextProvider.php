<?php

namespace Application\Service;

use Application\Service\Parametres;
use Application\Acl\IntervenantRole;
use Application\Acl\DbRole;

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
     * Retourne le contexte global.
     * 
     * @return GlobalContext
     */
    public function getGlobalContext()
    {
        if (null === $this->globalContext) {
            $authUserContext = $this->getServiceLocator()->get('authUserContext');
            
            $utilisateur = $authUserContext->getDbUser();
            $intervenant = $utilisateur->getIntervenant();
            $personnel   = $utilisateur->getPersonnel();
            $annee       = $this->getEntityManager()->find('Application\Entity\Db\Annee', $this->getParametres()->annee);
            $etab        = $this->getEntityManager()->find('Application\Entity\Db\Etablissement', $this->getParametres()->etablissement);

            if (null === $intervenant) {
                $ldapUser = $authUserContext->getLdapUser();
                $intervenant = $this->getServiceLocator()->get('ApplicationIntervenant')->importer($ldapUser->getSupannEmpId());
            }
            
            $this->globalContext = new GlobalContext();
            $this->globalContext
                    ->setUtilisateur($utilisateur)
                    ->setIntervenant($intervenant)
                    ->setPersonnel($personnel)
                    ->setAnnee($annee)
                    ->setEtablissement($etab);
        }
        
        return $this->globalContext;
    }
    
    /**
     * 
     * @return DbRole|IntervenantRole
     */
    public function getSelectedIdentityRole()
    {
        return $this->getServiceLocator()->get('AuthUserContext')->getSelectedIdentityRole();
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