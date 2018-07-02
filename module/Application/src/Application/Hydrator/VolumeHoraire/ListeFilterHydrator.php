<?php

namespace Application\Hydrator\VolumeHoraire;

use Application\Entity\VolumeHoraireListe;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

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
     * @param  VolumeHoraireListe $object
     *
     * @return array
     */
    public function extract($object)
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
    private function dataToInt(string $filter, $value): int
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
     *
     * @return bool|\DateTime|int|null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    private function allToData(string $filter, $value)
    {
        if (
            is_bool($value) || null === $value || is_object($value)
        ) {
            return $value;
        }

        $value = (int)$value;

        if (-2 == $value) return false;
        if (-1 == $value) return true;
        if (0 == $value) return null;

        $class = VolumeHoraireListe::FILTRES_LIST[$filter]['class'];
        switch ($class) {
            case null:
                return $value;
            case \DateTime::class:
                $dateTime = new \DateTime;
                $dateTime->setTimestamp($value);

                return $dateTime;
            default:
                if (!$em = $this->getEntityManager()) {
                    throw new \Exception('L\'EntityManager doit être fourni!!');
                }

                return $em->find($class, $value);
        }
    }



    /**
     * Extract values from an object
     *
     * @param  VolumeHoraireListe $object
     *
     * @return array
     */
    public function extractInts($object)
    {
        $data = $this->extract($object);
        foreach ($data as $filter => $value) {
            $intData = $this->dataToInt($filter, $value);
            if (-2 == $intData) {
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
     * @param  array              $data
     * @param  VolumeHoraireListe $object
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
     * @return ListeHydrator
     */
    public function setFilters(array $filters): ListeHydrator
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