CREATE OR REPLACE FORCE VIEW V_INDICATEUR_P_REFERENTIEL AS
SELECT
  p.numero*100 + tp.type_volume_horaire_id * 10 numero,
  tp.intervenant_id,
  COALESCE(srs.structure_id, i.structure_id) structure_id,
  pe.libelle etat,
  fr.libelle_court fonction,
  tp.heures,
  tp.plafond,
  tp.derogation
FROM
  tbl_plafond_referentiel tp
  JOIN plafond p ON p.id = tp.plafond_id
  JOIN plafond_etat pe ON pe.id = tp.plafond_etat_id
  JOIN intervenant i ON i.id = tp.intervenant_id
  JOIN fonction_referentiel fr ON fr.id = tp.fonction_referentiel_id
  LEFT JOIN (SELECT DISTINCT intervenant_id, fonction_id, structure_id FROM service_referentiel) srs ON srs.intervenant_id = tp.intervenant_id AND srs.fonction_id = tp.fonction_referentiel_id
WHERE
  pe.code <> 'desactive'
  AND tp.depassement = 1