CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_ENSEIGNEMENT_REALISE AS
SELECT
  'enseignement_saisie_realise'                                         etape_code,
  tsd.intervenant_id                                                    intervenant_id,
  COALESCE(ts.structure_id,i.structure_id)                              structure_id,
  AVG(GREATEST(CASE WHEN si.service_prevu = 1 THEN tsd.service_statutaire ELSE 1 END, 1)) objectif,
  AVG(GREATEST(CASE WHEN si.service_prevu = 1 THEN tsd.service_statutaire ELSE 1 END, 1)) partiel,
  SUM(ts.heures)                                                        realisation
FROM
            tbl_service_du tsd
       JOIN intervenant      i ON i.id = tsd.intervenant_id
       JOIN statut          si ON si.id = i.statut_id
  LEFT JOIN tbl_service     ts ON ts.intervenant_id = tsd.intervenant_id AND ts.type_volume_horaire_code = 'REALISE' AND ts.heures > 0
WHERE
  si.service_realise = 1
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/
  /*@statut_id=si.id*/
GROUP BY
  tsd.intervenant_id,
  ts.structure_id,
  i.structure_id