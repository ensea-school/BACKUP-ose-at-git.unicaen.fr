CREATE OR REPLACE FORCE VIEW OSE.SRC_CHEMIN_PEDAGOGIQUE  AS
SELECT
  elp.id                                                               element_pedagogique_id,
  etp.id                                                               etape_id,
  ROW_NUMBER() OVER (PARTITION BY etp.id, aq.annee_id ORDER BY ROWNUM) ordre,
  s.id                                                                 source_id,
  aq.source_code || '_' || aq.annee_id                                 source_code
FROM
            act_chemin_pedagogique aq
       JOIN source                          s ON s.code = 'Actul'
  LEFT JOIN element_pedagogique           elp ON elp.source_code = aq.z_element_pedagogique_id
                                             AND elp.annee_id = TO_NUMBER(aq.annee_id)
  LEFT JOIN etape                         etp ON etp.source_code = aq.z_etape_id
                                             AND etp.annee_id = TO_NUMBER(aq.annee_id)