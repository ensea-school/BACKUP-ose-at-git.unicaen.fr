CREATE
OR REPLACE FORCE VIEW SRC_PAYS_OCTO AS
SELECT code_pays                                                     code,
       libelle_court                                                 libelle,
       coalesce(date_ouverture, TO_DATE('1900/01/01', 'YYYY/MM/DD')) validite_debut,
       date_fermeture                                                validite_fin,
       s.id                                                          source_id,
       code_pays                                                     source_code
FROM pays@octodev p
         JOIN source s ON s.code = 'Octopus';