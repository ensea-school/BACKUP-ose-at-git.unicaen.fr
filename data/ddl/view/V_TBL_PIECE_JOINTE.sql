CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE AS
WITH pjf AS (
  SELECT
    pjf.annee_id,
    pjf.type_piece_jointe_id,
    pjf.intervenant_id,
    COUNT(*) count,
    SUM(CASE WHEN validation_id IS NULL THEN 0 ELSE 1 END) validation,
    SUM(CASE WHEN fichier_id IS NULL THEN 0 ELSE 1 END) fichier
  FROM
    tbl_piece_jointe_fournie pjf
  GROUP BY
    pjf.annee_id,
    pjf.type_piece_jointe_id,
    pjf.intervenant_id
)
SELECT
  COALESCE( pjd.annee_id, pjf.annee_id ) annee_id,
  COALESCE( pjd.type_piece_jointe_id, pjf.type_piece_jointe_id ) type_piece_jointe_id,
  COALESCE( pjd.intervenant_id, pjf.intervenant_id ) intervenant_id,
  CASE WHEN pjd.intervenant_id IS NULL THEN 0 ELSE 1 END demandee,
  CASE WHEN pjf.fichier = pjf.count THEN 1 ELSE 0 END fournie,
  CASE WHEN pjf.validation = pjf.count THEN 1 ELSE 0 END validee,
  COALESCE(pjd.heures_pour_seuil,0) heures_pour_seuil,
  COALESCE(pjd.obligatoire,1) obligatoire
FROM
  tbl_piece_jointe_demande pjd
  FULL JOIN pjf ON pjf.type_piece_jointe_id = pjd.type_piece_jointe_id AND pjf.intervenant_id = pjd.intervenant_id