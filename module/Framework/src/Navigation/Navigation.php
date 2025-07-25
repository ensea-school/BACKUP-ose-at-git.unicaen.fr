<?php

namespace Framework\Navigation;

use Framework\Container\Autowire;
use Psr\Container\ContainerInterface;

class Navigation
{
    private Page $home;



    public function __construct(
        private readonly ContainerInterface $container,

        #[Autowire(config: 'navigation')]
        array                               $navigation,
    )
    {
        if (!isset($navigation['default']['home'])) {
            throw new \Exception('La navigation ne comporte pas de page default/home');
        }

        $this->home = new Page($this, $this->container, $navigation['default']['home']);
    }



    public function getHome(): Page
    {
        return $this->home;
    }

}