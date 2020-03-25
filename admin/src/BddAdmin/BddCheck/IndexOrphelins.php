<?php

namespace BddAdmin\BddCheck;

class IndexOrphelins extends BddCheckRule
{
    public $nullable;

    public $consNormName;

    public $constraintName;

    public $status;

    public $cols;



    public function sql()
    {
        return "
SELECT 
  I.TABLE_NAME,
  I.INDEX_NAME,
  C.CONSTRAINT_NAME
FROM
  USER_INDEXES I
  LEFT JOIN USER_CONSTRAINTS C ON C.CONSTRAINT_NAME = I.INDEX_NAME
WHERE
  C.CONSTRAINT_NAME IS NULL
  AND I.INDEX_TYPE = 'NORMAL'
  AND I.INDEX_NAME NOT IN (
    'CC_ACTIVITE_FI', 'CC_ACTIVITE_FA', 'CC_ACTIVITE_FC', 'CC_ACTIVITE_REF',
    'TYPE_RESSOURCE_FI', 'TYPE_RESSOURCE_FA', 'TYPE_RESSOURCE_FC', 'TYPE_RESSOURCE_REF',
    'INTERVENANT_NOM_PATRONYMIQUE', 'INTERVENANT_NOM_USUEL', 'INTERVENANT_PRENOM', 'INTERVENANT_RECHERCHE',
    'INTERVENANT_CODE', 'FORMULE_RESULTAT_TYPE_INT', 'TBL_NOEUD_ANNEE_IDX', 'TBL_NOEUD_ETAPE_IDX', 'TBL_NOEUD_GTF_IDX',
    'TBL_NOEUD_NETAPE_IDX', 'TBL_NOEUD_NOEUD_IDX', 'TBL_NOEUD_STRUCTURE_IDX', 'TBL_LIEN_ACTIF'
  )
ORDER BY
  I.INDEX_NAME
        ";
    }



    public function check()
    {
        $this->error("Index $this->indexName Orphelin");
    }

}