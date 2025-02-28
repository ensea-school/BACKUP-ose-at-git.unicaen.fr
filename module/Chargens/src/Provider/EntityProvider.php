<?php

namespace Chargens\Provider;

class EntityProvider
{
    /**
     * @var ChargensProvider
     */
    private $chargens;

    /**
     * @var array
     */
    private $entities = [];



    /**
     * NoeudProvider constructor.
     *
     * @param ChargensProvider $chargens
     */
    public function __construct(ChargensProvider $chargens)
    {
        $this->chargens = $chargens;
    }



    /**
     * @param array|object $entity
     *
     * @return $this
     */
    public function add($entity)
    {
        if (is_array($entity)) {
            foreach ($entity as $e) {
                $this->__add($e);
            }
        } else {
            $this->__add($entity);
        }

        return $this;
    }



    /**
     * @param object $entity
     */
    private function __add($entity)
    {
        if ($entity && method_exists($entity, 'getId') && $entity->getId()) {
            $name = get_class($entity);
            if (($pos = strpos($name, 'Application')) > 0) {
                $name = substr($name, $pos);
            }
            $id = $entity->getId();

            $this->entities[$name][$id] = $entity;
        }
    }



    /**
     * @param string        $name
     * @param integer|array $id
     *
     * @return $this
     */
    public function load($name, $id)
    {
        if (is_array($id)) {
            foreach ($id as $k => $i) {
                if ($this->has($name, $i)) {
                    unset($id[$k]);
                }
            }
            $repo = $this->chargens->getEntityManager()->getRepository($name);
            $qb   = $repo->createQueryBuilder('ent');
            $qb->andWhere('ent IN (:ids)')->setParameter('ids', $id);
            $entity = $qb->getQuery()->execute();
        } else {
            if ($this->has($name, $id)) {
                $entity = null;
            } else {
                $entity = $this->chargens->getEntityManager()->find($name, $id);
            }
        }

        if ($entity) {
            $this->add($entity);
        }

        return $this;
    }



    /**
     * @param string  $name
     * @param integer $id
     *
     * @return bool
     */
    public function has($name, $id)
    {
        return isset($this->entities[$name][$id]);
    }



    /**
     * @param string  $name
     * @param integer $id
     *
     * @return null|object
     */
    public function get($name, $id)
    {
        if (!$id) return null;

        if (!$this->has($name, $id)) {
            $this->load($name, $id);
        }

        if ($this->has($name, $id)) {
            return $this->entities[$name][$id];
        } else {
            return null;
        }
    }



    /**
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @return array
     * @since PHP 5.6.0
     *
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [];
    }

}