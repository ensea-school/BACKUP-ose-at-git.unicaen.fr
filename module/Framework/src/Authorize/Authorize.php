<?php

namespace Framework\Authorize;

use Framework\Container\Autowire;
use Framework\Router\Router;
use Framework\User\UserManager;
use Framework\User\UserManagerInterface;
use Framework\User\UserProfile;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class Authorize extends AbstractListenerAggregate
{
    const ERROR = 'error-unauthorized-controller';

    private const SYNTAX_PRIVILEGE  = 'privilege/';
    private const SYNTAX_ROLE       = 'role/';
    private const SYNTAX_CONTROLLER = 'controller/';
    private const SYNTAX_ROUTE      = 'route/';



    public function __construct(
        #[Autowire(service: UserManager::class)]
        private readonly UserManagerInterface   $userManager,

        private readonly Router        $router,
        private readonly GuardProvider $guardProvider,
        private readonly RuleProvider  $ruleProvider,
    )
    {

    }



    public function isAllowed(mixed $resource, ?string $privilege = null): bool
    {
        if (is_string($resource)) {
            if (str_starts_with($resource, self::SYNTAX_PRIVILEGE)) {
                $privilege = substr($resource, strlen(self::SYNTAX_PRIVILEGE));
                return $this->isAllowedPrivilege($privilege);
            } elseif (str_starts_with($resource, self::SYNTAX_CONTROLLER)) {
                $controller = substr($resource, strlen(self::SYNTAX_CONTROLLER));
                [$controller, $action] = explode(':', $controller);
                return $this->isAllowedController($controller, $action);
            } elseif (str_starts_with($resource, self::SYNTAX_ROUTE)) {
                $route = substr($resource, strlen(self::SYNTAX_ROUTE));
                return $this->isAllowedRoute($route);
            }
        }

        return $this->isAllowedResource($resource, $privilege);
    }



    public function isAllowedPrivilege(string $privilege): bool
    {
        return $this->userManager->hasPrivilege($privilege);
    }



    public function isAllowedRole(string $role): bool
    {
        if (UserProfile::PRIVILEGE_GUEST === $role) {
            return true;
        }
        if (UserProfile::PRIVILEGE_USER === $role) {
            return $this->userManager->isConnected();
        }

        $currentRoleId = $this->userManager->getRole()?->getRoleId();

        return $role === $currentRoleId;
    }



    public function isAllowedRoute(string $route): bool
    {
        if (!$this->router->hasRoute($route)) {
            return false;
        }
        $route      = $this->router->getRoute($route);
        $controller = $route->getController();
        if (!$controller) {
            return false;
        }
        $action = $route->getAction();

        $actionRule = $this->guardProvider->get($controller, $action);
        if ($actionRule && !$this->isAllowedRule($actionRule, ['route' => $route])) {
            return false;
        }

        $controllerRule = $this->guardProvider->get($controller);
        if ($controllerRule && !$this->isAllowedRule($controllerRule, ['route' => $route])) {
            return false;
        }

        // règle passée => OK, sinon false par défaut
        return (bool)($actionRule || $controllerRule);
    }



    public function isAllowedController(string $controller, ?string $action = null): bool
    {
        $actionRule = $this->guardProvider->get($controller, $action);
        if ($actionRule && !$this->isAllowedRule($actionRule, ['controller' => $controller, 'action' => $action])) {
            return false;
        }

        $controllerRule = $this->guardProvider->get($controller);
        if ($controllerRule && !$this->isAllowedRule($controllerRule, ['controller' => $controller, 'action' => $action])) {
            return false;
        }

        // règle passée => OK, sinon false par défaut
        return (bool)($actionRule || $controllerRule);
    }



    public function isAllowedResource(null|string|ResourceInterface $resource, ?string $privilege = null): bool
    {
        if (empty($resource)) {
            // empty resource => false
            return false;
        }

        $context = ['resource' => $resource];
        if ($privilege) {
            $context['privilege'] = $privilege;
        }

        if ($resource instanceof ResourceInterface) {
            $rules = $this->ruleProvider->get($resource->getResourceId());
        } else {
            $rules = $this->ruleProvider->get($resource);
        }

        foreach ($rules as $rule) {
            if ($this->isAllowedRule($rule, $context)) {
                return true;
            }
        }

        return false;
    }



    public static function controllerResource(string $controller, ?string $action = null): string
    {
        if (isset($action)) {
            return self::SYNTAX_CONTROLLER . sprintf('%s:%s', $controller, strtolower($action));
        }

        return self::SYNTAX_CONTROLLER . $controller;
    }



    public static function roleResource(string|object $role): string
    {
        if (is_object($role) && method_exists($role, 'getRoleId')) {
            $role = $role->getRoleId();
        }

        return self::SYNTAX_ROLE . $role;
    }



    public static function privilegeResource(string|object $privilege): string
    {
        if (is_object($privilege) && method_exists($privilege, 'getFullCode')) {
            $privilege = $privilege->getFullCode();

        }

        return self::SYNTAX_PRIVILEGE . $privilege;
    }



    public function routeResource(string $route): string
    {
        return self::SYNTAX_ROUTE . $route;
    }



    public function isAllowedRule(Rule $rule, array $context = []): bool
    {
        if (!empty($rule->roles)) {
            $roleFound = false;
            foreach ($rule->roles as $role) {
                if ($this->isAllowedRole($role)) {
                    $roleFound = true;
                    break;
                }
            }
            if (!$roleFound) {
                // on a une liste de rôles, mais aucun ne correspond au rôle courant
                return false;
            }
        }

        if (!empty($rule->privileges)) {
            $privilegeFound = false;
            foreach ($rule->privileges as $privilege) {
                if ($this->isAllowedPrivilege($privilege)) {
                    $privilegeFound = true;
                    break;
                }
            }
            if (!$privilegeFound) {
                // L'utilisateur connecté n'a aucun des privilèges autorisés par à règle
                return false;
            }
        }

        if ($rule->assertion){

        }

        return true;
    }



    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onDispatch'], -1000);
    }



    public function onDispatch(MvcEvent $event)
    {
        $routeMatch = $event->getParam('route-match');
        $params     = $routeMatch->getParams();

        $controller = $params['controller'];
        $action     = $params['action'];

        $authorized = $this->isAllowedController($controller, $action);
        if ($authorized) {
            return;
        }

        $event->setError(static::ERROR);
        $event->setParam('identity', $this->userManager->getUser()?->getDisplayName());
        $event->setParam('controller', $controller);
        $event->setParam('action', $action);

        $errorMessage = sprintf("You are not authorized to access %s:%s", $controller, $action);
        $event->setParam('exception', new UnAuthorizedException($errorMessage));

        /* @var $app \Laminas\Mvc\ApplicationInterface */
        $app = $event->getTarget();

        $event->setName(MvcEvent::EVENT_DISPATCH_ERROR);
        $app->getEventManager()->triggerEvent($event);
    }
}