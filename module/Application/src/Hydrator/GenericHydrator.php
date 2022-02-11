<?php

namespace Application\Hydrator;

use Application\Constants;
use Application\Interfaces\ParametreEntityInterface;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class GenericHydrator implements HydratorInterface
{
    use EntityManagerAwareTrait;

    protected $elements       = [];

    protected $noGenericParse = [];



    public function __construct(EntityManager $entityManager, array $elements = [])
    {
        $this->setEntityManager($entityManager);
        $this->elements = $elements;
    }



    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }



    /**
     * @param array $elements
     *
     * @return GenericHydrator
     */
    public function setElements(array $elements): GenericHydrator
    {
        $this->elements = $elements;

        return $this;
    }



    public function extract($object): array
    {
        $data = [];
        foreach ($this->elements as $name => $params) {
            if (!in_array($name, $this->noGenericParse)) {
                $type   = isset($params['type']) ? $params['type'] : null;
                $getter = isset($params['getter']) ? $params['getter'] : null;

                /* Récupération de la valeur */
                $value = null;
                if (is_string($getter) && method_exists($object, $getter)) {
                    $value = $object->$getter();
                } elseif (method_exists($object, $gget = 'get' . ucFirst($name))) {
                    $value = $object->$gget();
                } elseif (method_exists($object, $gis = 'is' . ucFirst($name))) {
                    $value = $object->$gis();
                } elseif (is_callable($getter)) {
                    $value = $getter($name, $object);
                }

                /* Transformation en string */
                if ('float' == $type && is_float($value)) {
                    $value = floatToString($value);
                }
                if ('int' == $type && is_int($value)) {
                    $value = intToString($value);
                }
                if (('bool' == $type || 'boolean' == $type) && is_bool($value)) {
                    $value = booleanToString($value, '1', '0');
                }
                if (\DateTime::class == $type && $value instanceof \DateTime) {
                    $value = $value->format(Constants::DATE_FORMAT);
                }
                if (class_exists($type) && $value instanceof $type && method_exists($value, 'getId')) {
                    $value = (string)$value->getId();
                }

                $data[$name] = $value;
            }
        }

        return $data;
    }



    public function hydrate(array $data, $object)
    {
        foreach ($this->elements as $name => $params) {
            if (!in_array($name, $this->noGenericParse)) {
                $type     = isset($params['type']) ? $params['type'] : null;
                $setter   = isset($params['setter']) ? $params['setter'] : 'set' . ucfirst($name);
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
                if (\DateTime::class == $type) {
                    $value = \DateTime::createFromFormat(Constants::DATE_FORMAT, $value);
                    if ($value) $value->setTime(0, 0, 0);
                    if (!$value) $value = null;
                }
                if (class_exists($type) && $this->isEntity($type) && $value) {
                    $value = $this->getEntityManager()->find($type, $value);
                }

                /* Injection de la valeur dans l'objet */
                if (is_string($setter) && method_exists($object, $setter)) {
                    $object->$setter($value);
                } elseif (is_callable($setter)) {
                    $setter($object, $name, $value);
                }
            }
        }
    }



    public function setReadOnly(string $element, bool $readOnly = true)
    {
        $this->elements[$element]['readOnly'] = $readOnly;
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