CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_REFERENTIEL_REALISE AS
SELECT
  'referentiel_saisie_realise' etape_code,
  tsd.intervenant_id           intervenant_id,
  ts.structure_id              structure_id,
  1                            objectif,
  1                            partiel,
  COALESCE(SUM(ts.heures),0)   realisation
FROM
            tbl_service_du tsd
       JOIN intervenant      i ON i.id = tsd.intervenant_id
       JOIN statut          si ON si.id = i.statut_id
  LEFT JOIN tbl_referentiel ts ON ts.intervenant_id = tsd.intervenant_id AND ts.type_volume_horaire_code = 'REALISE' AND ts.heures > 0
WHERE
  si.referentiel_prevu = 1
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/
  /*@statut_id=si.id*/
GROUP BY
  tsd.intervenant_id,
  ts.structure_id,
  i.structure_id