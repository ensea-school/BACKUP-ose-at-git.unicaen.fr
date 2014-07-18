<?php

namespace Application\Service;

use Common\Exception\LogicException;

/**
 * Service Paramètres
 *
 * Permet d'accéder facilement aux paramètres globaux de l'application
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Parametres extends AbstractService {

    /**
     * Retourne la liste des paramètres de configuration de OSE
     *
     * @return string[]
     */
    public function getList()
    {
        $repository = $this->getEntityManager()->getRepository('Application\Entity\Db\Parametre');
        /* @var $repository \Doctrine\ORM\EntityRepository */

        $result = $repository->findAll();
        foreach( $result as $index => $entity ){
            $result[$index] = $entity->getNom();
        }
        return $result;
    }


    /**
     * Retourne un paramètre
     *
     * @param string $param
     * @return string
     */
    public function get($param)
    {
        $repository = $this->getEntityManager()->getRepository('Application\Entity\Db\Parametre');
        /* @var $repository \Doctrine\ORM\EntityRepository */

        $result = $repository->findBy(array('nom' => $param));
        if (empty($result)){
            throw new LogicException('Le paramètre "'.$param.'" est invalide.');
        }
        return $result[0]->getValeur();
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
        $repository = $this->getEntityManager()->getRepository('Application\Entity\Db\Parametre');
        /* @var $repository \Doctrine\ORM\EntityRepository */

        $result = $repository->findBy(array('nom' => $param));
        if (empty($result)){
            throw new LogicException('Le paramètre "'.$param.'" est invalide.');
        }
        return $result[0]->getDescription();
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
        $repository = $this->getEntityManager()->getRepository('Application\Entity\Db\Parametre');
        /* @var $repository \Doctrine\ORM\EntityRepository */

        $result = $repository->findBy(array('nom' => $param));
        if (empty($result)){
            throw new LogicException('Le paramètre "'.$param.'" est invalide.');
        }
        $result[0]->setValeur($value);
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