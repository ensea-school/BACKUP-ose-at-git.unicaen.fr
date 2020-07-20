CREATE OR REPLACE FORCE VIEW SRC_TYPE_INTERVENTION_EP AS
WITH t AS (
SELECT
  ti.id                                                   type_intervention_id,
  ti.code                                                 type_intervention_code,
  ep.id                                                   element_pedagogique_id,
  ep.annee_id                                             annee_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id  source_code,
  COALESCE(vhe.heures,0)                                  heures,
  SUM(COALESCE(vhe.heures,0)) OVER (PARTITION BY ep.id)   total_heures,
  ti.regle_foad                                           regle_foad
FROM
            element_pedagogique              ep

       JOIN type_intervention                ti ON ep.annee_id BETWEEN COALESCE(ti.annee_debut_id,ep.annee_id) AND COALESCE(ti.annee_fin_id, ep.annee_id)
                                               AND ti.histo_destruction IS NULL

  LEFT JOIN type_intervention_structure     tis ON tis.type_intervention_id = ti.id
                                               AND tis.structure_id = ep.structure_id
                                               AND ep.annee_id BETWEEN COALESCE(tis.annee_debut_id,ep.annee_id) AND COALESCE(tis.annee_fin_id, ep.annee_id)
                                               AND tis.histo_destruction IS NULL

  LEFT JOIN volume_horaire_ens              vhe ON vhe.element_pedagogique_id = ep.id
                                               AND vhe.type_intervention_id = COALESCE(ti.type_intervention_maquette_id, ti.id)
                                               AND vhe.histo_destruction IS NULL
WHERE
  ep.histo_destruction IS NULL
  AND COALESCE( tis.visible, ti.visible ) = 1
  AND (ti.regle_foad = 0 OR ep.taux_foad > 0)
  AND (ti.regle_fc = 0 OR ep.taux_fc > 0)
)
SELECT
  t.type_intervention_id    type_intervention_id,
  t.element_pedagogique_id  element_pedagogique_id,
  t.source_code             source_code,
  src.id                    source_id
FROM
  t
  JOIN source src ON src.code = 'Calcul'
WHERE
  heures > 0  --Soit il y a des heures de prévues
  OR (total_heures = 0 AND annee_id < 2019) -- soit on autorise tout s'il n'y a pas de charges (avant 2019)
  --OR regle_foad = 1 -- soit ce sont des types d'intervention spécifiques FOAD pour lesquels pas de charge dans Apogée
  OR annee_id < 2017 -- soit on autorise vraiment tout (avant 2017)
;
