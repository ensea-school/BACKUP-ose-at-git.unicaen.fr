CREATE OR REPLACE FORCE VIEW SRC_CHEMIN_PEDAGOGIQUE AS
SELECT
  elp.id                                                               element_pedagogique_id,
  etp.id                                                               etape_id,
  ROW_NUMBER() OVER (PARTITION BY etp.id, fq.annee_id ORDER BY ROWNUM) ordre,
  s.id                                                                 source_id,
  fq.source_code || '_' || fq.annee_id                                 source_code
FROM
            fca.ose_chemin_pedagogique@fcaprod fq
       JOIN source                              s ON s.code = 'FCAManager'
  LEFT JOIN element_pedagogique               elp ON elp.source_code = fq.z_element_pedagogique_id
                                                 AND elp.annee_id = TO_NUMBER(fq.annee_id)
  LEFT JOIN etape                             etp ON etp.source_code = fq.z_etape_id
                                                 AND etp.annee_id = TO_NUMBER(fq.annee_id)