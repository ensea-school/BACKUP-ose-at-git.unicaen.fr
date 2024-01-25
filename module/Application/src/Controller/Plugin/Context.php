<?php

namespace Application\Controller\Plugin;

use Intervenant\Service\IntervenantServiceAwareTrait;
use Laminas\Mvc\Controller\Plugin\Params;
use LogicException;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Plugin facilitant l'accès au gestionnaire d'entités Doctrine.
 *
 * @method *FromRoute($name = null, $default = null) Description
 * @method *FromQuery($name = null, $default = null) Description
 * @method *FromPost($name = null, $default = null) Description
 *
 * @see Params
 */
class Context extends Params
{
    use EntityManagerAwareTrait;
    use IntervenantServiceAwareTrait;


    /**
     * Liste des arguments attendus :
     *
     * 0 : $argName    : Nom de l'entrée recherchée. L'entrée peut être de type entree[sous-entree]. Alors, la valeur du sous-tableau correspondant sera retournée.
     * 1 : $argDefault : Valeur de retour par défaut
     * 2 : $argSources : Utile dans certains cas seulement : liste des sources où rechercher (post, query, context, etc.)
     *
     * @param string $name
     * @param array  $arguments
     *
     * @throws LogicException
     */
    public function __call ($name, $arguments)
    {
        /* Récupération des paramètres */
        $argName     = isset($arguments[0]) ? $arguments[0] : null;
        $argSubNames = [];
        $argDefault  = isset($arguments[1]) ? $arguments[1] : null;
        $argSources  = isset($arguments[2]) ? $arguments[2] : null;

        switch (true) {
            case ($method = 'FromQuery') === substr($name, $length = -9):
            break;
            case ($method = 'FromPost') === substr($name, $length = -8):
            break;
            default:
                throw new LogicException("Méthode '$name' inexistante.");
        }

        if ($argName) { // construction du tableau des arguments et de ses sous-arguments pour, plus tard, récupérer une valeur dans un tableau
            $names = explode('[', str_replace(']', '', $argName));
            foreach ($names as $i => $n) {
                if (0 === $i) {
                    $argName = $n;
                } else {
                    $argSubNames[] = $n;
                }
            }
        }
        $target = substr($name, 0, $length);
        if (!$argName) $argName = $target;

        /* Récupération de la valeur */
        $value = call_user_func_array([$this, lcfirst($method)], [$argName, $argDefault]);

        /* Parcours du tableau pour récupérer la valeur attendue */
        if (!empty($argSubNames)) {
            foreach ($argSubNames as $subName) {
                if (!isset($value[$subName])) {
                    throw new RuntimeException("Tableau invalide ou clé \"$subName\" non trouvée.");
                }
                $value = $value[$subName];
            }
        }

        /* Cas particulier pour les intervenants : import implicite */
        if ('intervenant' === $target && $value) {
            $value = $this->getServiceIntervenant()->getByRouteParam($value);
        }
        //Adaptation pour que contexte cherche les entités dans d'autres modules que Application
        $modules = ['Application', 'OffreFormation'];
        foreach ($modules as $module) {
            $className = $module . '\\Entity\\Db\\' . ucfirst($target);
            if (class_exists($className)) {
                break;
            }
        }
        /* Conversion éventuelle en entité */
        //$className = 'Application\\Entity\\Db\\' . ucfirst($target);
        if (class_exists($className)) {
            if (!is_object($value) && !is_array($value)) {
                $id = (int)$value;
                if ($id) {
                    $value = $this->getEntityManager()->find($className, $id);
                    if (!$value) {
                        throw new RuntimeException($className . " introuvable avec cet id : $id.");
                    }
                } else {
                    $value = null;
                }
            }
        }

        return $value;
    }

}