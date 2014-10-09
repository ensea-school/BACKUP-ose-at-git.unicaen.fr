SELECT
  tm.id                       tm_id,
  tm.code                     tm_code,
  tm.libelle                  tm_libelle,
  ose_divers.implode('SELECT libelle_court FROM structure s JOIN type_modulateur_structure tms on tms.structure_id = s.id WHERE tms.type_modulateur_id = ' || to_char(tm.id) ) tm_structures,
  m.id                        m_id,
  m.code                      m_code,
  m.libelle                   m_libelle,
  M.PONDERATION_SERVICE_DU    m_pondaration_service_du,
  M.PONDERATION_SERVICE_COMPL m_PONDERATION_SERVICE_COMPL
 
FROM
  TYPE_MODULATEUR TM
  LEFT JOIN modulateur M ON m.type_modulateur_id = tm.id
WHERE
  tm.histo_destruction is null
  AND m.histo_destruction IS NULL
ORDER BY
  tm.code, m.code;
  
--UPDATE type_modulateur_structure set structure_id = (SELECT id FROM structure WHERE libelle_court = 'IAE') WHERE type_modulateur_id = (SELECT id FROM type_modulateur WHERE code = 'IAE_FOAD');