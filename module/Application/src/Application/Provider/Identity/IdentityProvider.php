<?php
namespace Application\Provider\Identity;

use Application\Acl;
use Application\Entity\Db\Affectation;
use Application\Entity\Db\Role;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\PersonnelAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Provider\Identity\ChainableProvider;
use UnicaenAuth\Provider\Identity\ChainEvent;
use UnicaenAuth\Service\Traits\RoleServiceAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use BjyAuthorize\Provider\Identity\ProviderInterface as IdentityProviderInterface;
use Application\Traits\SessionContainerTrait;

/**
 * Classe chargée de fournir les rôles que possède l'identité authentifiée.
 *
 */
class IdentityProvider implements ServiceLocatorAwareInterface, ChainableProvider, EntityManagerAwareInterface, IdentityProviderInterface
{
    use ServiceLocatorAwareTrait;
    use EntityManagerAwareTrait;
    use SessionContainerTrait;
    use IntervenantAwareTrait;
    use PersonnelAwareTrait;



    /**
     * {@inheritDoc}
     */
    public function injectIdentityRoles(ChainEvent $event)
    {
        $event->addRoles($this->getIdentityRoles());
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentityRoles()
    {
        $serviceAuthUserContext = $this->getServiceLocator()->get('AuthUserContext');
        /* @var $serviceAuthUserContext \UnicaenAuth\Service\UserContext */

        if ($ldapUser = $serviceAuthUserContext->getLdapUser()) {
            $utilisateurCode = (integer)$ldapUser->getSupannEmpId();
        }else{
            $utilisateurCode = null;
        }


        $session = $this->getSessionContainer();
        if ($mustRefresh = ! isset($session->utilisateurCode) || $session->utilisateurCode != $utilisateurCode){
            $session->utilisateurCode = $utilisateurCode;
        }

        if ($serviceAuthUserContext->getNextSelectedIdentityRole()){
            $mustRefresh = true; // on rafraichit si un rôle prochain est forcé!!
        }

        if (! isset($session->roles) || $mustRefresh) {
            $filter = $this->getEntityManager()->getFilters()->enable('historique');
            $filter->setServiceLocator($this->getServiceLocator());
            $filter->init([
                Role::class,
                Affectation::class,
            ]);

            $roles = [];

            if (! $utilisateurCode) return []; // pas connecté

            /**
             * @todo attention : plusieurs intervenants pourront remonter si on peut leur donner plusieurs statuts par an!!
             */
            $intervenant = $this->getServiceIntervenant()->getBySourceCode($utilisateurCode);
            $personnel = $this->getServicePersonnel()->getBySourceCode($utilisateurCode);

            /**
             * Rôles que possède l'utilisateur dans la base de données.
             */
            if ($personnel) {
                foreach ($personnel->getAffectation() as $affectation) {
                    /* @var $affectation Affectation */
                    $roleId = $affectation->getRole()->getCode();
                    if ($structure = $affectation->getStructure()){
                        $roleId .= '-'.$structure->getSourceCode();
                    }
                    $roles[] = $roleId;
                }
            }

            /**
             * Rôle lié au statut de l'intervenant
             */
            if ($intervenant) {
                $roles[] = $intervenant->getStatut()->getRoleId();
            }

            $session->roles = $roles;
        }

        $rs = [];
        foreach( $session->roles as $roleId ){

        }

        return $session->roles;
    }
}
