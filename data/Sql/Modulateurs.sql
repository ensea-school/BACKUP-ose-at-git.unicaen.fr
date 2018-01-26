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
  LEFT JOIN modulateur M ON m.type_modulateur_id = tm.id AND m.histo_destruction IS NULL
WHERE
  tm.histo_destruction is null
ORDER BY
  tm.code, m.code;
  
--UPDATE type_modulateur_structure set structure_id = (SELECT id FROM structure WHERE libelle_court = 'IAE') WHERE type_modulateur_id = (SELECT id FROM type_modulateur WHERE code = 'IAE_FOAD');


update element_modulateur set modulateur_id = 2 where modulateur_id = 6 and histo_destruction is null;
update modulateur set histo_destruction = sysdate, histo_destructeur_id = 2 where id = 6;
update modulateur set ponderation_service_compl = 1.5 where id = 33;

UPDATE "OSE"."MODULATEUR" SET LIBELLE = 'Niveau 3', code = 'IAE_FC_3' WHERE code = 'IAE_FC_IUP_BFA';
UPDATE "OSE"."MODULATEUR" SET LIBELLE = 'Niveau 2', code = 'IAE_FC_2' WHERE code = 'IAE_FC_NM';
UPDATE "OSE"."MODULATEUR" SET LIBELLE = 'Niveau 1', code = 'IAE_FC_1' WHERE code = 'IAE_FC_NL';

select * from type_modulateur;

  
  
INSERT INTO   TYPE_MODULATEUR_STRUCTURE  (
    ID,
    TYPE_MODULATEUR_ID,
    STRUCTURE_ID,
    --HISTO_CREATION,
    HISTO_CREATEUR_ID,
    --HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
    --HISTO_DESTRUCTION,
    --HISTO_DESTRUCTEUR_ID
  )
  VALUES  (
    TYPE_MODULATEUR_STRUCTU_ID_SEQ.nextval,
    (SELECT id FROM type_modulateur WHERE code = 'ESPE_FC'),
    (SELECT id FROM structure WHERE source_code = 'E01'),
    (SELECT id FROM utilisateur WHERE username = 'lecluse'),
    (SELECT id FROM utilisateur WHERE username = 'lecluse')
  );
  
  select  * from structure where libelle_court like '%ESPE%';