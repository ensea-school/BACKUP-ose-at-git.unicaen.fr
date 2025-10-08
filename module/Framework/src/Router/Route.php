<?php

namespace Framework\Router;

class Route
{
    private string $name;

    private ?Route $parent = null;

    /** @var array|Route[] */
    private array $children = [];

    private string $route;

    private int $priority = 0;

    private ?string $namespace = null;

    private ?string $controller;

    private ?string $action;

    private array $constraints = [];

    private array $params = [];

    /** @var array|string[] */
    private array $privileges = [];

    private ?string $assertion = null;



    public function __construct(string $name, array $data, ?Route $parent)
    {
        $this->name   = $name;
        $this->parent = $parent;
        if ($parent) {
            $parent->addChild($this);
        }

        $options  = $data['options'] ?? [];
        $defaults = $options['defaults'] ?? [];

        if ($parent) {
            $this->route = $parent->getRoute() . ($options['route'] ?? null);
        } else {
            $this->route = $options['route'] ?? null;
        }

        if (isset($options['constraints'])) {
            $this->constraints = $options['constraints'];
        }

        $this->priority = $data['priority'] ?? 0;

        $this->namespace  = $defaults['__NAMESPACE__'] ?? $parent?->getNamespace();
        $this->controller = $defaults['controller'] ?? $parent?->getController();
        $this->action     = $defaults['action'] ?? $parent?->getAction();

        if (!empty($data['privileges'])) {
            $this->privileges = (array)$data['privileges'];
        }

        if (!empty($data['assertion'])) {
            $this->privileges = $data['assertion'];
        }

        foreach ($defaults as $k => $v) {
            if ($k != '__NAMESPACE__' && $k != 'controller' && $k != 'action') {
                $this->params[$k] = $v;
            }
        }
    }



    public function isParentOf(Route $route)
    {
        do {
            if ($route === $this) {
                return true;
            }
        } while ($route = $route->getParent());

        return false;
    }


    public function isLiteral(): bool
    {
        return !str_contains($this->route, ':');
    }



    public function getName(): string
    {
        return $this->name;
    }



    public function getParent(): ?Route
    {
        return $this->parent;
    }



    public function getChildren(): array
    {
        return $this->children;
    }



    protected function addChild(Route $child): void
    {
        $this->children[] = $child;
    }



    public function getRoute(): string
    {
        return $this->route;
    }



    public function getConstraints(): array
    {
        return $this->constraints;
    }



    public function getParams(): array
    {
        return $this->params;
    }



    public function getNamespace(): ?string
    {
        return $this->namespace;
    }



    public function getController(): ?string
    {
        return $this->controller;
    }



    public function getAction(): ?string
    {
        return $this->action;
    }



    public function getPrivileges(): array
    {
        return $this->privileges;
    }



    public function getAssertion(): ?string
    {
        return $this->assertion;
    }

}