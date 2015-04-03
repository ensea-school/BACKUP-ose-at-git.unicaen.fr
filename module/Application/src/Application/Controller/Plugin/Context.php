<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\Params;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Session\Container;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;

/**
 * Plugin facilitant l'accès au gestionnaire d'entités Doctrine.
 *
 * @method mixed *FromRoute($name = null, $default = null) Description
 * @method mixed *FromQuery($name = null, $default = null) Description
 * @method mixed *FromPost($name = null, $default = null) Description
 * @method mixed *FromSession($name = null, $default = null) Description
 * @method mixed *FromSources($name = null, $default = null, array $sources = null) Description
 * @method mixed *FromQueryPost($name = null, $default = null) Description
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see Params
 */
class Context extends Params implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var bool
     */
    protected $mandatory = false;

    /**
     * @var Container
     */
    protected $sessionContainer;

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
            case ($method = 'FromSession') === substr($name, $length = -11):
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

        $em = $this->getServiceLocator()->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        /* Cas particulier pour les intervenants : import implicite */
        if ('intervenant' === $target && (int)$value){
            $sourceCode = (string)(int)$value;
            if (!($intervenant = $this->getServiceIntervenant()->getBySourceCode($sourceCode))) {
                $this->getProcessusImport()->intervenant($sourceCode); // Import
                if (!($intervenant = $this->getServiceIntervenant()->getBySourceCode($sourceCode))) {
                    throw new RuntimeException("L'intervenant suivant est introuvable après import : sourceCode = $sourceCode.");
                }
            }
            $value = $intervenant;
        }

//        var_dump($value, $method, $target);

        if ($this->mandatory && null === $value) {
            throw new LogicException("Paramètre requis introuvable : '$target'.");
        }

        /* Conversion éventuelle en entité */
        $className = 'Application\\Entity\\Db\\'.ucfirst($target);
        if (class_exists($className)){
            if (!is_object($value) && ! is_array($value)){
                $id = (int)$value;
                if ($id){
                    $value = $em->find($className, $id);
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
     *
     * @param  string $name Parameter name to retrieve.
     * @param  mixed $default Default value to use when the requested parameter is not set.
     * @param array $sources
     * @return mixed
     */
    public function fromSources($name, $default=null, array $sources=[])
    {
        $defaultSources = ['context', 'route', 'query', 'post', 'session' ];
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

    /**
     * Return a single session parameter.
     *
     * @param  string $name Parameter name to retrieve.
     * @param  mixed $default Default value to use when the requested parameter is not set.
     * @return mixed
     */
    public function fromSession($name, $default = null)
    {
        if (!isset($this->getSessionContainer()->$name)) {
            return $default;
        }

        return $this->getSessionContainer()->$name;
    }

    /**
     * @return Container
     */
    protected function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            $this->sessionContainer = new Container(get_class($this->getController()));
        }
        return $this->sessionContainer;
    }

    /**
     * @return \Application\Service\Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationIntervenant');
    }

    /**
     * @return \Import\Processus\Import
     */
    protected function getProcessusImport()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('importProcessusImport');
    }

    /**
     * @return \Application\Service\ContextProvider
     */
    protected function getServiceContextProvider()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('ApplicationContextProvider');
    }
}