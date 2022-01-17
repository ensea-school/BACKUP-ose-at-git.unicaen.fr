<?php

namespace Application\Form;

use Application\Constants;
use Application\Hydrator\GenericHydrator;
use Application\Service\AbstractEntityService;
use Application\Traits\TranslatorTrait;
use Laminas\Form\Form;
use Laminas\Http\Request;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;


abstract class AbstractForm extends Form implements InputFilterProviderInterface
{
    use TranslatorTrait;

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
     * @param string            $name               Name of the route
     * @param array             $params             Parameters for the link
     * @param array|Traversable $options            Options for the route
     * @param bool              $reuseMatchedParams Whether to reuse matched parameters
     *
     * @return string Url                         For the link href attribute
     * @see    \Laminas\Mvc\Router\RouteInterface::assemble()
     *
     */
    protected function getUrl($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        $url = \Application::$container->get('ViewHelperManager')->get('url');

        /* @var $url \Laminas\View\Helper\Url */
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
     * @param array       $hydratorElements
     * @param string|null $hydratorClass
     *
     * @return GenericHydrator
     */
    protected function useGenericHydrator(array $hydratorElements, ?string $hydratorClass = null): GenericHydrator
    {
        $em = \Application::$container->get(Constants::BDD);

        if ($hydratorClass) {
            $hydrator = new $hydratorClass($em, $hydratorElements);
        } else {
            $hydrator = new GenericHydrator($em, $hydratorElements);
        }

        $this->setHydrator($hydrator);

        return $hydrator;
    }



    /**
     * Exécute la sauvegarde d'un formulaire à partir des données Request
     *
     * Dans $saveFnc, l'entité (dont les données ont été mises à jour) est transmise
     *
     * Retourne true si tout s'est bien passé, false sinon.
     * Le message d'erreur pourra être récupéré via le FlashMessenger ou bien via getLastException() pour la traiter ensuite
     *
     * @param                                $entity
     * @param Request                        $request
     * @param AbstractEntityService|function $saveFnc
     * @param string                         $successMessage
     *
     * @return bool
     */
    public function bindRequestSave($entity, Request $request, $saveFnc, $successMessage = 'Enregistrement effectué')
    {
        $this->exception = null;
        $this->bind($entity);
        if ($request->isPost()) {
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $this->setData($data);
            if ($this->isValid()) {
                if ($saveFnc instanceof AbstractEntityService) {
                    try {
                        $saveFnc->save($entity);
                        $this->getControllerPluginFlashMessenger()->addSuccessMessage($successMessage);
                    } catch (\Exception $e) {
                        $this->getControllerPluginFlashMessenger()->addErrorMessage($this->translate($e->getMessage()));
                    }
                } elseif ($saveFnc instanceof \Closure) {
                    try {
                        $saveFnc($entity);
                    } catch (\Exception $e) {
                        $this->exception = $e;
                        $this->getControllerPluginFlashMessenger()->addErrorMessage($e->getMessage());

                        return false;
                    }
                }
            }
        }

        return true;
    }



    /**
     * @param                       $entity
     * @param AbstractEntityService $service
     * @param string                $successMessage
     *
     * @return bool
     */
    public function delete($entity, AbstractEntityService $service, $successMessage = 'Donnée supprimée avec succès.')
    {
        try {
            $service->delete($entity);
            $this->getControllerPluginFlashMessenger()->addSuccessMessage($successMessage);
        } catch (\Exception $e) {
            $this->getControllerPluginFlashMessenger()->addErrorMessage($this->translate($e->getMessage()));

            return false;
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
            $this->controllerPluginFlashMessenger = \Application::$container->get('ControllerPluginManager')->get('flashMessenger');
        }

        return $this->controllerPluginFlashMessenger;
    }
}