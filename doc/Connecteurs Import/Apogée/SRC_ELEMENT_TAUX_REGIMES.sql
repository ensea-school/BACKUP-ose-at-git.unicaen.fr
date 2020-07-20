CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_TAUX_REGIMES AS
WITH apogee_query AS (
  SELECT
    e.z_element_pedagogique_id  z_element_pedagogique_id,
    to_number(e.annee_id) + 1   annee_id,
    e.effectif_fi               effectif_fi,
    e.effectif_fc               effectif_fc,
    e.effectif_fa               effectif_fa,
    'Apogee'                    z_source_id,
    TO_NUMBER(e.annee_id) + 1 || '-' || e.z_element_pedagogique_id source_code
  FROM
    ose_element_effectifs@apoprod e
  WHERE
    (e.effectif_fi + e.effectif_fc + e.effectif_fa) > 0
)
SELECT
  ep.id           element_pedagogique_id,
  aq.annee_id     annee_id,
  OSE_DIVERS.CALCUL_TAUX_FI( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fi,
  OSE_DIVERS.CALCUL_TAUX_FC( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fc,
  OSE_DIVERS.CALCUL_TAUX_FA( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fa,
  s.id           source_id,
  aq.source_code source_code
FROM
       apogee_query aq
  JOIN source s ON s.code = aq.z_source_id
  JOIN ELEMENT_PEDAGOGIQUE ep ON ep.source_code = aq.z_element_pedagogique_id AND ep.annee_id = aq.annee_id
WHERE
  NOT EXISTS( -- on évite de remonter des données issus d'autres sources pour le pas risquer de les écraser!!
    SELECT * FROM element_taux_regimes aq_tbl WHERE
      aq_tbl.element_pedagogique_id = ep.id
      AND aq_tbl.source_id <> s.id
  )