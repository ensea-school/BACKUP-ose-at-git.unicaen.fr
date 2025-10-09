<?php

namespace Application\Traits;

use Application\Filter\FloatFromString;
use Application\Hydrator\GenericHydrator;
use Doctrine\ORM\EntityManager;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Select;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Stdlib\ArrayUtils;
use UnicaenApp\Util;

trait FormFieldsetTrait
{
    use TranslatorTrait;

    private ?EntityManager $entityManager = null;

    private ?FlashMessenger $controllerPluginFlashMessenger = null;

    private array $spec = [];



    protected function getEntityManager(): EntityManager
    {
        if (!$this->entityManager) {
            $this->entityManager =\Unicaen\Framework\Application\Application::getInstance()->container()->get(EntityManager::class);
        }

        return $this->entityManager;
    }



    private function getControllerPluginFlashMessenger(): FlashMessenger
    {
        if (!$this->controllerPluginFlashMessenger) {
            $this->controllerPluginFlashMessenger =\Unicaen\Framework\Application\Application::getInstance()->container()->get('ControllerPluginManager')->get('flashMessenger');
        }

        return $this->controllerPluginFlashMessenger;
    }



    protected function getUrl($name = null, $params = [], $options = [], $reuseMatchedParams = false): string
    {
        $url = \Unicaen\Framework\Application\Application::getInstance()->container()->get('ViewHelperManager')->get('url');

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



    public function readOnly(bool $readOnly = true, array $elements = [])
    {
        if (empty($elements)) {
            $elements = $this->getElements();
        }

        /** @var $element \Laminas\Form\Element */
        foreach ($elements as $elementName) {

            if ($element = $this->get($elementName)) {
                switch (get_class($element)) {
                    case Select::class:
                    case Checkbox::class:
                        $element->setAttribute('disabled', $readOnly);
                        break;
                    default:
                        $element->setAttribute('readonly', $readOnly);
                }
            }
        }
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
     * @param string $name
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
     * @param array $params
     *
     * @return $this
     * @throws \Doctrine\DBAL\Exception
     */
    public function setValueOptionsSql(string $name, string $query, array $params = []): self
    {
        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($query, $params);
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



    public function spec(string|object|array $spec, array $ignore = [])
    {
        $spec = \Application\Util::spec($spec, $ignore);

        /* Construction des éléments de formulaires */
        if (!empty($spec)) {
            foreach ($spec as $property => $element) {
                $this->makeElement($property, $spec[$property]);
            }
        }

        $this->spec = ArrayUtils::merge($this->spec, $spec);
    }



    public function specDump()
    {
        \Application\Util::specDump($this->spec);
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
                    'type'       => 'Date',
                    'name'       => $property,
                    'options'    => [
                        'label'         => ucfirst($property),
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