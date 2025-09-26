<?php

namespace Framework\Container;

use Psr\Container\ContainerInterface;


/**
 * Description of AutowireFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AutowireFactory
{
    private ContainerInterface $container;



    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): object
    {
        return $this->createService($container, $requestedName);
    }



    protected function createService(ContainerInterface $container, string $requestedName): object
    {
        if (!class_exists($requestedName)) {
            throw new \Exception('La classe "' . $requestedName . '" n\'a pas été trouvée');
        }

        $this->container = $container;

        $reflection = new \ReflectionClass($requestedName);

        $constructor = $reflection->getConstructor();
        if ($constructor === null) {
            return new $requestedName();
        }

        $parameters   = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $attribute = $parameter->getAttributes()[0] ?? null;
            $traite    = false;
            if ($attribute && $attribute->getName() == Autowire::class) {
                $arguments = $attribute->getArguments();
                foreach ($arguments as $argName => $argValue) {
                    $dependencies[] = $this->traitementArgument($argName, $argValue);
                    $traite         = true;
                    // un seul argument est autorisé ici
                    break;
                }
            }

            if (!$traite) {
                if ($parameter->getType()?->getName() == ContainerInterface::class) {
                    $dependencies[] = $this->container;
                }else {
                    $dependencyName = $parameter->getType()?->getName();
                    if ($dependencyName) {
                        $dependencies[] = $container->get($dependencyName);
                    }
                }
            }
        }

        return new $requestedName(...$dependencies);
    }



    private function traitementArgument(string $argumentName, string $argumentValue): mixed
    {
        switch ($argumentName) {
            case 'config':
                return $this->getConfig($argumentValue);
        }
        throw new \Exception('Argument de changement ' . $argumentName . ' inconnu');
    }



    private function getConfig(string $path): mixed
    {
        $config = $this->container->get('config');

        $path = explode('/', $path);
        foreach ($path as $part) {
            $config = $config[$part] ?? null;
        }

        return $config;
    }
}