<?php

namespace GenDbStructure;

use Doctrine\ORM\EntityManager;
use UnicaenApp\Service\EntityManagerAwareTrait;

class DdlGen
{
    const SEQUENCES       = 'Séquences';
    const TABLES          = 'Tables';
    const PACKAGES        = 'Packages';
    const VIEWS           = 'Vues';
    const MVIEWS          = 'Vues matérialisées';
    const PACKAGES_BODIES = 'Packages Bodies';
    const CONSTRAINTS     = 'Clés étrangères';
    const INDEXES         = 'Indexs';
    const TRIGGERS        = 'Triggers';
    const JOBS            = 'Jobs';

    use EntityManagerAwareTrait;

    /**
     * @var array
     */
    private $ddlQueries = [];

    /**
     * @var array
     */
    private $ddl    = [];

    private $tables = [];



    public function __construct(EntityManager $em)
    {
        $this->setEntityManager($em);
    }



    protected function init()
    {
        $sql = "
        BEGIN
            DBMS_METADATA.SET_TRANSFORM_PARAM(DBMS_METADATA.SESSION_TRANSFORM, 'EMIT_SCHEMA',        false);
            DBMS_METADATA.SET_TRANSFORM_PARAM(DBMS_METADATA.SESSION_TRANSFORM, 'SQLTERMINATOR',      true );
            DBMS_METADATA.SET_TRANSFORM_PARAM(DBMS_METADATA.SESSION_TRANSFORM, 'PRETTY',             true );
            DBMS_METADATA.SET_TRANSFORM_PARAM(DBMS_METADATA.SESSION_TRANSFORM, 'SEGMENT_ATTRIBUTES', false);
            DBMS_METADATA.SET_TRANSFORM_PARAM(DBMS_METADATA.SESSION_TRANSFORM, 'REF_CONSTRAINTS',    false);
            DBMS_METADATA.SET_TRANSFORM_PARAM(DBMS_METADATA.SESSION_TRANSFORM, 'STORAGE',            false);
        END;";

        $this->getEntityManager()->getConnection()->executeQuery($sql);

        return $this;
    }



    private function getDdlQueries()
    {
        if (empty($this->ddlQueries)) {
            $this->makeDdlQueries();
        }

        return $this->ddlQueries;
    }



    public function addDdlQuery($type, $sql, $callback = null)
    {
        $this->ddlQueries[$type] = [
            'sql'      => $sql,
            'callback' => $callback,
        ];

        return $this;
    }



    protected function makeDdl($types = null)
    {
        $this->init();

        $queries = $this->getDdlQueries();

        if ($types !== null) {
            $types = (array)$types;
        } else {
            $types = array_keys($queries);
        }

        foreach ($types as $type) {
            $query = $queries[$type];
            $qr    = $this->getEntityManager()->getConnection()->fetchAll($query['sql']);
            foreach ($qr as $ql) {
                $name = $ql['OBJECT_NAME'];
                $ddl  = trim($ql['OBJECT_DDL']);
                if (isset($query['callback'])) {
                    $this->ddl[$type][$name] = $query['callback']($name, $ddl);
                } else {
                    $this->ddl[$type][$name] = $ddl;
                }
            }
        }

        $this->ddl[self::JOBS] = [];

        $this->ddl[self::JOBS]['OSE_CHARGENS_CALCUL_EFFECTIFS'] = "BEGIN
  DBMS_SCHEDULER.CREATE_JOB (
      job_name => 'OSE_CHARGENS_CALCUL_EFFECTIFS',
    job_type => 'STORED_PROCEDURE',
    job_action => 'OSE_CHARGENS.CALC_ALL_EFFECTIFS',
    number_of_arguments => 0,
    start_date => TO_TIMESTAMP_TZ('2017-04-27 17:04:05.788458000 EUROPE/PARIS','YYYY-MM-DD HH24:MI:SS.FF TZR'),
    repeat_interval => 'FREQ=DAILY;BYHOUR=20;BYMINUTE=0;BYSECOND=0',
    end_date => NULL,
    enabled => TRUE,
    auto_drop => FALSE,
    comments => 'Calcul général des effectifs des charges d''enseignement'
  );
END;
/";

        $this->ddl[self::JOBS]['OSE_FORMULE_REFRESH'] = "BEGIN
  DBMS_SCHEDULER.CREATE_JOB (
      job_name => 'OSE_FORMULE_REFRESH',
    job_type => 'STORED_PROCEDURE',
    job_action => 'OSE_FORMULE.CALCULER_TOUT',
    number_of_arguments => 1,
    start_date => TO_TIMESTAMP_TZ('2014-12-09 10:25:17.032495000 EUROPE/PARIS','YYYY-MM-DD HH24:MI:SS.FF TZR'),
    repeat_interval => 'FREQ=DAILY;BYDAY=MON,TUE,WED,THU,FRI,SAT,SUN;BYHOUR=5;BYMINUTE=0;BYSECOND=0',
    end_date => NULL,
    enabled => TRUE,
    auto_drop => FALSE,
    comments => 'Recalcul général de la formule de calcul'
  );
END;
/";

        $this->ddl[self::JOBS]['MAJ_ALL_TBL'] = "BEGIN
  DBMS_SCHEDULER.CREATE_JOB (
      job_name => 'MAJ_ALL_TBL',
    job_type => 'STORED_PROCEDURE',
    job_action => 'OSE_DIVERS.CALCULER_TABLEAUX_BORD',
    number_of_arguments => 0,
    start_date => TO_TIMESTAMP_TZ('2017-11-06 16:03:22.734108000 EUROPE/PARIS','YYYY-MM-DD HH24:MI:SS.FF TZR'),
    repeat_interval => 'FREQ=DAILY;BYHOUR=2,14;BYMINUTE=0;BYSECOND=0',
    end_date => NULL,
    enabled => TRUE,
    auto_drop => FALSE,
    comments => 'Mise à jour de tous les tableaux de bord (hors formule de calcul)'
  );
END;
/";

    }



    private function comment1($str)
    {
        $len = 50;

        $result = str_pad('', $len, '-') . "\n";
        $result .= "-- " . $str . "\n";
        $result .= str_pad('', $len, '-') . "\n\n";

        return $result;
    }



    private function comment2($str)
    {
        return '-- ' . $str . "\n";
    }



    public function getDdl($types = null)
    {
        $this->makeDdl($types);

        $content = $this->comment1('DDL de la base de données OSE');

        $content .= "\nSET DEFINE OFF;\n\n\n";

        foreach ($this->ddl as $type => $query) {
            $content .= $this->comment1($type);
            foreach ($query as $name => $oddl) {
                $content .= $this->comment2($name) . $oddl . "\n\n";
            }
        }

        return $content;
    }



    private function getTables()
    {
        if (empty($this->tables)) {
            $sql = "SELECT table_name FROM ALL_TABLES WHERE owner = 'OSE'";
            $ts  = $this->getEntityManager()->getConnection()->fetchAll($sql);
            foreach ($ts as $t) {
                $this->tables[] = $t['TABLE_NAME'];
            }
        }

        return $this->tables;
    }



    private function getCurrentSchema()
    {
        $sql = "SELECT user scname FROM dual";

        return $this->getEntityManager()->getConnection()->fetchAssoc($sql)['SCNAME'];
    }



    public function delSchemaReff($name, $sql)
    {
        $tables        = $this->getTables();
        $currentSchema = $this->getCurrentSchema();

        foreach ($tables as $table) {
            $sql = str_replace('"' . $currentSchema . '"."' . $table . '"', '"' . $table . '"', $sql);
        }

        return $sql;
    }



    public function delMViewTableSpaceInfos($name, $sql)
    {
        return substr($sql, 0, strpos($sql, ')') + 1)
            . ' AS' . "\n"
            . substr($sql, strpos($sql, 'SELECT'));
    }



    public function delAutoGenPackageCode($sql)
    {
        return substr($sql, 0, strpos($sql, '-- AUTOMATIC GENERATION --') + 26)
            . substr($sql, strpos($sql, '-- END OF AUTOMATIC GENERATION --') - 4);
    }



    private function makeDdlQueries()
    {
        $q = "SELECT
            sequence_name object_name,
            to_clob('CREATE SEQUENCE ' || sequence_name || ' INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;') object_ddl
          FROM
            ALL_SEQUENCES
          WHERE
            sequence_owner = 'OSE'
          ORDER BY
            sequence_name
        ";
        $this->addDdlQuery(self::SEQUENCES, $q);


        $q = "SELECT
            table_name object_name,
            DBMS_METADATA.get_ddl ('TABLE', table_name, user) 
            || CASE WHEN table_name like 'TBL_%' THEN 
              '\nALTER TABLE ' || table_name || ' NOLOGGING;'
            ELSE '' END object_ddl
          FROM
            ALL_TABLES
          WHERE
            owner = 'OSE'
            AND table_name NOT LIKE 'MV_%'
            AND table_name NOT LIKE 'TBL_NOEUD'
            AND table_name NOT LIKE 'UNICAEN_%'
          ORDER BY
            table_name
        ";
        $this->addDdlQuery(self::TABLES, $q);


        $q = "SELECT
            object_name object_name,
            DBMS_METADATA.get_ddl ('PACKAGE', object_name, user) object_ddl
          FROM
            USER_OBJECTS
          WHERE
            object_type = 'PACKAGE'
            AND object_name NOT LIKE '%_AUTOGEN_PROCS_%'
          ORDER BY
            object_name
        ";
        $this->addDdlQuery(self::PACKAGES, $q, function ($name, $ddl) {
            $res = trim(substr($ddl, 0, strpos($ddl, 'CREATE OR REPLACE PACKAGE BODY')));

            return $res;
        });


        $q = "SELECT
            view_name object_name,
            DBMS_METADATA.get_ddl ('VIEW', view_name, user) object_ddl
          FROM
            ALL_VIEWS
          WHERE
            owner='OSE'
            AND view_name NOT LIKE 'SRC_%'
            AND view_name NOT LIKE 'V_DIFF_%'
            AND view_name NOT LIKE 'V_SYMPA_%'
            AND view_name NOT LIKE 'V_UNICAEN_%'
          ORDER BY
            view_name
        ";
        $this->addDdlQuery(self::VIEWS, $q);


        $q = "SELECT
            mview_name object_name,
            DBMS_METADATA.get_ddl ('MATERIALIZED_VIEW', mview_name, user) object_ddl
          FROM
            ALL_MVIEWS
          WHERE
            owner='OSE'
            AND mview_name NOT LIKE 'MV_%'
          ORDER BY
            mview_name
        ";
        $this->addDdlQuery(self::MVIEWS, $q, function ($name, $ddl) {
            return $this->delMViewTableSpaceInfos($name, $ddl);
        });


        $q = "SELECT
            object_name object_name,
            DBMS_METADATA.get_ddl ('PACKAGE_BODY', object_name, user) object_ddl
          FROM
            USER_OBJECTS
          WHERE
            object_type = 'PACKAGE'
            AND object_name NOT LIKE '%_AUTOGEN_PROCS_%'
          ORDER BY
            object_name
        ";
        $this->addDdlQuery(self::PACKAGES_BODIES, $q);


        $q = "SELECT
            constraint_name object_name,
            DBMS_METADATA.get_ddl ('REF_CONSTRAINT', constraint_name) object_ddl
          FROM
            all_constraints
          WHERE
            owner = 'OSE'
            AND constraint_type = 'R'
          ORDER BY
            constraint_name
        ";
        $this->addDdlQuery(self::CONSTRAINTS, $q);


        $q = "SELECT
            i.index_name object_name,
            DBMS_METADATA.get_ddl ('INDEX', i.index_name, user) object_ddl
          FROM
            ALL_INDEXES i
            LEFT JOIN all_constraints c ON c.constraint_name = i.index_name OR c.constraint_name || '_IDX' = i.index_name 
          WHERE
            i.owner='OSE'
            AND i.index_name NOT LIKE 'MV_%'
            AND i.index_name NOT LIKE 'UNICAEN_%'
            AND c.constraint_name IS NULL
            AND c.constraint_name NOT IN (
            'TBL_PJD_UN_IDX','TBL_PJF_UN_IDX','TBL_SERVICE_REFERENTIEL_UN_IDX','TBL_SERVICE_SAISIE_UN_IDX'
            )
          ORDER BY
            i.index_name
        ";
        $this->addDdlQuery(self::INDEXES, $q, function ($name, $ddl) {
            return trim(str_replace(";", '', $ddl)) . ';';
        });


        $q = "SELECT 
            trigger_name object_name, 
            DBMS_METADATA.get_ddl ('TRIGGER', trigger_name, user) object_ddl
          FROM 
            ALL_TRIGGERS
          WHERE
            owner='OSE'
          ORDER BY 
            trigger_name
        ";
        $this->addDdlQuery(self::TRIGGERS, $q, function ($name, $ddl) {
            return $this->delSchemaReff($name, $ddl);
        });
    }
}