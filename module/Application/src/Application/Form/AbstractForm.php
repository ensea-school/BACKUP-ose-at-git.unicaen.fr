<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Http\Request;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;


abstract class AbstractForm extends Form implements ServiceLocatorAwareInterface, InputFilterProviderInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var FlashMessenger
     */
    private $controllerPluginFlashMessenger;

    /**
     * @var \Exception
     */
    private $exception;



    /**
     * Generates a url given the name of a route.
     *
     * @see    Zend\Mvc\Router\RouteInterface::assemble()
     *
     * @param  string            $name               Name of the route
     * @param  array             $params             Parameters for the link
     * @param  array|Traversable $options            Options for the route
     * @param  bool              $reuseMatchedParams Whether to reuse matched parameters
     *
     * @return string Url                         For the link href attribute
     */
    protected function getUrl($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');

        /* @var $url \Zend\View\Helper\Url */
        return $url->__invoke($name, $params, $options, $reuseMatchedParams);
    }



    /**
     * @return string
     */
    protected function getCurrentUrl()
    {
        return $this->getUrl(null, [], [], true);
    }



    /**
     * Exécute la sauvegarde d'un formulaire à partir des données Request
     *
     * Dans $saveFnc, l'entité (dont les données ont été mises à jour) est transmise
     *
     * Retourne true si tout s'est bien passé, false sinon.
     * Le message d'erreur pourra être récupéré via le FlashMessenger ou bien via getLastException() pour la traiter ensuite
     *
     * @param         $entity
     * @param Request $request
     * @param         $saveFnc
     *
     * @return bool
     */
    public function bindRequestSave($entity, Request $request, $saveFnc)
    {
        $this->exception = null;
        $this->bind($entity);
        if ($request->isPost()) {
            $this->setData($request->getPost());
            if ($this->isValid()) {
                try {
                    $saveFnc($entity);
                } catch (\Exception $e) {
                    $this->exception = $e;
                    $this->getControllerPluginFlashMessenger()->addErrorMessage($e->getMessage());

                    return false;
                }
            }
        }

        return true;
    }



    /**
     * Exécute la sauvegarde d'un formulaire à partir des données Request
     *
     * Dans $saveFnc, les données du formulaire sont transmises
     *
     * Retourne true si tout s'est bien passé, false sinon.
     * Le message d'erreur pourra être récupéré via le FlashMessenger ou bien via getLastException() pour la traiter ensuite
     *
     * @param Request $request
     * @param         $saveFnc
     *
     * @return bool
     */
    public function requestSave(Request $request, $saveFnc)
    {
        $this->exception = null;
        if ($request->isPost()) {
            $this->setData($request->getPost());
            if ($this->isValid()) {
                try {
                    $saveFnc($this->getData());
                } catch (\Exception $e) {
                    $this->exception = $e;
                    $this->getControllerPluginFlashMessenger()->addErrorMessage($e->getMessage());

                    return false;
                }
            }
        }

        return true;
    }



    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }



    /**
     * @return FlashMessenger
     */
    private function getControllerPluginFlashMessenger()
    {
        if (!$this->controllerPluginFlashMessenger) {
            $this->controllerPluginFlashMessenger = $this->getServiceLocator()->getServiceLocator()->get('ControllerPluginManager')->get('flashMessenger');
        }

        return $this->controllerPluginFlashMessenger;
    }
}