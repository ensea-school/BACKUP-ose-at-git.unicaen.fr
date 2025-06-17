CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_REFERENTIEL_REALISE AS
SELECT
  'referentiel_saisie_realise' etape_code,
  tsd.intervenant_id           intervenant_id,
  ts.structure_id              structure_id,
  1                            objectif,
  COALESCE(SUM(ts.heures),0)   realisation
FROM
            tbl_service_du tsd
       JOIN intervenant      i ON i.id = tsd.intervenant_id
       JOIN statut          si ON si.id = i.statut_id
  LEFT JOIN tbl_referentiel ts ON ts.intervenant_id = tsd.intervenant_id AND ts.type_volume_horaire_code = 'REALISE' AND ts.heures > 0
WHERE
  si.referentiel_prevu = 1
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@STATUT_ID=si.id*/
GROUP BY
  tsd.intervenant_id,
  ts.structure_id,
  i.structure_id