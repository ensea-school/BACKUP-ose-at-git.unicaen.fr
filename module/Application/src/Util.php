<?php

namespace Application;


use Administration\Interfaces\ParametreEntityInterface;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenPrivilege\Guard\PrivilegeController;

/**
 * Class Util
 *
 * @author  Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @package Application
 */
class Util
{
    /**
     * @var array
     */
    private static $rcaCache = [];



    /**
     * Fusionne les attributs HTML en provenance de deux tableaux, en tenant compte des classes
     *
     * @param array $attribs1
     * @param array $attribs2
     *
     * @return array
     */
    static public function mergeHtmlAttribs(array $attribs1 = [], array $attribs2 = [])
    {
        $classes = array_merge(
            (isset($attribs1['class']) ? (array)$attribs1['class'] : []),
            (isset($attribs2['class']) ? (array)$attribs2['class'] : [])
        );

        $result = array_merge($attribs1, $attribs2);
        $result['class'] = array_unique($classes);

        foreach ($result as $att => $value) {
            if (is_int($value)) {
                $result[$att] = (string)$value;
            }
        }


        return $result;
    }



    static public function sqlAndIn($column, array $entities)
    {
        if (!empty($entities)) {
            $l = [];
            foreach ($entities as $e) {
                if (is_object($e) && method_exists($e, 'getId')) {
                    $l[] = $e->getId();
                } else {
                    $l[] = (int)$e;
                }
            }

            return ' AND ' . $column . ' IN (' . implode(',', $l) . ')';
        }

        return '';
    }



    /**
     * Prend une route et recherche le controleur et l'action correspondante (si ils font partie des options par défaut)
     *
     * Retourne un tableau avec deux éléments : contrôleur et action.
     * Si ces derniers ne sotn pas trouvés alors ils sont <code>null</code>
     *
     * @param string $route
     *
     * @return array
     */
    static public function routeToControllerAction($route)
    {
        $container = \AppAdmin::container();
        if (!$container) throw new \LogicException('Le container n\'est pas accessible!!!');

        if (!array_key_exists($route, self::$rcaCache)) {
            $config = $container->get('config');
            $r = ['child_routes' => $config['router']['routes']];

            $elements = explode('/', $route);

            $namespace = null;
            $controller = null;
            $action = null;

            foreach ($elements as $element) {
                if (isset($r['child_routes'][$element])) {
                    $r = array_change_key_case($r['child_routes'][$element]);
                    if (isset($r['options'])) {
                        $options = array_change_key_case($r['options']);
                        if (isset($options['defaults'])) {
                            $defaults = array_change_key_case($options['defaults']);

                            if (isset($defaults['__namespace__'])) {
                                $namespace = $defaults['__namespace__'];
                            }
                            if (isset($defaults['controller'])) {
                                $controller = $defaults['controller'];
                            }
                            if (isset($defaults['action'])) {
                                $action = $defaults['action'];
                            }
                        }
                    }
                }
            }

            if ($namespace && $controller && 0 !== strpos($controller, $namespace)) {
                $controller = $namespace . '\\' . $controller;
            }
            self::$rcaCache[$route] = [$controller, $action];
        }

        return self::$rcaCache[$route];
    }



    /**
     * @param string $route
     *
     * @return string
     */
    static public function routeToActionResource($route)
    {
        [$controller, $action] = self::routeToControllerAction($route);

        if (!$controller || !$action) {
            throw new \LogicException('Les contrôleur et action de la route "' . $route . '" n\'ont pas pu être calculées.');
        }

        return PrivilegeController::getResourceId($controller, $action);
    }



    public static function spec(string|object|array $spec, array $ignore = [])
    {
        if (is_string($spec) && class_exists($spec)) {
            return self::specFromClass($spec, $ignore);
        }
        if (is_object($spec)) {
            return self::specFromObject($spec, $ignore);
        }
        if (is_array($spec)) {
            return self::specFromArray($spec, $ignore);
        }

        throw new \Exception('La spécification fournie n\'est pas exploitable');
    }



    public static function specDump(array $spec)
    {
        echo '<pre>';
        foreach ($spec as $name => $propSpec) {

            echo '<h3>' . $name . '</h3>';
            phpDump($propSpec);
        }
        echo '</pre>';
    }



    private static function specFromClass(string $class, array $ignore): array
    {
        $elements = [];
        $rc = new \ReflectionClass($class);
        $methods = $rc->getMethods();

        if ($rc->implementsInterface(HistoriqueAwareInterface::class)) {
            $ignore[] = 'histoCreation';
            $ignore[] = 'histoCreateur';
            $ignore[] = 'histoModification';
            $ignore[] = 'histoModificateur';
            $ignore[] = 'histoDestruction';
            $ignore[] = 'histoDestructeur';
        }
        if ($rc->implementsInterface(ParametreEntityInterface::class)) {
            $ignore[] = 'annee';
        }

        foreach ($methods as $method) {
            $parameters = $method->getParameters();
            if (!empty($parameters)){
                // un getter qui a des paramètres en entrée n'est pas exploitable ici
                continue;
            }
            $property = null;
            if (str_starts_with($method->name, 'get')) {
                $property = substr($method->name, 3);
            } elseif (str_starts_with($method->name, 'is')) {
                $property = substr($method->name, 2);
            } elseif (str_starts_with($method->name, 'has')) {
                $property = substr($method->name, 3);
            }

            if ($property) {
                if (!$rc->hasMethod('set' . $property)) {
                    $property = null;
                }
            }

            if ($property) {
                $elKey = lcfirst($property);
                if (!in_array($elKey, $ignore)) {
                    $element = [
                        'hydrator' => [
                            'getter' => $method->name,
                            'setter' => 'set' . $property,
                        ],
                    ];
                    if ($method->hasReturnType()) {
                        $rt = $method->getReturnType();
                        if ($rt instanceof \ReflectionNamedType) {
                            $element['hydrator']['type'] = $rt->getName();
                        } elseif ($rt instanceof \ReflectionUnionType) {
                            $element['hydrator']['type'] = $rt->getTypes()[0]->getName();
                        }
                    }
                    $elements[$elKey] = $element;
                }
            }
        }

        /* Si c'est une entité Doctrine, on récupère les infos du mapping */
        try {
            /** @var EntityManager $em */
            $em = \AppAdmin::container()->get(EntityManager::class);
            $cmd = $em->getClassMetadata($class);
        } catch (\Exception $e) {
            $cmd = null;
        }
        if (!empty($elements) && !empty($cmd)) {
            foreach ($elements as $property => $element) {
                if ($cmd->hasField($property)) {
                    $mapping = $cmd->getFieldMapping($property);
                    self::elementAddMapping($elements[$property], $mapping);
                }
            }
        }

        /* Ajout d'un élément caché pour l'ID */
        if ($cmd && $cmd->hasField('id')) {
            $elements['id'] = ['type' => 'Hidden', 'name' => 'id'];
        }

        return self::specFromArray($elements, []);
    }



    private static function specFromObject(object $object, array $ignore): array
    {
        return self::specFromClass(get_class($object), $ignore);
    }



    private static function specFromArray(array $spec, array $ignore): array
    {
        foreach ($spec as $k => $v) {
            if (in_array($k, $ignore)) {
                unset($spec[$k]);
            }
        }

        return $spec;
    }



    private static function elementAddMapping(array &$element, array $mapping)
    {
        /* Gestion du Required */
        if (isset($mapping['nullable'])) {
            if (!isset($element['input'])) {
                $element['input'] = [];
            }
            $element['input']['required'] = !$mapping['nullable'];
        }

        /* Gestion des length */
        if (($mapping['type'] ?? '') == 'string' && isset($mapping['length']) && $mapping['length']) {
            if (!isset($element['input'])) {
                $element['input'] = [];
            }
            if (!isset($element['input']['validators'])) {
                $element['input']['validators'] = [];
            }
            $element['input']['validators'][] = [
                'name'    => 'StringLength',
                'options' => ['max' => $mapping['length']],
            ];
        }
    }

}