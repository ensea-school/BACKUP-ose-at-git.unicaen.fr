<?php

namespace Application\Controller\Plugin;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;

/**
 * Plugin facilitant l'accès au gestionnaire d'entités Doctrine.
 *
 * @method mixed *FromRoute($name = null, $default = null) Description
 * @method mixed *FromQuery($name = null, $default = null) Description
 * @method mixed *FromPost($name = null, $default = null) Description
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see \Zend\Mvc\Controller\Plugin\Params
 */
class Context extends \Zend\Mvc\Controller\Plugin\Params implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $sl;
    
    /**
     * @var bool
     */
    protected $mandatory = false;
    
    /**
     * @var \Application\Service\Context
     */
    protected $context;
    
    /**
     * @var \Zend\Session\Container
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
     * 
     * @param type $name
     * @param type $arguments
     * @throws LogicException
     */
    public function __call($name, $arguments)
    {
        $argName = isset($arguments[0]) ? $arguments[0] : null;
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
            case ($method = 'FromContext') === substr($name, $length = -11):
                break;
            case ($method = 'FromSources') === substr($name, $length = -11):
                break;
            case 'FromQueryPost' === substr($name, $length = -13):
                $method = 'FromSources';
                $argSources = array('query','post');
                break;
            default:
                throw new LogicException("Méthode '$name' inexistante.");
        }
        
        $target = substr($name, 0, $length);
        if (! $argName) $argName = $target;

        if ('fromSources' === $method){
            $value = $this->fromSources($argName, $argDefault, $argSources);
        }else{
            $value = call_user_func_array(array($this, lcfirst($method)), array($argName,$argDefault));
        }
        

//        var_dump($value, $method, $target);

        if ($this->mandatory && null === $value) {
            throw new LogicException("Paramètre requis introuvable : '$target'.");
        }

        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $className = 'Application\\Entity\\Db\\'.ucfirst($target);
        if (class_exists($className)){
            if (!is_object($value)){
                if ((int)$value){
                    if (!($value = $em->find($className, (int)$value))) {
                        throw new RuntimeException($className." introuvable avec cet id : $value.");
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
    public function fromSources($name, $default=null, array $sources=array())
    {
        $defaultSources = array('context', 'route', 'query', 'post', 'session' );
        if (empty($sources)) $sources = $defaultSources;

        foreach( $sources as $source ){
            if (! in_array($source, $defaultSources)){
                throw new LogicException("Source de données introuvable : '$source'.");
            }
            $result = call_user_func_array(array($this, 'from'.lcfirst($source)), array($name, null));
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
     * Return a single global context parameter.
     *
     * @param  string $name Parameter name to retrieve.
     * @param  mixed $default Default value to use when the requested parameter is not set.
     * @return mixed
     */
    public function fromContext($name, $default = null)
    {
        try {
            $value = $this->getGlobalContext()->get($name);
        }
        catch (LogicException $exc) {
            $value = $default;
        }

        return $value;
    }
    
    /**
     * @return \Application\Service\Context
     */
    public function getGlobalContext()
    {
        if (null === $this->context) {
            $this->context = $this->sl->get('ApplicationContext');
        }
        return $this->context;
    }
    
    /**
     * @return \Zend\Session\Container
     */
    protected function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            $this->sessionContainer = new \Zend\Session\Container(get_class($this->getController()));
        }
        return $this->sessionContainer;
    }
    
    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator->getServiceLocator();
        
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->sl;
    }
}