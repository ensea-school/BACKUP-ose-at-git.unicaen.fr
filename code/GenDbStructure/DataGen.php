<?php

namespace GenDbStructure;

use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Service\EntityManagerAwareTrait;

class DataGen
{
    use EntityManagerAwareTrait;
    use SourceServiceAwareTrait;

    /**
     * @var array
     */
    protected $tablesInfo = [];

    protected $breaks = false;

    protected $tablesSel  = [
        'AFFECTATION'                  => "source_code = 'local-aff-admin'",
        'ANNEE'                        => '',
        'CATEGORIE_PRIVILEGE'          => '',
        'CC_ACTIVITE'                  => '',
        'CIVILITE'                     => '',
        'DEPARTEMENT'                  => '',
        'DISCIPLINE'                   => '',
        'DOMAINE_FONCTIONNEL'          => "source_code = '000'",
        'ETABLISSEMENT'                => '',
        'ETAT_VOLUME_HORAIRE'          => '',
        'FONCTION_REFERENTIEL'         => '',
        'GRADE'                        => '',
        'GROUPE'                       => '',
        'IMPORT_TABLES'                => '',
        'INDICATEUR'                   => '',
        'MESSAGE'                      => '',
        'MOTIF_MODIFICATION_SERVICE'   => '',
        'MOTIF_NON_PAIEMENT'           => '',
        'PARAMETRE'                    => '',
        'PAYS'                         => '',
        'PERIMETRE'                    => '',
        'PERIODE'                      => '',
        'PLAFOND'                      => '',
        'PLAFOND_ETAT'                 => '',
        'PRIVILEGE'                    => '',
        'REGLE_STRUCTURE_VALIDATION'   => '',
        'ROLE'                         => '',
        'ROLE_PRIVILEGE'               => '',
        'SCENARIO'                     => 'structure_id IS NULL',
        'SOURCE'                       => "code='OSE'",
        'STATUT_INTERVENANT'           => '',
        'STATUT_PRIVILEGE'             => '',
        'TAUX_HORAIRE_HETD'            => '',
        'TBL'                          => '',
        'TYPE_AGREMENT'                => '',
        'TYPE_AGREMENT_STATUT'         => '',
        'TYPE_CONTRAT'                 => '',
        'TYPE_DOTATION'                => "SOURCE_CODE IN ('dotation-initiale','dotation-complementaire','abondement')",
        'TYPE_HEURES'                  => '',
        'TYPE_INTERVENANT'             => '',
        'TYPE_INTERVENTION'            => "code IN ('CM','TD','TP','Stage','Projet','Mémoire')",
        'TYPE_PIECE_JOINTE'            => '',
        'TYPE_PIECE_JOINTE_STATUT'     => '',
        'TYPE_RESSOURCE'               => '',
        'TYPE_STRUCTURE'               => '',
        'TYPE_VALIDATION'              => '',
        'TYPE_VOLUME_HORAIRE'          => '',
        'UTILISATEUR'                  => "username = 'admin'",
        'WF_ETAPE'                     => '',
        'WF_ETAPE_DEP'                 => '',
    ];



    public function __construct(EntityManager $em)
    {
        $this->setEntityManager($em);
    }



    private function makeTablesInfo()
    {
        $ti  = [];
        $sql = "
            WITH refs AS (
              SELECT
                cc.table_name,
                cc.column_name,
                rcc.table_name r_table_name,
                rcc.column_name r_column_name
              FROM
                all_constraints c
                JOIN all_cons_columns cc ON cc.constraint_name = c.constraint_name AND cc.table_name = c.table_name
                JOIN all_constraints rc ON rc.owner = c.owner AND rc.constraint_type = 'P' AND rc.constraint_name = c.r_constraint_name
                JOIN all_cons_columns rcc ON rcc.constraint_name = rc.constraint_name AND rcc.position = cc.position
              WHERE
                c.constraint_type = 'R'
                AND c.owner = 'OSE'
            )
            SELECT 
              utc.table_name,
              utc.column_name,
              utc.data_type,
              refs.r_table_name,
              refs.r_column_name
            FROM 
              all_tables ut
              JOIN all_tab_cols utc ON utc.owner = 'OSE' AND utc.table_name = ut.table_name
              LEFT JOIN refs ON refs.table_name = utc.table_name AND refs.column_name = utc.column_name
            WHERE 
              ut.owner = 'OSE'
              AND ut.table_name <> 'WF_DEP_BLOQUANTE'
              AND ut.table_name NOT LIKE 'MV_%'
              AND ut.table_name NOT LIKE 'TBL_%'
              AND ut.table_name NOT LIKE 'UNICAEN_%'
            ORDER BY
               ut.table_name,
               utc.internal_column_id 
            ";
        $ts  = $this->getEntityManager()->getConnection()->fetchAll($sql);
        foreach ($ts as $t) {
            $ti[$t['TABLE_NAME']][$t['COLUMN_NAME']] = [
                'type'              => $t['DATA_TYPE'],
                'constraint_table'  => $t['R_TABLE_NAME'],
                'constraint_column' => $t['R_COLUMN_NAME'],
            ];
        }

        $tablesOk = [
            'SOURCE',
        ];

        for ($i = 0; $i < 20; $i++) {
            foreach ($ti as $table => $cols) {
                if (!in_array($table, $tablesOk)) {
                    $ok = true;
                    foreach ($cols as $col => $def) {
                        $ctable = $def['constraint_table'];
                        if ($ctable) {
                            if ((!in_array($ctable, $tablesOk)) && $ctable != $table) {
                                $ok = false;
                            }
                        }
                    }

                    if ($ok) {
                        $tablesOk[] = $table;
                    }
                }
            }
        }

        foreach ($ti as $table => $cols) {
            if (!in_array($table, $tablesOk)) {
                $tablesOk[] = $table;
            }
        }

        $res = [];
        foreach ($tablesOk as $table) {
            $res[$table] = $ti[$table];
        }

        return $res;
    }



    private function getTablesInfo($tableName = null)
    {
        if (empty($this->tablesInfo)) {
            $this->tablesInfo = $this->makeTablesInfo();
        }

        if ($tableName) {
            return $this->tablesInfo[$tableName];
        }

        return $this->tablesInfo;
    }



    private function getCodeColumn($tableName)
    {
        $codesCols = [
            'SOURCE_CODE',
            'CODE',
            'LIBELLE_COURT',
            'ID',
        ];

        $def = $this->getTablesInfo($tableName);

        foreach ($codesCols as $col) {
            if (isset($def[$col])) {
                return $col;
            }
        }

        throw new \Exception('Colonne de code non trouvée pour la table ' . $tableName);
    }



    protected function getTablesSel()
    {
        $roles   = "('administrateur','gestionnaire-composante','superviseur-etablissement')";
        $statuts = "('SALAR_ETRANGER','ENS_2ND_DEG','ENS_CH','ASS_MI_TPS','ATER','ATER_MI_TPS','DOCTOR','ENS_CONTRACT',
        'LECTEUR','MAITRE_LANG','BIATSS','SALAR_PRIVE','SALAR_PUBLIC','AUTO_LIBER_INDEP','SS_EMPLOI_NON_ETUD','AUTRES',
        'NON_AUTORISE')";

        $ts = $this->tablesSel;

        $ts['ROLE']           = "code IN $roles";
        $ts['ROLE_PRIVILEGE'] = "role_id IN (SELECT id FROM role WHERE code IN $roles)";

        $ts['STATUT_INTERVENANT']       = "source_code IN $statuts";
        $ts['STATUT_PRIVILEGE']         = "statut_id IN (SELECT id FROM statut_intervenant WHERE source_code IN $statuts)";
        $ts['TYPE_AGREMENT_STATUT']     = "statut_intervenant_id IN (SELECT id FROM statut_intervenant WHERE source_code IN $statuts)";
        $ts['TYPE_PIECE_JOINTE_STATUT'] = "statut_intervenant_id IN (SELECT id FROM statut_intervenant WHERE source_code IN $statuts) AND type_piece_jointe_id IN (SELECT id FROM type_piece_jointe WHERE histo_destruction IS NULL)";

        return $ts;
    }



    public function getDdlData()
    {
        $ts   = $this->getTablesInfo();
        $tSel = $this->getTablesSel();

        $res = '';
        foreach ($ts as $table => $def) {
            if (isset($tSel[$table])) {
                $imq = $this->makeInsertMetaQuery($table, $def, $tSel[$table]);
                $res .= "\n\n-- Table $table\n";
                $res .= $this->getResMetaQuery($imq);
            }
        }

        return $res;
    }



    protected function getResMetaQuery($sql)
    {
        $res     = $this->getEntityManager()->getConnection()->fetchAll($sql);
        $inserts = '';

        foreach ($res as $isql) {
            $inserts .= $isql['ISQL'];
        }

        return $inserts;
    }



    protected function makeInsertMetaQuery($tableName, $tableDef, $conds = '')
    {
        $hasHisto = isset($tableDef['HISTO_DESTRUCTION']);

        $tc = [];
        foreach ($tableDef as $c => $t) {
            $tc[$c] = $this->formatCol($tableName, $c, $t);
        }

        if ($hasHisto) {
            $tc['HISTO_CREATION']        = 'SYSDATE';
            $tc['HISTO_CREATEUR_ID']     = '1';
            $tc['HISTO_MODIFICATION']    = 'SYSDATE';
            $tc['HISTO_MODIFICATEUR_ID'] = '1';
            unset($tc['HISTO_DESTRUCTION']);
            unset($tc['HISTO_DESTRUCTEUR_ID']);
        }

        $ret            = $this->breaks ? "\n" : "";
        $beforeColName  = $this->breaks ? "\t" : "";
        $beforeColValue = $this->breaks ? "\t" : "";

        $isql = "SELECT 'INSERT INTO $tableName($ret" . $beforeColName
            . implode(", $ret" . $beforeColName, array_keys($tc))
            . "$ret) VALUES ($ret" . $beforeColValue
            . implode(", $ret" . $beforeColValue, array_values($tc))
            . "$ret);$ret\n' isql FROM " . $tableName . ' WHERE 1=1';
        if ($hasHisto) {
            $isql .= ' AND histo_destruction IS NULL';
        }

        if ($conds) {
            $isql .= " AND ($conds)";
        }

        return $isql;
    }



    protected function formatCol($table, $column, $def)
    {
        if ('ID' == $column && $table != 'ANNEE') {
            return substr($table, 0, 23) . '_ID_SEQ.NEXTVAL';
        }

        if ('SOURCE_ID' == $column){
            return "(SELECT id FROM source WHERE code = ''OSE'')";
        }

        if ('TYPE_HEURES_ELEMENT_ID' == $column && 'TYPE_HEURES' == $table){
            return 'TYPE_HEURES_ID_SEQ.CURRVAL';
        }

        if ('DOMAINE_FONCTIONNEL_ID' == $column){
            return "(SELECT id FROM domaine_fonctionnel WHERE source_code = ''000'')";
        }

        if ('STRUCTURE_ID' == $column){
            return "(SELECT id FROM structure WHERE source_code = ''UNIV'')";
        }

        if ('PRIVILEGE_ID' == $column){
            $cppSql = "SELECT cp.code || '-' || p.code FROM privilege p JOIN categorie_privilege cp ON cp.id = p.categorie_id WHERE p.id = privilege_id";
            return "(SELECT p.id FROM privilege p JOIN categorie_privilege cp ON p.categorie_id = cp.id WHERE cp.code || ''-'' || p.code = ''' ||($cppSql)|| ''')";
        }



        if ($def['constraint_table']) {
            $ctable  = $def['constraint_table'];
            $ccol    = $def['constraint_column'];
            $codeCol = $this->getCodeColumn($ctable);
            $ccsql   = "SELECT $codeCol FROM $ctable WHERE $ccol = $column";
            $csql    = "'(SELECT $ccol FROM $ctable WHERE ROWNUM = 1 AND $codeCol = q''[' || ($ccsql) || ']'')'";

            return "' || CASE WHEN $column IS NULL THEN 'NULL' ELSE $csql END || '";
        }

        switch ($def['type']) {
            case 'NUMBER':
            case 'FLOAT':
                return "' || CASE WHEN $column IS NULL THEN 'NULL' ELSE '' || $column || '' END || '";
            break;
            case 'VARCHAR2':
                return "' || CASE WHEN $column IS NULL THEN 'NULL' ELSE 'q''[' || $column || ']''' END || '";
            case 'CLOB':
                return "' || CASE WHEN $column IS NULL THEN 'NULL' ELSE 'q''[' || to_char($column) || ']''' END || '";
            break;
            case 'DATE':
                return "' || CASE WHEN $column IS NULL THEN 'NULL' ELSE 'to_date(''' || to_char($column,'YYYY-MM-DD HH:MI:SS') || ''',''YYYY-MM-DD HH:MI:SS'')' END || '";
            break;
        }

        return $column;
    }
}
