<?php
namespace Application\Provider\Identity;

use Application\Acl;
use Application\Entity\Db\Affectation;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Provider\Identity\ChainableProvider;
use UnicaenAuth\Provider\Identity\ChainEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use BjyAuthorize\Provider\Identity\ProviderInterface as IdentityProviderInterface;

/**
 * Classe chargée de fournir les rôles que possède l'identité authentifiée.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IdentityProvider implements ServiceLocatorAwareInterface, ChainableProvider, EntityManagerAwareInterface, IdentityProviderInterface
{
    use ServiceLocatorAwareTrait,
        EntityManagerAwareTrait,
        \Application\Traits\SessionContainerTrait
    ;


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
//        $session = $this->getSessionContainer();

//        if (! isset($session->roles)) {
            $this->getEntityManager()->getFilters()->enable('historique')->init(
                [
                    'Application\Entity\Db\Role',
                    'Application\Entity\Db\Affectation',
                ],
                new \DateTime
            );

            $roles = [];

            $serviceAuthUserContext = $this->getServiceLocator()->get('AuthUserContext');
            /* @var $serviceAuthUserContext \UnicaenAuth\Service\UserContext */
            $utilisateur = $serviceAuthUserContext->getDbUser();
            /* @var $utilisateur \Application\Entity\Db\Utilisateur */

            if (! $utilisateur) return []; // pas connecté

            /**
             * Rôles que possède l'utilisateur dans la base de données.
             */
            if ($utilisateur->getPersonnel()) {
                foreach ($utilisateur->getPersonnel()->getAffectation() as $affectation) {
                    /* @var $affectation Affectation */
                    $roleId = $affectation->getRole()->getCode();
                    if ($structure = $affectation->getStructure()){
                        $roleId .= '-'.$structure->getSourceCode();
                    }
                    $roles[] = $roleId;
                }
            }

            /**
             * Rôle correspondant au type d'intervenant auquel appartient l'utilisateur
             */
            if ($intervenant = $utilisateur->getIntervenant()){
                if ($intervenant->getStatut()->getSourceCode() != 'NON_AUTORISES'){
                    $roles[] = $intervenant->getStatut()->getRoleId();
                }
            }return $roles;
//            $session->roles = $roles;
//        }
//        return $session->roles;
    }
}
