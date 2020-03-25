CREATE OR REPLACE FORCE VIEW V_ELEMENT_TYPE_MODULATEUR AS
SELECT
  ep.id element_pedagogique_id,
  tms.type_modulateur_id type_modulateur_id
FROM
       element_pedagogique        ep
  JOIN structure                   s ON s.id = ep.structure_id
                                    AND s.histo_destruction IS NULL

  JOIN type_modulateur_structure tms ON tms.structure_id = s.id
                                    AND tms.histo_destruction IS NULL
                                    AND ep.annee_id BETWEEN GREATEST(NVL(tms.annee_debut_id,0),ep.annee_id) AND LEAST(NVL(tms.annee_fin_id,9999),ep.annee_id)

UNION

SELECT
  tm_ep.element_pedagogique_id element_pedagogique_id,
  tm_ep.type_modulateur_id type_modulateur_id
FROM
  type_modulateur_ep tm_ep
WHERE
  tm_ep.histo_destruction IS NULL