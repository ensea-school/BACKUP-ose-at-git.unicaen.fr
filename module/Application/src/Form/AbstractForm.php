<?php

namespace Application\Form;

use Application\Constants;
use Application\Filter\FloatFromString;
use Application\Hydrator\GenericHydrator;
use Application\Interfaces\ParametreEntityInterface;
use Application\Service\AbstractEntityService;
use Application\Traits\TranslatorTrait;
use Doctrine\ORM\EntityManager;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\Http\Request;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;
use Laminas\Stdlib\ArrayUtils;
use UnicaenApp\Entity\HistoriqueAwareInterface;


abstract class AbstractForm extends Form implements InputFilterProviderInterface
{
    use TranslatorTrait;

    private ?FlashMessenger $controllerPluginFlashMessenger = null;

    private ?EntityManager  $entityManager                  = null;

    private array           $spec                           = [];



    protected function getEntityManager(): EntityManager
    {
        if (!$this->entityManager) {
            $this->entityManager = \Application::$container->get(Constants::BDD);
        }

        return $this->entityManager;
    }



    private function getControllerPluginFlashMessenger(): FlashMessenger
    {
        if (!$this->controllerPluginFlashMessenger) {
            $this->controllerPluginFlashMessenger = \Application::$container->get('ControllerPluginManager')->get('flashMessenger');
        }

        return $this->controllerPluginFlashMessenger;
    }



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
        if ($hydratorClass) {
            $hydrator = new $hydratorClass($this->getEntityManager(), $hydratorElements);
        } else {
            $hydrator = new GenericHydrator($this->getEntityManager(), $hydratorElements);
        }

        $this->setHydrator($hydrator);

        return $hydrator;
    }



    public function spec(string|object|array $spec, array $ignore = [])
    {
        if (is_string($spec) && class_exists($spec)) {
            return $this->specFromClass($spec, $ignore);
        }
        if (is_object($spec)) {
            return $this->specFromObject($spec, $ignore);
        }
        if (is_array($spec)) {
            return $this->specFromArray($spec, $ignore);
        }

        throw new \Exception('La spécification fournie n\'est pas exploitable');
    }



    public function specElement(string $elementName, array $elSpec)
    {
        if (!isset($this->spec[$elementName])) {
            $this->spec[$elementName] = [];
        }
        if (!isset($this->spec[$elementName]['element'])) {
            $this->spec[$elementName]['element'] = [];
        }
        $this->spec[$elementName]['element'] = ArrayUtils::merge($this->spec[$elementName]['element'], $elSpec);
    }



    public function specBuild()
    {
        $this->useGenericHydrator($this->spec);

        foreach ($this->spec as $elName => $elSpec) {
            if (isset($elSpec['element'])) {
                $this->add($elSpec['element']);
            }
        }

        $this->add(new Csrf('security'));
    }



    private function specFromClass(string $class, array $ignore): self
    {
        $elements = [];
        $rc       = new \ReflectionClass($class);
        $methods  = $rc->getMethods();

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
            $property = null;
            if (str_starts_with($method->name, 'get')) {
                $property = substr($method->name, 3);
            } elseif (str_starts_with($method->name, 'is')) {
                $property = substr($method->name, 2);
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
                        'getter' => $method->name,
                        'setter' => 'set' . $property,
                    ];
                    if ($method->hasReturnType()) {
                        $rt = $method->getReturnType();
                        if ($rt instanceof \ReflectionNamedType) {
                            $element['type'] = $rt->getName();
                        } elseif ($rt instanceof \ReflectionUnionType) {
                            $element['type'] = $rt->getTypes()[0]->getName();
                        }
                    }
                    $elements[$elKey] = $element;
                }
            }
        }

        /* Si c'est une entité Doctrine, on récupère les infos du mapping */
        try {
            $cmd = $this->getEntityManager()->getClassMetadata($class);
        } catch (\Exception $e) {
            $cmd = null;
        }
        if (!empty($elements) && !empty($cmd)) {
            foreach ($elements as $property => $element) {
                if ($cmd->hasField($property)) {
                    $mapping = $cmd->getFieldMapping($property);
                    $this->elementAddMapping($elements[$property], $mapping);
                }
            }
        }

        /* Ajout d'un élément caché pour l'ID */
        if ($cmd && $cmd->hasField('id')) {
            $this->spec(['id' => ['element' => ['type' => 'Hidden', 'name' => 'id']]]);
        }

        /* Construction des éléments de formulaires */
        if (!empty($elements)) {
            foreach ($elements as $property => $element) {
                $this->makeElement($property, $elements[$property]);
            }
        }

        $this->specFromArray($elements, []);

        return $this;
    }



    private function specFromObject(object $object, array $ignore): self
    {
        return $this->specFromClass(get_class($object), $ignore);
    }



    private function specFromArray(array $spec, array $ignore): self
    {
        foreach ($spec as $k => $v) {
            if (in_array($k, $ignore)) {
                unset($spec[$k]);
            }
        }
        $this->spec = ArrayUtils::merge($this->spec, $spec);

        return $this;
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
    public function bindRequestSave($entity, Request $request, $saveFnc, string $successMessage = 'Enregistrement effectué'): bool
    {
        $this->bind($entity);
        if ($request->isPost()) {
            $data = ArrayUtils::merge(
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
                        $this->getControllerPluginFlashMessenger()->addErrorMessage($e->getMessage());

                        return false;
                    }
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
    public function requestSave(Request $request, $saveFnc): bool
    {
        if ($request->isPost()) {
            $this->setData($request->getPost());
            if ($this->isValid()) {
                try {
                    $saveFnc($this->getData());
                } catch (\Exception $e) {
                    $this->getControllerPluginFlashMessenger()->addErrorMessage($e->getMessage());

                    return false;
                }
            }
        }

        return true;
    }



    public function readOnly(bool $readOnly)
    {
        /** @var $element \Laminas\Form\Element */
        foreach ($this->getElements() as $element) {
            switch (get_class($element)) {
                case Number::class:
                case Text::class:
                    $element->setAttribute('readonly', $readOnly);
                break;
                case Select::class:
                case Checkbox::class:
                    $element->setAttribute('disabled', $readOnly);
                break;
            }
        }
    }



    public function getInputFilterSpecification()
    {
        $filters = [];

        foreach ($this->spec as $name => $spec) {
            if (isset($spec['filter'])) {
                $filters[$name] = $spec['filter'];
            }
        }

        return $filters;
    }



    private function elementAddMapping(array &$element, array $mapping)
    {
        /* Gestion du Required */
        if (isset($mapping['nullable'])) {
            if (!isset($element['controls'])) {
                $element['controls'] = [];
            }
            $element['controls']['required'] = !$mapping['nullable'];
        }

        /* Gestion des length */
        if (($mapping['type'] ?? '') == 'string' && isset($mapping['length']) && $mapping['length']) {
            $validator = [
                'name'    => 'StringLength',
                'options' => ['max' => $mapping['length']],
            ];
            $this->elementAddValidator($element, $validator);
        }
    }



    protected function elementAddValidator(array &$element, array $validatorConfig)
    {
        if (!isset($element['controls'])) {
            $element['controls'] = [];
        }
        if (!isset($element['controls']['validators'])) {
            $element['controls']['validators'] = [];
        }
        $element['controls']['validators'][] = $validatorConfig;
    }



    protected function makeElement(string $property, array &$element)
    {
        $elSpec     = [];
        $elControls = [];
        switch ($element['type'] ?? '') {
            case 'string':
                $elSpec = [
                    'type'    => 'Text',
                    'name'    => $property,
                    'options' => [
                        'label' => ucfirst($property),
                    ],
                ];
            break;
            case 'bool':
            case 'boolean':
                $elSpec = [
                    'type'    => 'Checkbox',
                    'name'    => $property,
                    'options' => [
                        'label'           => ucfirst($property),
                        'checked_value'   => '1',
                        'unchecked_value' => '0',
                    ],
                ];
            break;
            case 'float':
                $elSpec     = [
                    'type'    => 'Text',
                    'name'    => $property,
                    'options' => [
                        'label' => ucfirst($property),
                    ],
                ];
                $elControls = [
                    'filters' => [
                        ['name' => 'Laminas\Filter\StringTrim'],
                        ['name' => FloatFromString::class],
                    ],
                ];
            break;
            case 'int':
                $elSpec     = [
                    'type'    => 'Text',
                    'name'    => $property,
                    'options' => [
                        'label' => ucfirst($property),
                    ],
                ];
                $elControls = [
                    'filters' => [
                        ['name' => 'Laminas\Filter\StringTrim'],
                    ],
                ];
            break;
            case \DateTime::class:
                $elSpec = [
                    'type'       => 'DateTime',
                    'name'       => $property,
                    'options'    => [
                        'label'         => ucfirst($property),
                        'format'        => Constants::DATE_FORMAT,
                        'label_options' => [
                            'disable_html_escape' => true,
                        ],
                    ],
                    'attributes' => [
                        'placeholder' => "jj/mm/aaaa",
                    ],
                ];
            break;
        }

        /* Si c'est une entité Doctrine, alors on présuppose qu'on a affaire à un Select */
        try {
            $this->getEntityManager()->getClassMetadata($element['type'] ?? '');
            $elSpec = [
                'type'    => 'Select',
                'name'    => $property,
                'options' => [
                    'label' => ucfirst($property),
                ],
            ];
        } catch (\Exception $e) {
        }

        if (!empty($elSpec)) {
            if (!isset($element['element'])) {
                $element['element'] = [];
            }
            $element['element'] = ArrayUtils::merge($element['element'], $elSpec);
        }
        if (!empty($elControls)) {
            if (!isset($element['controls'])) {
                $element['controls'] = [];
            }
            array_push($element['controls'], $elControls);
        }
    }
}