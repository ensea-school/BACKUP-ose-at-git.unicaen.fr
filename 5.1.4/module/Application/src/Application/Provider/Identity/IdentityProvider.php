<?php

namespace Application\Provider\Identity;

use Application\Entity\Db\Affectation;
use Application\Entity\Db\Role;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\PersonnelAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Provider\Identity\ChainableProvider;
use UnicaenAuth\Provider\Identity\ChainEvent;
use BjyAuthorize\Provider\Identity\ProviderInterface as IdentityProviderInterface;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Classe chargée de fournir les rôles que possède l'identité authentifiée.
 *
 */
class IdentityProvider implements ChainableProvider, IdentityProviderInterface
{
    use EntityManagerAwareTrait;
    use SessionContainerTrait;
    use IntervenantAwareTrait;
    use PersonnelAwareTrait;
    use ContextAwareTrait;



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
        /**
         * @todo attention : plusieurs intervenants pourront remonter si on peut leur donner plusieurs statuts par an!!
         */
        $intervenant = $this->getServiceContext()->getIntervenant();
        $personnel   = $this->getServiceContext()->getPersonnel();

        $utilisateurCode = 'i'.($intervenant ? $intervenant->getId() : '').'p'.($personnel ? $personnel->getId() : '');

        $session = $this->getSessionContainer();
        if ($mustRefresh = !isset($session->utilisateurCode) || $session->utilisateurCode != $utilisateurCode) {
            $session->utilisateurCode = $utilisateurCode;
        }

        if (!isset($session->roles) || $mustRefresh) {
            $filter = $this->getEntityManager()->getFilters()->enable('historique');
            $filter->init([
                Role::class,
                Affectation::class,
            ]);

            $roles = [];

            /**
             * Rôles que possède l'utilisateur dans la base de données.
             */
            if ($personnel) {
                foreach ($personnel->getAffectation() as $affectation) {
                    /* @var $affectation Affectation */
                    $roleId = $affectation->getRole()->getCode();
                    if ($structure = $affectation->getStructure()) {
                        $roleId .= '-' . $structure->getSourceCode();
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

        return $session->roles;
    }
}
