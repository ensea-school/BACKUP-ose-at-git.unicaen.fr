<?php

namespace Application;


use UnicaenAuth\Guard\PrivilegeController;

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

        $result          = array_merge($attribs1, $attribs2);
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
        $container = \Application::$container;
        if (!$container) throw new \LogicException('Le container n\'est pas accessible!!!');

        if (!array_key_exists($route, self::$rcaCache)) {
            $config = $container->get('config');
            $r      = ['child_routes' => $config['router']['routes']];

            $elements = explode('/', $route);

            $namespace  = null;
            $controller = null;
            $action     = null;

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

}