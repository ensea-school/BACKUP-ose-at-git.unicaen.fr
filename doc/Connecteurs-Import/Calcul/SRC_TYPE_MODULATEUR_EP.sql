CREATE OR REPLACE FORCE VIEW SRC_TYPE_MODULATEUR_EP AS
SELECT
  tm.id                               type_modulateur_id,
  ep.id                               element_pedagogique_id,
  src.id                              source_id,
  tm.code || '_' || ep.source_code || '_' || ep.annee_id  source_code
FROM
  element_pedagogique             ep
  JOIN type_modulateur            tm ON tm.histo_destruction IS NULL
  JOIN structure                   s ON s.id = ep.structure_id
  JOIN type_modulateur_structure tms ON tms.type_modulateur_id = tm.id
                                    AND tms.structure_id = s.id
                                    AND tms.histo_destruction IS NULL
                                    AND ep.annee_id BETWEEN COALESCE( tms.annee_debut_id, 1 ) AND COALESCE( tms.annee_fin_id, 999999 )
  JOIN source                    src ON src.code = 'Calcul'
WHERE
  ep.histo_destruction IS NULL
  AND ep.taux_fc > 0;
