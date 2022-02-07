CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE_SAISIE AS
SELECT
  i.annee_id,
  i.id intervenant_id,
  si.service,
  si.referentiel,
  SUM( CASE WHEN tvhs.code = 'PREVU'   THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_prev,
  SUM( CASE WHEN tvhrs.code = 'PREVU'   THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_prev,
  SUM( CASE WHEN tvhs.code = 'REALISE' THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_real,
  SUM( CASE WHEN tvhrs.code = 'REALISE' THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_real
FROM
  intervenant i
  JOIN statut si ON si.id = i.statut_id
  LEFT JOIN service s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
  LEFT JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
  LEFT JOIN type_volume_horaire tvhs ON tvhs.id = vh.type_volume_horaire_id

  LEFT JOIN service_referentiel sr ON sr.intervenant_id = i.id AND sr.histo_destruction IS NULL
  LEFT JOIN volume_horaire_ref vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
  LEFT JOIN type_volume_horaire tvhrs ON tvhrs.id = vhr.type_volume_horaire_id
WHERE
  i.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
GROUP BY
  i.annee_id,
  i.id,
  si.service,
  si.referentiel