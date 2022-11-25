<?php

namespace Enseignement\Hydrator;

use Application\Constants;
use Enseignement\Entity\VolumeHoraireListe;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class ListeFilterHydrator implements HydratorInterface, EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;

    /**
     * @var array
     */
    private $filters;



    /**
     * Extract values from an object
     *
     * @param VolumeHoraireListe $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [];
        foreach (VolumeHoraireListe::FILTRES_LIST as $filter => $rule) {
            if ($this->hasFilter($filter)) {
                $method        = 'get' . $rule['accessor'];
                $data[$filter] = $object->$method();
            }
        }

        return $data;
    }



    /**
     * -2 = false
     * -1 = true
     *  0 = null
     *  > 0 = ID d'entité ou timestamp
     *
     * @param string $filter
     * @param        $value
     *
     * @return int
     */
    public function dataToInt(string $filter, $value): int
    {
        if (false === $value) return -2;
        if (true === $value) return -1;
        if (null === $value) return 0;

        $toIntFunc = VolumeHoraireListe::FILTRES_LIST[$filter]['to-int-func'];
        if ($toIntFunc) return $value->$toIntFunc();

        return -2;
    }



    /**
     * @param string $filter
     * @param        $value
     * @param array  $options
     *
     * @return bool|\DateTime|int|null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function allToData(string $filter, $value, $options = [])
    {
        if (
            is_bool($value) || null === $value || is_object($value)
        ) {
            return $value;
        }

        if ('all' == $value) return false;

        $valInt = (int)$value;

        if (-2 == $valInt) return false;
        if (-1 == $valInt) return true;
        if (0 == $valInt) return null;

        $class = VolumeHoraireListe::FILTRES_LIST[$filter]['class'];
        switch ($class) {
            case null:
                return $valInt;
            case \DateTime::class:
                if ((string)(int)$value === $value) {
                    $dateTime = new \DateTime;
                    $dateTime->setTimestamp($valInt);
                } else {
                    $format   = isset($options['format']) ? $options['format'] : Constants::DATETIME_FORMAT;
                    $dateTime = \DateTime::createFromFormat($format, $value);
                }

                return $dateTime;
            default:
                if (!$em = $this->getEntityManager()) {
                    throw new \Exception('L\'EntityManager doit être fourni!!');
                }

                return $em->find($class, $valInt);
        }
    }



    /**
     * Extract values from an object
     *
     * @param VolumeHoraireListe $object
     *
     * @return array
     */
    public function extractInts($object, $withAll = false)
    {
        $data = $this->extract($object);
        foreach ($data as $filter => $value) {
            $intData = $this->dataToInt($filter, $value);
            if (-2 == $intData && !$withAll) {
                unset($data[$filter]);
            } else {
                $data[$filter] = $intData;
            }
        }

        return $data;
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array              $data
     * @param VolumeHoraireListe $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        foreach ($data as $filter => $value) {
            if ($this->hasFilter($filter)) {
                $rule          = VolumeHoraireListe::FILTRES_LIST[$filter];
                $method        = 'set' . $rule['accessor'];
                $data[$filter] = $object->$method($this->allToData($filter, $value));
            }
        }
    }



    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }



    /**
     * @param array $filters
     *
     * @return ListeFilterHydrator
     */
    public function setFilters(array $filters): ListeFilterHydrator
    {
        $this->filters = $filters;

        return $this;
    }



    public function hasFilter(string $filter): bool
    {
        $filtres = VolumeHoraireListe::FILTRE_LIST;
        if (!in_array($filter, $filtres)) return false;

        return empty($this->filters) || in_array($filter, $this->filters);
    }
}