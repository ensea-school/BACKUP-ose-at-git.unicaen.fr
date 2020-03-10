CREATE OR REPLACE FORCE VIEW V_ETAPE_TYPE_MODULATEUR AS
SELECT DISTINCT
  etm.type_modulateur_id type_modulateur_id,
  ep.etape_id etape_id
FROM
  v_element_type_modulateur etm
  JOIN element_pedagogique ep ON ep.id = etm.element_pedagogique_id AND ep.histo_destruction IS NULL