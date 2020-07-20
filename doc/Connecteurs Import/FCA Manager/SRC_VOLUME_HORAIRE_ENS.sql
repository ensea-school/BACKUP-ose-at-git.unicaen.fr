CREATE OR REPLACE FORCE VIEW SRC_VOLUME_HORAIRE_ENS AS
WITH apogee_fca_query AS (
  SELECT
    vhe.z_element_pedagogique_id            z_element_pedagogique_id,
    vhe.z_type_intervention_id              z_type_intervention_id,
    vhe.heures                              heures,
    1                                       groupes,
    'FCAManager'                            z_source_id,
    TO_CHAR(vhe.source_code)                source_code,
    TO_NUMBER(vhe.annee_id)                 annee_id
  FROM
    fca.ose_volume_horaire_ens@fcaprod vhe
)
SELECT
  ep.id           element_pedagogique_id,
  ti.id           type_intervention_id,
  afq.heures      heures,
  afq.groupes     groupes,
  s.id            source_id,
  afq.source_code source_code
FROM
            apogee_fca_query   afq
       JOIN source               s ON s.code         = afq.z_source_id
  LEFT JOIN element_pedagogique ep ON ep.source_code = afq.z_element_pedagogique_id
                                  AND ep.annee_id    = afq.annee_id
  LEFT JOIN type_intervention   ti ON ti.code        = afq.z_type_intervention_id