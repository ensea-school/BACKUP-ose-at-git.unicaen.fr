<?php

namespace Application\Hydrator;

use Application\Filter\DateTimeFromString;
use Application\Util;
use Doctrine\ORM\EntityManager;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\DateTimeLocal;
use Laminas\Stdlib\ArrayUtils;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class GenericHydrator implements HydratorInterface
{
    use EntityManagerAwareTrait;

    const EXTRACT_TYPE_STRING   = 0;
    const EXTRACT_TYPE_JSON = 1;
    const EXTRACT_TYPE_ORIGINAL = 2;

    protected array $spec = [];

    protected $noGenericParse = [];

    protected string $dateTimeFormat = 'Y-m-d';

    protected int $extractType = 0;



    public function __construct(EntityManager $entityManager, string|object|array $spec = [], array $ignore = [])
    {
        $this->setEntityManager($entityManager);
        if (!empty($spec)) {
            $this->spec($spec, $ignore);
        }
    }



    public function spec(string|object|array $spec, array $ignore = []): void
    {
        $spec = Util::spec($spec, $ignore + $this->noGenericParse);
        $this->spec = ArrayUtils::merge($this->spec, $spec);
    }



    public function specDump(): void
    {
        Util::specDump($this->spec);
    }



    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }



    public function setDateTimeFormat(string $dateTimeFormat): GenericHydrator
    {
        $this->dateTimeFormat = $dateTimeFormat;
        return $this;
    }



    public function getExtractType(): int
    {
        return $this->extractType;
    }



    public function setExtractType(int $extractType): GenericHydrator
    {
        $this->extractType = $extractType;
        return $this;
    }



    public function extract($object): array
    {
        $data = [];
        if (method_exists($object, 'getId')) {
            $data['id'] = (string)$object->getId();
        }
        foreach ($this->spec as $name => $params) {
            if (!in_array($name, $this->noGenericParse)) {
                if(isset($params['hydrator'])) {
                    $params = $params['hydrator'];
                }
                $getter = isset($params['getter']) ? $params['getter'] : null;
                $type = ($getter instanceof \Closure) ? 'string' : (isset($params['type']) ? $params['type'] : null);

                /* Récupération de la valeur */
                $value = null;
                if (is_string($getter) && method_exists($object, $getter)) {
                    $value = $object->$getter();
                } elseif ($getter instanceof \Closure) {
                    $value = $getter($object, $name);
                } elseif (method_exists($object, $gget = 'get' . ucFirst($name))) {
                    $value = $object->$gget();
                } elseif (method_exists($object, $gis = 'is' . ucFirst($name))) {
                    $value = $object->$gis();
                }

                switch ($this->extractType) {
                    case self::EXTRACT_TYPE_STRING:
                        if ('float' == $type && is_float($value)) {
                            $value = floatToString($value);
                        } elseif ('int' == $type && is_int($value)) {
                            $value = intToString($value);
                        } elseif (('bool' == $type || 'boolean' == $type) && is_bool($value)) {
                            $value = booleanToString($value, '1', '0');
                        } elseif (\DateTime::class == $type && $value instanceof \DateTime) {
                            $dateFormat = $params['format'] ?? $this->dateTimeFormat;
                            $value = $value->format($dateFormat);
                        } elseif ($type && class_exists($type) && $value instanceof $type && method_exists($value, 'getId')) {
                            $value = (string)$value->getId();
                        }
                        break;

                    case self::EXTRACT_TYPE_ORIGINAL:
                        // rien à faire : on laisse tout passer
                        break;

                    case self::EXTRACT_TYPE_JSON:
                        // à la place des sous-entités on retourne les ID
                        if ($type && class_exists($type) && $value instanceof $type && method_exists($value, 'getId')) {
                            $value = $value->getId();
                        }
                        break;
                }


                $data[$name] = $value;
            }
        }

        return $data;
    }



    public function hydrate(array $data, $object)
    {
        foreach ($this->spec as $name => $params) {
            if (!in_array($name, $this->noGenericParse)) {
                if(isset($params['hydrator'])) {
                    $params = $params['hydrator'];
                }
                $setter = isset($params['setter']) ? $params['setter'] : 'set' . ucfirst($name);
                $type = ($setter instanceof \Closure) ? 'string' : (isset($params['type']) ? $params['type'] : null);
                $readOnly = isset($params['readonly']) ? (bool)$params['readonly'] : false;

                if ($readOnly || !isset($data[$name])) continue;

                /* Récupération de la valeur */
                $value = $data[$name];
                if ($value === '') $value = null;

                /* Transformation de la string en type original */
                if ('float' == $type) {
                    $value = stringToFloat($value);
                }
                if ('int' == $type) {
                    $value = stringToInt($value);
                }
                if (('bool' == $type || 'boolean' == $type)) {
                    $value = stringToBoolean($value);
                }
                if ('Date' == $type || 'DateTime' == $type || Date::class == $type || DateTimeLocal::class == $type || 'DateTimeLocal' == $type) {
                    $value = DateTimeFromString::run($value);
                }
                if (class_exists($type ?? '') && $this->isEntity($type) && $value) {
                    $value = $this->getEntityManager()->find($type, $value);
                }

                /* Injection de la valeur dans l'objet */
                if ($setter instanceof \Closure) {
                    $setter($object, $value, $name);
                } elseif (is_string($setter) && method_exists($object, $setter)) {
                    $object->$setter($value);
                }
            }
        }
    }



    public function setReadOnly(string $element, bool $readOnly = true)
    {
        $this->spec[$element]['readOnly'] = $readOnly;
    }



    private function isEntity(string $class): bool
    {
        try {
            $this->getEntityManager()->getClassMetadata($class);

            return true;
        } catch (\Exception $e) {
        }

        return false;
    }

}