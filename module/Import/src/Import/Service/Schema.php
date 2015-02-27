<?php
namespace Import\Service;

use Import\Exception\Exception;
use Import\Entity\Schema\Column;

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
    protected $schema;





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
     * @return Column[][]
     */
    public function makeSchema()
    {
        $sql = 'SELECT * FROM V_IMPORT_TAB_COLS';
        $d = $this->query( $sql, array() );

        $sc = array();
        foreach( $d as $col ){
            $column = new Column;
            $column->dataType        = $col['DATA_TYPE'];
            $column->length          = (null === $col['LENGTH']) ? null : (integer)$col['LENGTH'];
            $column->nullable        = $col['NULLABLE'] == '1';
            $column->hasDefault      = $col['HAS_DEFAULT'] == '1';
            $column->refTableName    = $col['C_TABLE_NAME'];
            $column->refColumnName   = $col['C_COLUMN_NAME'];
            $column->importActif     = $col['IMPORT_ACTIF'] == '1';
            $sc[$col['TABLE_NAME']][$col['COLUMN_NAME']] = $column;
        }
        return $sc;
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
        ) t JOIN user_tables ut ON (ut.table_name = SUBSTR(name,5))
        WHERE name LIKE 'SRC_%'";
        return $this->query( $sql, array(), 'TABLE_NAME');
    }

    /**
     * Retourne la liste des tables ayant des vues matérialisées
     *
     * @return string[]
     */
    public function getImportMviews()
    {
        $sql = "SELECT mview_name FROM USER_MVIEWS WHERE mview_name LIKE 'MV_%'";
        $stmt = $this->getEntityManager()->getConnection()->query($sql);
        $mviews = [];
        while ($d = $stmt->fetch()){
            $mvn = substr( $d['MVIEW_NAME'], 3 );
            $mviews[] = $mvn;
        }
        return $mviews;
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