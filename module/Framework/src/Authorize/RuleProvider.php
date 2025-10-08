<?php

namespace Framework\Authorize;

use Framework\Container\Autowire;

class RuleProvider
{
    /** @var array|Rule[] */
    private array $rules = [];



    public function __construct(
        #[Autowire(config: 'bjyauthorize/rule_providers')]
        private ?array $oldRulesConfig,

        #[Autowire(config: 'rules')]
        private ?array $rulesConfig,
    )
    {
        $this->loadConfig();
    }



    protected function loadConfig(): void
    {
        if (!empty($this->oldRulesConfig)) {
            foreach ($this->oldRulesConfig as $provider => $allowDeny) {
                foreach ($allowDeny['allow'] as $rule) {
                    $this->addConfigRule($rule);
                }
            }
            $this->oldRulesConfig = null;
        }
        if (!empty($this->rulesConfig)) {
            foreach ($this->rulesConfig as $rule) {
                $this->addConfigRule($rule);
            }
            $this->rulesConfig = null;
        }
    }



    protected function addConfigRule(array $params): void
    {
        $ressources = (array)($params['resources'] ?? []);
        $roles      = (array)($params['roles'] ?? []);
        $privileges = (array)($params['privileges'] ?? []);
        $assertion  = $params['assertion'] ?? null;

        foreach ($ressources as $ressource) {
            $rule             = new Rule();
            $rule->privileges = $privileges;
            $rule->roles      = $roles;
            $rule->assertion  = $assertion;
            $this->add($ressource, $rule);
        }
    }



    public function has(string $resource): bool
    {
        return array_key_exists($resource, $this->rules);
    }



    public function add(string $resource, Rule $rule): void
    {
        //if ($this->has($resource)) {
        //    throw new \Exception('La ressource '.$resource.' est déjà configurée');
        //}
        if (!$this->has($resource)) {
            $this->rules[$resource] = [];
        }

        $this->rules[$resource][] = $rule;
    }



    /**
     * @param string $resource
     * @return array|Rule[]
     */
    public function get(string $resource): array
    {
        return $this->rules[$resource] ?? [];
    }



    public function getAll(): array
    {
        return $this->rules;
    }



    public function remove(string $resource): bool
    {
        if (!$this->has($resource)) {
            return false;
        }
        unset($this->rules[$resource]);
        return true;
    }
}