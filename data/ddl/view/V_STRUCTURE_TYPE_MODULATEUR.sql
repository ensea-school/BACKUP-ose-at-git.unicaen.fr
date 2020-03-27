CREATE OR REPLACE FORCE VIEW V_STRUCTURE_TYPE_MODULATEUR AS
SELECT DISTINCT
  etm.type_modulateur_id type_modulateur_id,
  ep.structure_id structure_id
FROM
  v_element_type_modulateur etm
  JOIN element_pedagogique ep ON ep.id = etm.element_pedagogique_id AND ep.histo_destruction IS NULL