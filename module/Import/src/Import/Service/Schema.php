<?php
namespace Import\Service;

use Import\Exception\Exception;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Schema extends Service
{
    /**
     * Schéma
     * 
     * @var array
     */
    protected static $schema;





    /**
     * Retourne le schéma de la BDD
     *
     * @return array
     */
    public function getSchema()
    {
        if (empty($this->schema)){
            $this->schema = $this->makeSchema();
        }
        return $this->schema;
    }



    /**
     * @return array
     */
    public function makeSchema()
    {
        throw new Exception('NOT YET IMPLEMENTED');
    }



    /**
     * retourne la liste des tables supportées par l'import automatique
     *
     * @return array
     */
    public function getImportTables()
    {
        $sql = "SELECT SUBSTR(name,5) as TABLE_NAME FROM (
            SELECT mview_name AS name FROM USER_MVIEWS
            UNION SELECT view_name AS name FROM USER_VIEWS
            UNION SELECT TABLE_NAME AS name FROM USER_TABLES
        ) t WHERE name LIKE 'SRC_%'";
        return $this->query( $sql, array(), 'TABLE_NAME');
    }



    /**
     * Retourne les colonnes concernées par l'import pour une table donnée
     */
    public function getImportCols( $tableName )
    {
        $sql = "
        SELECT
            utc.COLUMN_NAME
        FROM
          USER_TAB_COLS utc
          JOIN ALL_TAB_COLS atc ON (atc.table_name = 'SRC_' || utc.table_name AND atc.column_name = utc.column_name)
        WHERE
          utc.COLUMN_NAME NOT IN ('ID')
          AND utc.COLUMN_NAME NOT LIKE 'HISTO_%'
          AND utc.COLUMN_NAME NOT LIKE 'SOURCE_%'
          AND utc.table_name = :tableName
        ORDER BY
          utc.COLUMN_NAME";

        return $this->query( $sql, array('tableName' => $tableName), 'COLUMN_NAME');
    }

}