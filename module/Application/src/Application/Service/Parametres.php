<?php

namespace Application\Service;

use Application\Entity\Db\Parametre as ParametreEntity;
use LogicException;

/**
 * Service Paramètres
 *
 * Permet d'accéder facilement aux paramètres globaux de l'application
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Parametres extends AbstractService {

    /**
     *
     * @var array
     */
    protected $cache = [];

    protected function getCache($param)
    {
        if (! $this->cache){
            $repository = $this->getEntityManager()->getRepository(ParametreEntity::class);
            /* @var $repository \Doctrine\ORM\EntityRepository */
            $list = $repository->findAll();

            foreach( $list as $entity ){ /* @var $entity \Application\Entity\Db\Parametre */
                $this->cache[$entity->getNom()] = $entity->getValeur();
            }
        }
        if ($param)
            return isset($this->cache[$param]) ? $this->cache[$param] : null;
        else
            return $this->cache;
    }

    /**
     * Retourne la liste des paramètres de configuration de OSE
     *
     * @return string[]
     */
    public function getList()
    {
        return $this->getCache();
    }

    /**
     * Retourne la description d'un paramètre
     *
     * @param string $param
     * @return string
     * @throws LogicException
     */
    public function getDescription($param)
    {
        $repository = $this->getEntityManager()->getRepository(ParametreEntity::class);
        /* @var $repository \Doctrine\ORM\EntityRepository */

        $result = $repository->findBy(['nom' => $param]);
        if (empty($result)){
            throw new LogicException('Le paramètre "'.$param.'" est invalide.');
        }
        return $result[0]->getDescription();
    }

    /**
     * Retourne un paramètre
     *
     * @param string $param
     * @return string
     */
    public function get($param)
    {
        return $this->getCache($param);
    }

    /**
     * Affecte une valeur à un paramètre
     *
     * @param string $param
     * @param string $value
     * @return self
     */
    public function set($param, $value)
    {
        $repository = $this->getEntityManager()->getRepository(ParametreEntity::class);
        /* @var $repository \Doctrine\ORM\EntityRepository */

        $result = $repository->findBy(['nom' => $param]);
        if (empty($result)){
            throw new LogicException('Le paramètre "'.$param.'" est invalide.');
        }
        $result[0]->setValeur($value);
        $this->cache[$param] = $value;
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * Getter
     *
     * @param string $name
     * @return string
     */
    public function __get($name)
    {
         return $this->get($name);
    }

    /**
     * Setter
     *
     * @param string $name
     * @param string $value
     * @return self
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }
}