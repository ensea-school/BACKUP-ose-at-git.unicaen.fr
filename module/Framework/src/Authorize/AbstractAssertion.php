<?php

namespace Framework\Authorize;

use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use UnicaenAuthentification\Service\Traits\AuthorizeServiceAwareTrait;
use UnicaenAuthentification\Service\Traits\UserContextServiceAwareTrait;
use Laminas\Mvc\MvcEvent;
use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

/**
 * Description of AbstractAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
abstract class AbstractAssertion implements AssertionInterface
{
    use UserContextServiceAwareTrait;
    use AuthorizeServiceAwareTrait;

    private Acl $acl;

    private RoleInterface|bool|null $role = false;

    private MvcEvent $mvcEvent;



    public function __construct(
        protected readonly Authorize $authorize,
    ) {

    }



    /**
     * !!!! Pour éviter l'erreur "Serialization of 'Closure' is not allowed"... !!!!
     */
    public function __sleep(): array
    {
        return [];
    }



    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $this->resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     */
    public final function assert(Acl $acl, ?RoleInterface $role = null, ?ResourceInterface $resource = null, $privilege = null): bool
    {
        $this->setRole($role);
        $this->init();
        switch (true) {
            case $this->detectPrivilege($resource):
//                var_dump('assertPrivilege '.get_class($this).' '.$resource->getResourceId().' '.$privilege);
                return $this->assertPrivilege(ltrim(strstr($resource, '/'), '/'), $privilege);

            case $this->detectController($resource):

                $resource   = (string)$resource;
                $spos       = strpos($resource, '/') + 1;
                $dpos       = strrpos($resource, ':') + 1;
                $controller = substr($resource, $spos, $dpos - $spos - 1);
                $action     = substr($resource, $dpos);

//                var_dump('assertController '.get_class($this).' '.$controller.'.'.$action.' '.$privilege);
                return $this->assertController($controller, $action, $privilege);

            case $this->detectEntity($resource):
//                var_dump('assertEntity '.get_class($this).' '.$resource->getResourceId().' '.$privilege);
                return $this->assertEntity($resource, $privilege);

            default:
//                var_dump('assertOther '.get_class($this).' '.$resource->getResourceId().' '.$privilege);
                return $this->assertOther($resource, $privilege);
        }
    }



    public function isAllowed(string|ResourceInterface $resource, ?string $privilege = null): bool
    {
        return $this->authorize->isAllowed($resource, $privilege);
    }



    public function getRole(): ?RoleInterface
    {
        if (false === $this->role) {
            $sUserContext = $this->serviceUserContext;
            if ($sUserContext->getIdentity()) {
                $this->role = $sUserContext->getSelectedIdentityRole();
            }
        }

        if (false === $this->role) {
            return null;
        }else {
            return $this->role;
        }
    }



    public function setRole(?RoleInterface $role = null): self
    {
        $this->role = $role;

        return $this;
    }



    private function detectPrivilege(?ResourceInterface $resource = null): bool
    {
        if ($resource instanceof ResourceInterface) $resource = $resource->getResourceId();

        return is_string($resource) && 0 === strpos($resource, 'privilege/');
    }



    protected function assertPrivilege(string $privilege, ?string $subPrivilege = null): bool
    {
        return true;
    }



    private function detectController(string|ResourceInterface|null $resource = null): bool
    {
        if ($resource instanceof ResourceInterface) $resource = $resource->getResourceId();

        return 0 === strpos($resource, 'controller/');
    }



    /**
     * Ititialisation des paramètres de l'assertion (si nécessaire)
     */
    public function init(): void
    {

    }



    protected function assertController(string $controller, ?string $action = null, ?string $privilege = null): bool
    {
        return true;
    }



    private function detectEntity(string|ResourceInterface|null $resource = null): bool
    {
        return
            is_object($resource)
            && method_exists($resource, 'getId');
    }



    protected function assertEntity(ResourceInterface $entity, ?string $privilege = null): bool
    {
        return true;
    }



    protected function assertOther(string|ResourceInterface|null $resource = null, ?string $privilege = null): bool
    {
        return true;
    }



    /**
     * Parcours la liste des résultats des assertions transmises (liste de booleans)
     * Si l'une d'entres elles est fausse alors false est retourné. true sinon.
     */
    protected function asserts(array $assertions): bool
    {
        if (!is_array($assertions)) {
            $assertions = [$assertions];
        }

        foreach ($assertions as $assertion) {
            if (!$assertion) return false;
        }

        return true;
    }



    public function setMvcEvent(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;
    }



    protected function getMvcEvent(): MvcEvent
    {
        return $this->mvcEvent;
    }

}
