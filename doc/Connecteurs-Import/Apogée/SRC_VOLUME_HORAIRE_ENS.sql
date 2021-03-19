CREATE OR REPLACE FORCE VIEW SRC_VOLUME_HORAIRE_ENS AS
WITH apogee_query AS (
  SELECT
    vhe.z_element_pedagogique_id            z_element_pedagogique_id,
    CASE vhe.z_type_intervention_id
      WHEN 'MEMOIR' THEN 'Mémoire'
      WHEN 'STAGE'  THEN 'Stage'
      WHEN 'PROJET' THEN 'Projet'
      WHEN 'SORTIE' THEN 'Terrain'
    ELSE
      vhe.z_type_intervention_id
    END                                     z_type_intervention_id,
    vhe.heures                              heures,
    vhe.groupes                             groupes,
    'Apogee'                                z_source_id,
    vhe.annee_id || '_' || vhe.source_code  source_code,
    TO_NUMBER(vhe.annee_id)                 annee_id
  FROM
    ose_volume_horaire_ens@apoprod vhe
)
SELECT
  ep.id           element_pedagogique_id,
  ti.id           type_intervention_id,
  afq.heures      heures,
  afq.groupes     groupes,
  s.id            source_id,
  afq.source_code source_code
FROM
            apogee_query   afq
       JOIN source               s ON s.code         = afq.z_source_id
  LEFT JOIN element_pedagogique ep ON ep.source_code = afq.z_element_pedagogique_id
                                  AND ep.annee_id    = afq.annee_id
  LEFT JOIN type_intervention   ti ON ti.code        = afq.z_type_intervention_id