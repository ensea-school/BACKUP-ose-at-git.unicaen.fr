<?php

namespace Import\Exception;

use Common\Exception\RuntimeException;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Exception extends RuntimeException {

    /**
     * @param \Exception $exception
     * @param string     $tableName
     *
     * @return \Doctrine\DBAL\DBALException
     */
    public static function duringMajMVException(\Exception $exception, $tableName)
    {
        if (! $exception->getPrevious() instanceof \Doctrine\DBAL\Driver\OCI8\OCI8Exception){
            // Non gérée
            return $exception;
        }

        $msg = $exception->getPrevious()->getMessage();

        $msg = "Erreur lors de la mise à jour de la vue métarialisée liée à la table $tableName\n\n$msg";

        return new self($msg, 0, $exception);
    }

    /**
     * @param \Exception $exception
     * @param string     $tableName
     *
     * @return \Doctrine\DBAL\DBALException
     */
    public static function duringMajException(\Exception $exception, $tableName)
    {
        if (! $exception->getPrevious() instanceof \Doctrine\DBAL\Driver\OCI8\OCI8Exception){
            // Non gérée
            return $exception;
        }

        $msg = $exception->getPrevious()->getMessage();

        $msg = "Erreur lors d'une mise à jour de données dans la table $tableName\n\n$msg";

        return new self($msg, 0, $exception);
    }

}