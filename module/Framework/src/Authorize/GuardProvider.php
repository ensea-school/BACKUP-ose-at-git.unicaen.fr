<?php

namespace Framework\Authorize;

use Framework\Container\Autowire;

class GuardProvider
{
    private const ALL_CONTROLLER_GUARD = '__all_controller__';

    /** @var array|Rule[] */
    private array $guards = [];



    public function __construct(
        #[Autowire(config: 'bjyauthorize/guards')]
        private ?array $oldGuardsConfig,

        #[Autowire(config: 'guards')]
        private ?array $guardsConfig,
    )
    {
        $this->loadConfig();
    }



    protected function loadConfig(): void
    {
        if (!empty($this->oldGuardsConfig)) {
            foreach ($this->oldGuardsConfig as $rules) {
                foreach ($rules as $rule) {
                    $this->addConfigGuards($rule);
                }
            }
            $this->oldGuardsConfig = null;
        }

        if (!empty($this->guardsConfig)) {
            foreach ($this->guardsConfig as $rule) {
                $this->addConfigGuards($rule);
            }
            $this->guardsConfig = null;
        }
    }



    protected function addConfigGuards(array $params): void
    {
        $controller = $params['controller'] ?? null;

        if (!$controller) {
            throw new \Exception('Une guard n \'a pas de controller spécifié');
        }

        $actions    = (array)($params['action'] ?? self::ALL_CONTROLLER_GUARD);
        $roles      = (array)($params['roles'] ?? []);
        $privileges = (array)($params['privileges'] ?? []);
        $assertion  = $params['assertion'] ?? null;

        foreach ($actions as $action) {
            $rule             = new Rule();
            $rule->privileges = $privileges;
            $rule->roles      = $roles;
            $rule->assertion  = $assertion;
            $this->add($controller, $action, $rule);
        }
    }



    public function has(string $controller, ?string $action = null): bool
    {
        if (!$action) $action = self::ALL_CONTROLLER_GUARD;
        return isset($this->guards[$controller][$action]);
    }



    public function add(string $controller, ?string $action, Rule $rule): void
    {
        if (!$action) $action = self::ALL_CONTROLLER_GUARD;

        if (!array_key_exists($controller, $this->guards)) {
            $this->guards[$controller] = [];
        }

        //if ($this->has($controller, $action)) {
        //    throw new \Exception('La guard '.$controller.':'.$action.' est déjà configurée');
        //}

        $this->guards[$controller][$action] = $rule;
    }



    public function get(string $controller, ?string $action = null): ?Rule
    {
        if (!$action) $action = self::ALL_CONTROLLER_GUARD;
        return $this->guards[$controller][$action] ?? null;
    }



    public function getAll(): array
    {
        return $this->guards;
    }



    public function remove(string $controller, ?string $action = null): bool
    {
        if (!$action) $action = self::ALL_CONTROLLER_GUARD;
        if (!$this->has($controller, $action)) {
            return false;
        }
        unset($this->guards[$controller][$action]);
        if (empty($this->guards[$controller])) {
            unset($this->guards[$controller]);
        }

        return true;
    }
}