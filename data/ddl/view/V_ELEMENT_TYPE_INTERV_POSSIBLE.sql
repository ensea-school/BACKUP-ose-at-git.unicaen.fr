CREATE OR REPLACE FORCE VIEW V_ELEMENT_TYPE_INTERV_POSSIBLE AS
SELECT
  ti.id       type_intervention_id,
  ep.id       element_pedagogique_id
FROM
            element_pedagogique              ep

       JOIN type_intervention                ti ON ep.annee_id BETWEEN COALESCE(ti.annee_debut_id,ep.annee_id) AND COALESCE(ti.annee_fin_id, ep.annee_id)
                                               AND ti.histo_destruction IS NULL

  LEFT JOIN type_intervention_structure     tis ON tis.type_intervention_id = ti.id
                                               AND tis.structure_id = ep.structure_id
                                               AND ep.annee_id BETWEEN COALESCE(tis.annee_debut_id,ep.annee_id) AND COALESCE(tis.annee_fin_id, ep.annee_id)
                                               AND tis.histo_destruction IS NULL
WHERE
  ep.histo_destruction IS NULL
  AND COALESCE( tis.visible, ti.visible ) = 1
  AND (ti.regle_foad = 0 OR ep.taux_foad > 0)
  AND (ti.regle_fc = 0 OR ep.taux_fc > 0)