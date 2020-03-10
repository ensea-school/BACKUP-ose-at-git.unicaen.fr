CREATE OR REPLACE FORCE VIEW V_CENTRE_COUT_STRUCTURE AS
SELECT
  ccs.centre_cout_id,
  ccs.structure_id
FROM
  centre_cout_structure ccs
WHERE
  ccs.histo_destruction IS NULL