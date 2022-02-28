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
use UnicaenApp\Util;


abstract class  AbstractForm extends Form implements InputFilterProviderInterface
{
    use TranslatorTrait;

    private ?FlashMessenger $controllerPluginFlashMessenger = null;

    private ?EntityManager  $entityManager                  = null;

    protected array         $spec                           = [];



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
     * @return string                         For the link href attribute
     * @see    \Laminas\Mvc\Router\RouteInterface::assemble()
     *
     */
    protected function getUrl($name = null, $params = [], $options = [], $reuseMatchedParams = false): string
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



    public function specDump()
    {
        echo '<pre>';
        foreach ($this->spec as $name => $spec) {

            echo '<h3>' . $name . '</h3>';
            phpDump($spec);
        }
        echo '</pre>';
    }



    public function build()
    {
        if (!$this->hasAttribute('action')) {
            $this->setAttribute('action', $this->getCurrentUrl());
        }

        if (!$this->hydrator) {
            $hydratorElements = [];
            foreach ($this->spec as $name => $spec) {
                if (isset($spec['hydrator'])) {
                    $hydratorElements[$name] = $spec['hydrator'];
                }
            }
            $hydrator = new GenericHydrator($this->getEntityManager(), $hydratorElements);
            $this->setHydrator($hydrator);
        }

        foreach ($this->spec as $elName => $elSpec) {
            if (isset($elSpec['input'])) {
                unset($elSpec['input']);
            }
            if (isset($elSpec['hydrator'])) {
                unset($elSpec['hydrator']);
            }
            if (!empty($elSpec)) {
                try {
                    $this->add($elSpec);
                } catch (\Throwable $e) {
                    throw new \Exception('L\'élément de formulaire "' . $elName . '" n\'a pas pu être généré depuis sa spécification', 0, $e);
                }
            }
        }

        $this->add(new Csrf('security'));
    }



    public function addSubmit(string $value = 'Enregistrer'): self
    {
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => $value,
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }



    public function setLabels(array $labels): self
    {
        foreach ($labels as $element => $label) {
            if ($this->has($element)) {
                $this->get($element)->setLabel($label);
            }
        }

        return $this;
    }



    /**
     * Permet de peupler facilement une liste d'options
     * Accepte pour $collection :
     * - Un tableau d'entités
     * - Une requête DQL
     * - Un tableau associatif ([value => label, ...])
     *
     * $params sert à fournir d'éventuels paramètres si $collection est une requête DQL
     *
     * @param string       $name
     * @param string|array $collection
     * @param              $params
     *
     * @return $this
     * @throws \Exception
     */
    public function setValueOptions(string $name, string|array $collection, $params = null): self
    {
        if (!$this->has($name)) {
            throw new \Exception('Elément ' . $name . ' non trouvé');
        }
        $element = $this->get($name);
        if (!method_exists($element, 'setValueOptions')) {
            throw new \Exception('L\élément ' . $name . ' ne peut pas se voir associer de listes d\'options');
        }

        if (is_string($collection)) {
            $query = $this->getEntityManager()->createQuery($collection);
            if (is_array($params)) {
                $query->setParameters($params);
            }
            $element->setValueOptions(Util::collectionAsOptions($query->getResult()));
        }
        if (is_array($collection)) {
            $element->setValueOptions(Util::collectionAsOptions($collection));
        }

        return $this;
    }



    /**
     * Permet de peupler facilement une liste d'options à l'aide d'une requête SQL
     * la clé doit correspondre à la colonne VALUE et à défaut ID ou CODE ou SOURCE_CODE
     * la valeur doit correspondre à la colonne LABEL et à défaut LIBELLE ou LIBELLE_COURT
     * Si la clé est introuvable, alors la ligne est ignorée
     *
     * @param string $name
     * @param string $query
     * @param array  $params
     *
     * @return $this
     * @throws \Doctrine\DBAL\Exception
     */
    public function setValueOptionsSql(string $name, string $query, array $params = []): self
    {
        $res     = $this->getEntityManager()->getConnection()->fetchAllAssociative($query, $params);
        $options = [];
        foreach ($res as $r) {
            $value = $r['VALUE'] ?? $r['ID'] ?? $r['CODE'] ?? $r['SOURCE_CODE'] ?? null;
            $label = $r['LABEL'] ?? $r['LIBELLE'] ?? $r['LIBELLE_COURT'] ?? null;
            if ($value && $label) {
                $options[$value] = $label;
            }
        }
        asort($options);

        return $this->setValueOptions($name, $options);
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
            $elements['id'] = ['type' => 'Hidden', 'name' => 'id'];
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



    public function readOnly(bool $readOnly = true)
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

        foreach ($this->spec as $name => $elSpec) {
            if (isset($elSpec['input'])) {
                $filters[$name] = $elSpec['input'];
            }
        }

        return $filters;
    }



    private function elementAddMapping(array &$element, array $mapping)
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



    protected function makeElement(string $property, array &$element)
    {
        $spec = [];
        switch ($element['hydrator']['type'] ?? '') {
            case 'string':
                $spec = [
                    'type'    => 'Text',
                    'name'    => $property,
                    'options' => [
                        'label' => ucfirst($property),
                    ],
                ];
            break;
            case 'bool':
            case 'boolean':
                $spec = [
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
                $spec = [
                    'type'    => 'Text',
                    'name'    => $property,
                    'options' => [
                        'label' => ucfirst($property),
                    ],
                    'input'   => [
                        'filters' => [
                            ['name' => 'Laminas\Filter\StringTrim'],
                            ['name' => FloatFromString::class],
                        ],
                    ],
                ];

            break;
            case 'int':
                $spec = [
                    'type'    => 'Text',
                    'name'    => $property,
                    'options' => [
                        'label' => ucfirst($property),
                    ],
                    'input'   => [
                        'filters' => [
                            ['name' => 'Laminas\Filter\StringTrim'],
                        ],
                    ],
                ];
            break;
            case \DateTime::class:
                $spec = [
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
            $this->getEntityManager()->getClassMetadata($element['hydrator']['type'] ?? '');
            $spec = [
                'type'    => 'Select',
                'name'    => $property,
                'options' => [
                    'label' => ucfirst($property),
                ],
            ];
        } catch (\Exception $e) {
        }

        if (!empty($spec)) {
            $element = ArrayUtils::merge($element, $spec);
        }
    }
}