<?php

namespace Application\Factory;

use Psr\Container\ContainerInterface;


/**
 * Description of AutowireFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AutowireFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): object
    {
        return $this->createService($container, $requestedName);
    }



    protected function createService(ContainerInterface $container, string $requestedName): object
    {
        if (!class_exists($requestedName)) {
            throw new \Exception('La classe "' . $requestedName . '" n\'a pas été trouvée');
        }

        $reflection = new \ReflectionClass($requestedName);

        $constructor = $reflection->getConstructor();
        if ($constructor === null) {
            return new $requestedName();
        }

        $parameters   = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencyName = $parameter->getType()->getName();
            $dependencies[] = $container->get($dependencyName);
        }

        return new $requestedName(...$dependencies);
    }

}