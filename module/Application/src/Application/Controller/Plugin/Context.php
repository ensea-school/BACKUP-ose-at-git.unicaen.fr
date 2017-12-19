<?php

namespace Application\Controller\Plugin;

use Application\Service\Traits\IntervenantServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\Plugin\Params;
use LogicException;
use RuntimeException;

/**
 * Plugin facilitant l'accès au gestionnaire d'entités Doctrine.
 *
 * @method *FromRoute($name = null, $default = null) Description
 * @method *FromQuery($name = null, $default = null) Description
 * @method *FromPost($name = null, $default = null) Description
 * @method *FromSources($name = null, $default = null, array $sources = null) Description
 * @method *FromQueryPost($name = null, $default = null) Description
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see Params
 */
class Context extends Params
{
    use EntityManagerAwareTrait;
    use IntervenantServiceAwareTrait;

    /**
     * @var bool
     */
    protected $mandatory = false;

    /**
     *
     * @param bool $mandatory
     * @return Context
     */
    public function mandatory($mandatory = true)
    {
        $this->mandatory = $mandatory;
        return $this;
    }

    /**
     * Liste des arguments attendus :
     *
     * 0 : $argName    : Nom de l'entrée recherchée. L'entrée peut être de type entree[sous-entree]. Alors, la valeur du sous-tableau correspondant sera retournée.
     * 1 : $argDefault : Valeur de retour par défaut
     * 2 : $argSources : Utile dans certains cas seulement : liste des sources où rechercher (post, query, context, etc.)
     *
     * @param string $name
     * @param array $arguments
     * @throws LogicException
     */
    public function __call($name, $arguments)
    {
        /* Récupération des paramètres */
        $argName = isset($arguments[0]) ? $arguments[0] : null;
        $argSubNames = [];
        $argDefault = isset($arguments[1]) ? $arguments[1] : null;
        $argSources = isset($arguments[2]) ? $arguments[2] : null;

        switch (true) {
            case ($method = 'FromRoute') === substr($name, $length = -9):
                break;
            case ($method = 'FromQuery') === substr($name, $length = -9):
                break;
            case ($method = 'FromPost') === substr($name, $length = -8):
                break;
            case ($method = 'FromSources') === substr($name, $length = -11):
                break;
            case 'FromQueryPost' === substr($name, $length = -13):
                $method = 'FromSources';
                $argSources = ['query','post'];
                break;
            default:
                throw new LogicException("Méthode '$name' inexistante.");
        }

        if ($argName){ // construction du tableau des arguments et de ses sous-arguments pour, plus tard, récupérer une valeur dans un tableau
            $names = explode('[', str_replace(']', '', $argName));
            foreach( $names as $i => $n ){
                if (0 === $i){
                    $argName = $n;
                }else{
                    $argSubNames[] = $n;
                }
            }
        }
        $target = substr($name, 0, $length);
        if (! $argName) $argName = $target;

        /* Récupération de la valeur */
        if ('FromSources' === $method){
            $value = $this->fromSources($argName, $argDefault, $argSources);
        }else{
            $value = call_user_func_array([$this, lcfirst($method)], [$argName,$argDefault]);
        }

        /* Parcours du tableau pour récupérer la valeur attendue */
        if (! empty($argSubNames)){
            foreach( $argSubNames as $subName ){
                if (! isset($value[$subName])){
                    throw new RuntimeException("Tableau invalide ou clé \"$subName\" non trouvée.");
                }
                $value = $value[$subName];
            }
        }

        /* Cas particulier pour les intervenants : import implicite */
        if ('intervenant' === $target && $value) {
            $value = $this->intervenantFromSourceCode($value);
        }

        if ($this->mandatory && null === $value) {
            throw new LogicException("Paramètre requis introuvable : '$target'.");
        }

        /* Conversion éventuelle en entité */
        $className = 'Application\\Entity\\Db\\'.ucfirst($target);
        if (class_exists($className)){
            if (!is_object($value) && ! is_array($value)){
                $id = (int)$value;
                if ($id){
                    $value = $this->getEntityManager()->find($className, $id);
                    if (!$value && $this->mandatory) {
                        throw new RuntimeException($className." introuvable avec cet id : $id.");
                    }
                }else{
                    $value = null;
                }
            }
        }

        $this->mandatory = false;

        return $value;
    }


    /**
     * Fetch avec import préalable si besoin d'un ou plusieurs intervenants à partir du source code.
     *
     * @param string|string[] $sourceCodes
     * @return \Application\Entity\Db\Intervenant|array
     */
    private function intervenantFromSourceCode($sourceCodes)
    {
        if (is_scalar($sourceCodes)) {
            $sourceCode = (string)(int) $sourceCodes;
            if (!($intervenant = $this->getServiceIntervenant()->getBySourceCode($sourceCode))) {
                throw new RuntimeException("L'intervenant suivant est introuvable après import : sourceCode = $sourceCode.");
            }

            return $intervenant;
        }

        $intervenants = [];
        foreach ($sourceCodes as $sourceCode) {
            $intervenants[$sourceCode] = $this->intervenantFromSourceCode($sourceCode) ;
        }

        return $intervenants;
    }

    /**
     *
     * @param  string $name Parameter name to retrieve.
     * @param  mixed $default Default value to use when the requested parameter is not set.
     * @param array $sources
     * @return mixed
     */
    public function fromSources($name, $default=null, array $sources=[])
    {
        $defaultSources = ['context', 'route', 'query', 'post' ];
        if (empty($sources)) $sources = $defaultSources;

        foreach( $sources as $source ){
            if (! in_array($source, $defaultSources)){
                throw new LogicException("Source de données introuvable : '$source'.");
            }
            $result = call_user_func_array([$this, 'from'.lcfirst($source)], [$name, null]);
            if ($result !== null) return $result;
        }
        return $default;
    }

}