<?php
namespace Import\Service;

use Import\Exception\Exception;
use Import\Entity\Differentiel\Query;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class QueryGenerator extends Service
{
    const AG_BEGIN = '-- AUTOMATIC GENERATION --';
    const AG_END = '-- END OF AUTOMATIC GENERATION --';

    /**
     * Tables
     *
     * @var string[]
     */
    protected $tables;

    /**
     * Colonnes
     *
     * @var array
     */
    protected $cols = array();





    /**
     * Retourne la liste des tables importables
     *
     * @return string[]
     */
    protected function getTables()
    {
        if (empty($this->tables)){
            $this->tables = $this->getServiceManager()->get('importServiceSchema')->getImportTables();
        }
        return $this->tables;
    }



    /**
     * Retourne la liste des colonnes importables d'une table
     * 
     * @param string $tableName
     * @return string[]
     */
    protected function getCols( $tableName )
    {
        if (! isset($this->cols[$tableName])){
            $this->cols[$tableName] = $this->getServiceManager()->get('importServiceSchema')->getImportCols( $tableName );
        }
        return $this->cols[$tableName];
    }



    /**
     * Met à jour des données d'après la requête transmise
     *
     * @param Query              $query Requête de filtrage pour la mise à jour
     * @retun self
     */
    public function execMaj( Query $query )
    {
        $currentUser = $this->getDbUser();
        if (empty($currentUser)){
            throw new Exception('Vous devez être authentifié pour réaliser cette action');
        }
        $userId = $this->escape($currentUser->getId());
        $procName = $this->escapeKW('MAJ_'.$query->getTableName());
        $conditions = $query->toSql(false);
        if (! empty($conditions)){
            $conditions = $this->escape($conditions);
        }else{
            $conditions = 'NULL';
        }
        $ignoreFields = $query->getIgnoreFields();
        if (empty($ignoreFields)) $ignoreFields = 'NULL';
        $ignoreFields = $this->escape(implode(',',$ignoreFields));

        $sql = "BEGIN OSE_IMPORT.SET_CURRENT_USER($userId);OSE_IMPORT.$procName($conditions,$ignoreFields); END;";
        try{
            $this->getEntityManager()->getConnection()->exec($sql);
        }catch(\Doctrine\DBAL\DBALException $e){
            throw Exception::duringMajException($e, $query->getTableName());
        }
        return $this;
    }



    /**
     * Retourne les identifiants des données concernés
     *
     * @param string                $tableName
     * @param string|string[]|null  $sourceCode
     * @return integer[]|null
     */
    public function getIdFromSourceCode( $tableName, $sourceCode )
    {
        if (empty($sourceCode)) return null;

        $sql = 'SELECT ID FROM '.$this->escapeKW($tableName).' WHERE SOURCE_CODE IN (:sourceCode)';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery(
                                                                $sql,
                                                                array('sourceCode' => (array)$sourceCode),
                                                                array('sourceCode' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                                                            );
        $ids = array();
        while($r = $stmt->fetch()){
            $ids[] = $r['ID'];
        }
        return $ids;
    }



    /**
     * Mettre à jour toutes les infos dans la BDD
     * 
     * @return self
     */
    public function updateViewsAndPackages()
    {
        $views = $this->makeDiffViews();

        foreach( $views as $vn => $view ){
            $this->exec( $view );
        }

        $declaration = $this->makePackageDeclaration();
        $this->exec( $declaration );

        $body = $this->makePackageBody();
        $this->exec( $body );

        return $this;
    }



    /**
     * Retourne le code source du package d'import
     *
     * @return string
     */
    protected function getPackageDeclaration()
    {
        $sql = "SELECT TEXT FROM USER_SOURCE WHERE NAME = 'OSE_IMPORT' AND type = 'PACKAGE'";
        $result = $this->query($sql, array(), 'TEXT');
        return implode("", $result);
    }



    /**
     * Retourne le code source du package d'import
     *
     * @return string
     */
    protected function getPackageBody()
    {
        $sql = "SELECT TEXT FROM USER_SOURCE WHERE NAME = 'OSE_IMPORT' AND type = 'PACKAGE BODY'";
        $result = $this->query($sql, array(), 'TEXT');
        return implode("", $result);
    }



    /**
     * Construit toutes les vues différentielles
     *
     * @return array
     */
    protected function makeDiffViews()
    {
        $tables = $this->getTables();
        $result = array();
        foreach( $tables as $table ){
            $result[$table] = $this->makeDiffView($table);
        }
        return $result;
    }



    /**
     * Construit toutes les déclarations de procédures
     *
     * @return array
     */
    protected function makeProcDeclarations()
    {
        $tables = $this->getTables();
        $result = array();
        foreach( $tables as $table ){
            $result[$table] = $this->makeProcDeclaration($table);
        }
        return $result;
    }



    /**
     * Construit tous les corps de procédures
     *
     * @return array
     */
    protected function makeProcBodies()
    {
        $tables = $this->getTables();
        $result = array();
        foreach( $tables as $table ){
            $result[$table] = $this->makeProcBody($table);
        }
        return $result;
    }



    /**
     * Constuit la nouvelle déclaration du package OSE_IMPORT
     *
     * @return string
     */
    protected function makePackageDeclaration()
    {
        $src = $this->getPackageDeclaration();
        $decl = implode( "\n", $this->makeProcDeclarations() );
        return $this->updatePackageContent($src, $decl);
    }



    /**
     * Constuit la nouvelle déclaration du package OSE_IMPORT
     *
     * @return string
     */
    protected function makePackageBody()
    {
        $src = $this->getPackageBody();
        $decl = implode( "\n\n\n\n", $this->makeProcBodies() );
        return $this->updatePackageContent($src, $decl);
    }



    /**
     * Mise à jour du contenu d'un package (déclaration ou corps)
     *
     * @param string $packageSource
     * @param string $newContent
     * @return string
     */
    protected function updatePackageContent( $packageSource, $newContent )
    {
        $src = $packageSource;
        if (null === $begin = strpos($packageSource, self::AG_BEGIN))
            throw new Exception('Le tag indiquant le début de la zone automatique du package n\'a pas été trouvée');

        if (null === $end = strpos($packageSource, self::AG_END))
            throw new Exception('Le tag indiquant la fin de la zone automatique du package n\'a pas été trouvée');

        $src = 'CREATE OR REPLACE '
             . substr( $packageSource, 0, $begin + strlen(self::AG_BEGIN) )
             . "\n\n" . $newContent . "\n\n  "
             . substr( $packageSource, $end );

        return $src;
    }



    /**
     * Génère une vue différentielle pour une table donnée
     *
     * @param string $tableName
     * @return string
     */
    protected function makeDiffView( $tableName )
    {
        $cols = $this->getCols($tableName);

        $sql = "CREATE OR REPLACE FORCE VIEW OSE.V_DIFF_$tableName AS
select diff.* from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
  ".$this->formatColQuery($cols, '  CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.:column ELSE S.:column END :column', ",\n  ").",
  ".$this->formatColQuery($cols, '  CASE WHEN D.:column <> S.:column OR (D.:column IS NULL AND S.:column IS NOT NULL) OR (D.:column IS NOT NULL AND S.:column IS NULL) THEN 1 ELSE 0 END U_:column',",\n  " ). "
FROM
  $tableName D
  FULL JOIN SRC_$tableName S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR ".$this->formatColQuery($cols, 'D.:column <> S.:column OR (D.:column IS NULL AND S.:column IS NOT NULL) OR (D.:column IS NOT NULL AND S.:column IS NULL)',"\n  OR ")."
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1";
        return $sql;
    }



    /**
     * Génère une déclaration de procédure pour une table donnée
     * 
     * @param string $tableName
     * @return string
     */
    protected function makeProcDeclaration( $tableName )
    {
        return "  PROCEDURE MAJ_$tableName(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');";
    }



    /**
     * Génère un corps de procédure pour une table donnée
     * 
     * @param string $tableName
     * @return string
     */
    protected function makeProcBody( $tableName )
    {
        $cols = $this->getCols($tableName);

        $sql = "  PROCEDURE MAJ_$tableName(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_$tableName%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_$tableName.* FROM V_DIFF_$tableName ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.$tableName
            ( id, ".$this->formatColQuery($cols).", source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,$tableName"."_ID_SEQ.NEXTVAL), ".$this->formatColQuery($cols,'diff_row.:column').", diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          ".$this->formatColQuery(
                    $cols,
                    "IF (diff_row.u_:column = 1 AND IN_COLUMN_LIST(':column',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.$tableName SET :column = diff_row.:column WHERE ID = diff_row.id; END IF;"
                    ,"\n          "
            )."

        WHEN 'delete' THEN
          UPDATE OSE.$tableName SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          ".$this->formatColQuery(
                    $cols,
                    "IF (diff_row.u_:column = 1 AND IN_COLUMN_LIST(':column',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.$tableName SET :column = diff_row.:column WHERE ID = diff_row.id; END IF;"
                    ,"\n          "
            )."
          UPDATE OSE.$tableName SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_$tableName;";
        return $sql;
    }



    /**
     * Retourne une chaîne SQL correspondant, pour chaque colonne donnée, au résultat du formatage donné,
     * concaténé selon le séparateur transmis.
     *
     * L'opérateur $c permet de situer l'endroit où devont être placées les colonnes.
     *
     * @param array $cols
     * @param string $format
     * @param string $separator
     * @return string
     */
    protected function formatColQuery( array $cols, $format=':column', $separator=',' )
    {
        $res = array();
        foreach( $cols as $col ){
            $res[] = str_replace( ':column', $col, $format );
        }
        return implode( $separator, $res );
    }

}