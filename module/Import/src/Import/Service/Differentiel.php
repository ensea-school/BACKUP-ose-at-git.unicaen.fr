<?php

namespace Import\Service;

use Doctrine\DBAL\Driver\Statement;
use Import\Entity\Differentiel\Ligne;


/**
 * Classe permettant de récupérer le différentiel entre une table source et une table OSE
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Differentiel extends Service
{
    /**
     * Statement
     *
     * @var Statement
     */
    protected $stmt;

    /**
     * Nom de la table courante
     *
     * @var string
     */
    protected $tableName;




    public function make( $tableName )
    {
        $this->tableName = $tableName;
        $diffView = 'V_DIFF_'.strtoupper($tableName);
        $sql = 'SELECT * FROM '.$this->escapeKW($diffView);
        $this->stmt = $this->getEntityManager()->getConnection()->executeQuery( $sql, array() );
    }

    /**
     * Récupère la prochaine ligne de différentiel
     *
     * @return Ligne|false
     */
    public function fetchNext()
    {
        $data = $this->stmt->fetch();
        if ($data) return new Ligne( $this->getEntityManager(), $this->tableName, $data );
        return false;
    }
}