CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE AS
WITH t AS (
  SELECT
    pjd.annee_id                                                annee_id,
    pjd.type_piece_jointe_id                                    type_piece_jointe_id,
    MAX(pjf.piece_jointe_id)                                    piece_jointe_id,
    pjd.intervenant_id                                          intervenant_id,
    CASE WHEN pjd.intervenant_id IS NULL THEN 0 ELSE 1 END      demandee,
    SUM(CASE WHEN pjf.id IS NOT NULL THEN 1 ELSE 0 END)             fournie,
    MAX(pjf.validation_id) KEEP(DENSE_RANK FIRST ORDER BY pjf.annee_id DESC) validee,
    COALESCE(pjd.heures_pour_seuil,0)                           heures_pour_seuil,
    COALESCE(pjd.obligatoire,1)                                 obligatoire
  FROM
              tbl_piece_jointe_demande  pjd
    LEFT JOIN tbl_piece_jointe_fournie  pjf ON pjf.code_intervenant = pjd.code_intervenant
                                           AND pjf.type_piece_jointe_id = pjd.type_piece_jointe_id
                                           AND pjd.annee_id BETWEEN pjf.annee_id AND COALESCE(pjf.date_archive - 1,(pjf.annee_id + pjd.duree_vie-1))
  WHERE
    1=1
    /*@intervenant_id=pjd.intervenant_id*/
    /*@annee_id=pjd.annee_id*/
  GROUP BY
    pjd.annee_id, pjd.type_piece_jointe_id, pjd.intervenant_id, pjd.intervenant_id, pjd.heures_pour_seuil, pjd.obligatoire

  UNION ALL

  SELECT
    pjf.annee_id                                                annee_id,
    pjf.type_piece_jointe_id                                    type_piece_jointe_id,
    MAX(pjf.piece_jointe_id)                                    piece_jointe_id,
    pjf.intervenant_id                                          intervenant_id,
    0                                                           demandee,
    1                                                           fournie,
    MAX(pjf.validation_id) KEEP(DENSE_RANK FIRST ORDER BY pjf.annee_id DESC) validee,
    0                                                           heures_pour_seuil,
    0                                                           obligatoire
  FROM
              tbl_piece_jointe_fournie pjf
    LEFT JOIN tbl_piece_jointe_demande pjd ON pjd.intervenant_id = pjf.intervenant_id
                                          AND pjd.type_piece_jointe_id = pjf.type_piece_jointe_id
  WHERE
    pjd.id IS NULL
    /*@intervenant_id=pjf.intervenant_id*/
    /*@annee_id=pjf.annee_id*/
  GROUP BY
    pjf.annee_id, pjf.type_piece_jointe_id, pjf.intervenant_id
)
SELECT annee_id,
       type_piece_jointe_id,
       piece_jointe_id,
       intervenant_id,
       demandee,
       CASE WHEN fournie <> 0 THEN 1 ELSE 0 END    fournie,
       CASE WHEN validee IS NULL THEN 0 ELSE 1 END validee,
       heures_pour_seuil,
       obligatoire
FROM t