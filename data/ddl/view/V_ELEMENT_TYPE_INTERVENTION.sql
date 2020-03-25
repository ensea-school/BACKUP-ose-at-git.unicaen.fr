CREATE OR REPLACE FORCE VIEW V_ELEMENT_TYPE_INTERVENTION AS
SELECT
  type_intervention_id,
  element_pedagogique_id
FROM
  type_intervention_ep tie
  JOIN type_intervention ti ON ti.id = tie.type_intervention_id
WHERE
  tie.histo_destruction IS NULL
ORDER BY
  ti.ordre