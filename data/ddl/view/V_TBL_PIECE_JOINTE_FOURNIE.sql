CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE_FOURNIE AS
SELECT
  i.annee_id,
  i.code code_intervenant,
  pj.type_piece_jointe_id,
  pj.intervenant_id,
  pj.id piece_jointe_id,
  v.id validation_id,
  f.id fichier_id,
--  CASE WHEN MIN(COALESCE(tpjs.duree_vie,1)) IS NULL THEN 1 ELSE MIN(COALESCE(tpjs.duree_vie,1)) END duree_vie,
  --CASE WHEN MIN(COALESCE(tpjs.duree_vie,1)) IS NULL THEN i.annee_id+1 ELSE MIN(i.annee_id+COALESCE(tpjs.duree_vie,1)) END date_validite,
  MIN(COALESCE(tpjs.duree_vie,1)) duree_vie,
  MIN(COALESCE(tpjs.annee_fin_id+1, i.annee_id+COALESCE(tpjs.duree_vie,1))) date_validite,

  pj.date_archive date_archive
FROM
            piece_jointe          pj
       JOIN intervenant            i ON i.id = pj.intervenant_id
                                    AND i.histo_destruction IS NULL
       JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
       JOIN fichier                f ON f.id = pjf.fichier_id
                                    AND f.histo_destruction IS NULL
        LEFT JOIN type_piece_jointe_statut tpjs ON tpjs.statut_id = i.statut_id
                                           AND tpjs.type_piece_jointe_id = pj.type_piece_jointe_id
                                           AND i.annee_id BETWEEN COALESCE(tpjs.annee_debut_id,i.annee_id) AND COALESCE(tpjs.annee_fin_id,i.annee_id)
                                           AND tpjs.HISTO_DESTRUCTION IS NULL

 LEFT JOIN validation             v ON v.id = pj.validation_id
                                    AND v.histo_destruction IS NULL
WHERE
  pj.histo_destruction IS NULL
GROUP BY
i.annee_id,
  i.code,
  pj.type_piece_jointe_id,
  pj.intervenant_id,
  pj.id,
  v.id,
  f.id,
  pj.date_archive