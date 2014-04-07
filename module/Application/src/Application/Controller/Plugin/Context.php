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
        switch (true) {
            case ($method = 'FromRoute') === substr($name, $length = -9):
                break;
            case ($method = 'FromQuery') === substr($name, $length = -9):
                break;
            case ($method = 'FromPost') === substr($name, $length = -8):
                break;
            case 'FromSession' === substr($name, $length = -11):
                $method = 'fromSession';
                break;
            default:
                throw new LogicException("Méthode '$name' inexistante.");
        }
        
        $target = substr($name, 0, $length);
        if (!$arguments) {
            $arguments = array($target);
        }
        
        $value  = call_user_func_array(array($this, lcfirst($method)), $arguments);
        
//        var_dump($value, $method, $target);
        
        if ($this->mandatory && null === $value) {
            throw new LogicException("Paramètre requis introuvable : '$target'.");
        }
        
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        switch ($target) {
            case 'intervenant':
                if (null !== $value && is_numeric($value)) {
                    if (!($value = $em->find('Application\Entity\Db\Intervenant', $value))) {
                        throw new RuntimeException("Intervenant introuvable avec cet id : $value.");
                    }
                }
                break;
                
            case 'structure':
                if (null !== $value && is_numeric($value)) {
                    if (!($value = $em->find('Application\Entity\Db\Structure', $value))) {
                        throw new RuntimeException("Structure introuvable avec cet id : $value.");
                    }
                }
                break;

            case 'etape':
                if (null !== $value && is_numeric($value)) {
                    if (!($value = $em->find('Application\Entity\Db\Etape', $value))) {
                        throw new RuntimeException("Étape introuvable avec cet id : $value.");
                    }
                }
                break;

            default:
                break;
        }
        
        $this->mandatory = false;
        
        return $value;
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