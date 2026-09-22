CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_ENSEIGNEMENT_REALISE AS
SELECT
  'enseignement_saisie_realise'                                         etape_code,
  tsd.intervenant_id                                                    intervenant_id,
  ts.structure_id                                                       structure_id,
  AVG(GREATEST(CASE WHEN si.service_prevu = 1 THEN tsd.service_statutaire ELSE 1 END, 1)) objectif,
  AVG(GREATEST(CASE WHEN si.service_prevu = 1 THEN tsd.service_statutaire ELSE 1 END, 1)) partiel,
  SUM(ts.heures)                                                        realisation
FROM
            tbl_service_du tsd
       JOIN intervenant      i ON i.id = tsd.intervenant_id
       JOIN statut          si ON si.id = i.statut_id
  LEFT JOIN tbl_service     ts ON ts.intervenant_id = tsd.intervenant_id
                              AND ts.type_volume_horaire_code = 'REALISE'
                              AND ts.heures > 0
WHERE
  si.service_realise = 1
  AND ts.id IS NOT NULL
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/
  /*@statut_id=si.id*/
GROUP BY
  tsd.intervenant_id,
  ts.structure_id,
  i.structure_id

UNION ALL

SELECT
  'enseignement_saisie_realise'                                         etape_code,
  tsd.intervenant_id                                                    intervenant_id,
  sp.structure_id                                                       structure_id,
  AVG(GREATEST(tsd.service_statutaire, 1))                              objectif,
  AVG(GREATEST(tsd.service_statutaire, 1))                              partiel,
  0                                                                     realisation
FROM
            tbl_service_du tsd
       JOIN intervenant      i ON i.id = tsd.intervenant_id
       JOIN statut          si ON si.id = i.statut_id
       JOIN tbl_service     sp ON sp.intervenant_id = tsd.intervenant_id
                              AND sp.type_volume_horaire_code = 'PREVU'
                              AND sp.heures > 0
       JOIN tbl_contrat     tc ON tc.intervenant_id = sp.intervenant_id
                              AND tc.structure_id = sp.structure_id
                              AND tc.volume_horaire_index = 0
                              AND tc.actif = 1
WHERE
  si.service_realise = 1
  AND si.service_prevu = 1
  AND NOT EXISTS (
    SELECT 1
    FROM tbl_service tr
    WHERE tr.intervenant_id = sp.intervenant_id
      AND tr.structure_id = sp.structure_id
      AND tr.type_volume_horaire_code = 'REALISE'
      AND tr.heures > 0
  )
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/
  /*@statut_id=si.id*/
GROUP BY
  tsd.intervenant_id,
  sp.structure_id

UNION ALL

SELECT
  'enseignement_saisie_realise'                                         etape_code,
  tsd.intervenant_id                                                    intervenant_id,
  tc.structure_id                                                       structure_id,
  1                                                                     objectif,
  1                                                                     partiel,
  0                                                                     realisation
FROM
            tbl_service_du tsd
       JOIN intervenant      i ON i.id = tsd.intervenant_id
       JOIN statut          si ON si.id = i.statut_id
       JOIN tbl_contrat     tc ON tc.intervenant_id = tsd.intervenant_id
                              AND tc.volume_horaire_index = 0
                              AND tc.actif = 1
WHERE
  si.service_realise = 1
  AND si.service_prevu = 0
  AND NOT EXISTS (
    SELECT 1
    FROM tbl_service tr
    WHERE tr.intervenant_id = tc.intervenant_id
      AND tr.structure_id = tc.structure_id
      AND tr.type_volume_horaire_code = 'REALISE'
      AND tr.heures > 0
  )
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/
  /*@statut_id=si.id*/
GROUP BY
  tsd.intervenant_id,
  tc.structure_id
