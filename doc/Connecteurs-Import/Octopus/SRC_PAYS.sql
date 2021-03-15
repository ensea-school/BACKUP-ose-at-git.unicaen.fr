CREATE
OR REPLACE FORCE VIEW SRC_PAYS AS
SELECT code_pays                                                     code,
       libelle_long                                                  libelle,
       coalesce(date_ouverture, TO_DATE('1900/01/01', 'YYYY/MM/DD')) validite_debut,
       date_fermeture                                                validite_fin,
       0                                                             temoin_ue,
       s.id                                                          source_id,
       code_pays                                                     source_code
FROM octo.pays@octoprod p
         JOIN source s ON s.code = 'Octopus'
--Filtre sur les codes pays inutiles
WHERE p.code_pays NOT IN ('19A', '19B', '$', '#', '999')