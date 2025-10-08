<?php

namespace Framework\Authorize;

use Framework\Navigation\Page;
use Laminas\Mvc\MvcEvent;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

/**
 * Description of AbstractAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
abstract class AbstractAssertion implements AssertionInterface
{
    private MvcEvent $mvcEvent;



    public function __construct(
        protected readonly Authorize $authorize,
    )
    {
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
    public final function assert(array $context): bool
    {
        if (array_key_exists('resource', $context)) {
            $resource = $context['resource'];
            if (is_object($resource) && method_exists($resource, 'getId')) {
                return $this->assertEntity($resource, $context['privilege'] ?? null);
            }
        } elseif (array_key_exists('controller', $context)) {
            $controller = $context['controller'];
            return $this->assertController($controller, $context['action'] ?? null);
        } elseif (array_key_exists('page', $context)) {
            $page = $context['page'];
            return $this->assertPage($page);
        }

        return $this->assertOther($context);
    }



    public function isAllowed(null|string|ResourceInterface $resource, ?string $privilege = null): bool
    {
        return $this->authorize->isAllowed($resource, $privilege);
    }



    protected function assertController(string $controller, ?string $action): bool
    {
        return true;
    }



    protected function assertEntity(ResourceInterface $entity, ?string $privilege): bool
    {
        return true;
    }



    protected function assertPage(Page $page): bool
    {
        return true;
    }



    protected function assertOther(array $context): bool
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
