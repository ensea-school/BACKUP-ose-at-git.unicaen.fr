CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_MISSION_PRIME AS
SELECT
  'mission_prime'                                     etape_code,
  mp.intervenant_id                                   intervenant_id,
  mp.structure_id                                     structure_id,
  SUM(mp.prime)                                       objectif,
  SUM(mp.validation+mp.refus)                         partiel,
  SUM(mp.validation+mp.refus)                         realisation
FROM
  tbl_mission_prime mp
WHERE
  mp.actif = 1
  /*@INTERVENANT_ID=mp.intervenant_id*/
  /*@ANNEE_ID=mp.annee_id*/
GROUP BY
  mp.intervenant_id,
  mp.structure_id