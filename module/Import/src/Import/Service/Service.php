<?php

namespace Import\Service;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Import\Exception\Exception;
use ZfcUser\Entity\UserInterface;
use UnicaenAuth\Service\DbUserAwareInterface;

/**
 * Classe mère des services
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Service implements ServiceManagerAwareInterface, DbUserAwareInterface {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * utilisateur courant
     *
     * @var UserInterface
     */
    protected $currentUser;

    /**
     * @var EntityManager
     */
    protected $entityManager;


    
    /**
     * Echappe une chaîne de caractères pour convertir en SQL
     *
     * @param string $string
     * @return string
     */
    public static function escapeKW($string)
    {
        return '"'.str_replace( '"', '""', strtoupper($string) ).'"';
    }

    /**
     * Echappe une valeur pour convertir en SQL
     *
     * @param mixed $value
     * @return string
     */
    public static function escape($value)
    {
        if (null === $value) return 'NULL';
        switch( gettype($value)){
            case 'string':  return "'".str_replace( "'", "''", $value )."'";
            case 'integer': return (string)$value;
            case 'boolean': return $value ? '1' : '0';
            case 'double':  return (string)$value;
            case 'array':   return '('.implode(',',array_map('Import\Service\Service::escape', $value)).')';
        }
        throw new Exception('La valeur transmise ne peut pas être convertie en SQL');
    }

    /**
     * Retourne le code SQL correspondant à la valeur transmise, précédé de "=", "IS" ou "IN" suivant le contexte.
     *
     * @param mixed $value
     * @return string
     */
    public static function equals($value)
    {
        if     (null === $value)    $eq = ' IS ';
        elseif (is_array($value))   $eq = ' IN ';
        else                        $eq = ' = ';
        
        return $eq.self::escape($value);
    }

    /**
     * Retourne une tableau des résultats de la requête transmise.
     *
     *
     * @param string $sql
     * @param array $params
     * @param string $colRes
     * @return array
     */
    protected function query( $sql, $params=null, $colRes=null )
    {
        $stmt = $this->getEntityManager()->getConnection()->executeQuery( $sql, $params );
        $result = array();
        while($r = $stmt->fetch()){
            if (empty($colRes)) $result[] = $r; else $result[] = $r[$colRes];
        }
        return $result;
    }

    /**
     * exécute un ordre SQL
     *
     * @param string $sql
     * @return integer
     */
    protected function exec( $sql )
    {
        return $this->getEntityManager()->getConnection()->exec($sql);
    }

    /**
     * Retourne le gestionnaire d'entités Doctrine
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (empty($this->entityManager))
            $this->entityManager = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        return $this->entityManager;
    }

    /**
     * Get service manager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     * @return self
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     *
     * @return UserInterface
     */
    public function getDbUser()
    {
        return $this->currentUser;
    }

    /**
     *  Set Current User
     *
     * @param UserInterface $currentUser
     */
    public function setDbUser( UserInterface $currentUser )
    {
        $this->currentUser = $currentUser;
    }

}