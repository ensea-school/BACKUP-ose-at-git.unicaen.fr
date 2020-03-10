CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE_FOURNIE AS
SELECT
  i.annee_id,
  pj.type_piece_jointe_id,
  pj.intervenant_id,
  pj.id piece_jointe_id,
  v.id validation_id,
  f.id fichier_id
FROM
            piece_jointe          pj
       JOIN intervenant            i ON i.id = pj.intervenant_id
                                    AND i.histo_destruction IS NULL

       JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
       JOIN fichier                f ON f.id = pjf.fichier_id
                                    AND f.histo_destruction IS NULL

  LEFT JOIN validation             v ON v.id = pj.validation_id
                                    AND v.histo_destruction IS NULL
WHERE
  pj.histo_destruction IS NULL