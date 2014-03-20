<?php

namespace Import\Service;

use Doctrine\DBAL\Driver\Statement;
use Import\Entity\Differentiel\Ligne;
use Import\Entity\Differentiel\Query;


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





    /**
     * Construit un différentiel
     *
     * @param string            $query  Requête de filtrage
     * @return self
     */
    public function make( Query $query )
    {
        $this->tableName = $query->getTableName();
        $this->stmt = $this->getEntityManager()->getConnection()->executeQuery( $query->toSql(), array() );
        return $this;
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

    /**
     * Retourne toutes les lignes concernées
     *
     * @return Ligne[]
     */
    public function fetchAll()
    {
        $result = array();
        while( $data = $this->stmt->fetch() ){
            if ($data) $result[] = new Ligne( $this->getEntityManager(), $this->tableName, $data );
        }
        return $result;
    }
}