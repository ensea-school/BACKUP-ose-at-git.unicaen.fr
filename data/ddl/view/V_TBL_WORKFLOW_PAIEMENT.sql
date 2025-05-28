CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_PAIEMENT AS
SELECT
  'demande_mep'                                      etape_code,
  p.intervenant_id                                   intervenant_id,
  p.structure_id                                     structure_id,
  SUM(p.heures_a_payer_aa + p.heures_a_payer_ac)     objectif,
  SUM(p.heures_demandees_aa + p.heures_demandees_ac) partiel,
  SUM(p.heures_demandees_aa + p.heures_demandees_ac) realisation
FROM
  tbl_paiement p
WHERE
  p.heures_a_payer_aa + p.heures_a_payer_ac > 0
  /*@INTERVENANT_ID=p.intervenant_id*/
  /*@ANNEE_ID=p.annee_id*/
GROUP BY
  p.annee_id,
  p.intervenant_id,
  p.structure_id

UNION ALL

SELECT
  'saisie_mep'                                       etape_code,
  p.intervenant_id                                   intervenant_id,
  p.structure_id                                     structure_id,
  SUM(p.heures_demandees_aa + p.heures_demandees_ac) objectif,
  SUM(p.heures_payees_aa + p.heures_payees_ac)       partiel,
  SUM(p.heures_payees_aa + p.heures_payees_ac)       realisation
FROM
  tbl_paiement p
WHERE
  p.heures_demandees_aa + p.heures_demandees_ac > 0
  /*@INTERVENANT_ID=p.intervenant_id*/
  /*@ANNEE_ID=p.annee_id*/
GROUP BY
  p.annee_id,
  p.intervenant_id,
  p.structure_id