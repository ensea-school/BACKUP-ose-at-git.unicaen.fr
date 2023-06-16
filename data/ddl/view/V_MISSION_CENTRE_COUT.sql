CREATE OR REPLACE FORCE VIEW V_MISSION_CENTRE_COUT AS
SELECT
  m.id mission_id,
  cc.id centre_cout_id
FROM
  mission m
  JOIN centre_cout            cc ON cc.histo_destruction IS NULL

  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id
                                AND ccs.structure_id = m.structure_id
                                AND ccs.histo_destruction IS NULL

  JOIN cc_activite             a ON a.id = cc.activite_id
                                AND a.histo_destruction IS NULL
                                AND a.mission = 1

  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id
                                AND tr.histo_destruction IS NULL
                                AND tr.mission = 1
WHERE
  m.histo_destruction IS NULL