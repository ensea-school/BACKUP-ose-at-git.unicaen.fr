CREATE
OR REPLACE VIEW SRC_OCTO_STRUCTURE AS
SELECT s.code                                                        code,
       s.libelle_court                                               libelle_court,
       s.libelle_long                                                libelle_long,
       trim(sa.adresse1 || ' ' || sa.adresse2 || ' ' || sa.adresse3) adresse_precisions,
       null                                                          adresse_numero,
       null                                                          adresse_numero_compl_id,
       null                                                          adresse_voirie_id,
       null                                                          adresse_voie,
       null                                                          adresse_lieu_dit,
       sa.code_postal                                                adresse_code_postal,
       sa.ville_nom                                                  adresse_commune,
       sa.pays_id                                                    z_adresse_pays_id,
       src.id                                                        source_id,
       s.code                                                        source_code
FROM structure@octodev s
         JOIN structure_parent@octodev sp
              ON s.id = sp.structure_id AND sp.parent_id = (SELECT id FROM structure@octodev WHERE code = 'UNIV')
         JOIN source src
              ON src.code = 'Octopus'
         LEFT JOIN octo.structure_adresse@octoprod sa ON sa.structure_id = s.id
WHERE SYSDATE BETWEEN s.date_ouverture AND COALESCE(s.date_fermeture, SYSDATE + 1)

