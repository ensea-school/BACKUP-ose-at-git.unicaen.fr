CREATE
OR REPLACE VIEW SRC_STRUCTURE AS
--On recherche d'abord tout ce qui est rattaché au parent 'UNIV'
SELECT s.code          code,
       s.libelle_court libelle_court,
       s.libelle_long  libelle_long,
       src.id          source_id,
       s.code          source_code
FROM structure@octoprod s
         JOIN structure_parent@octoprod sp
              ON s.id = sp.structure_id AND sp.parent_id = (SELECT id FROM structure@octodev WHERE code = 'UNIV')
         JOIN source src
              ON src.code = 'Octopus'
         LEFT JOIN octo.structure_adresse@octoprod sa ON sa.structure_id = s.id
WHERE sysdate BETWEEN s.date_ouverture AND COALESCE(s.date_fermeture, sysdate + 1)
UNION ALL
-- On ajoute l'ensemble des laboratoires de recherches
SELECT s.code          code,
       s.libelle_court libelle_court,
       s.libelle_long  libelle_long,
       src.id          source_id,
       s.code          source_code
FROM structure@octoprod s
         JOIN structure_parent@octoprod sp
              ON s.id = sp.structure_id
         JOIN source src
              ON src.code = 'Octopus'
         LEFT JOIN octo.structure_adresse@octoprod sa ON sa.structure_id = s.id
         LEFT JOIN octo.structure_type@octoprod st ON st.id = s.type_id
WHERE sysdate BETWEEN s.date_ouverture AND COALESCE(s.date_fermeture, sysdate + 1)
  AND st.code = 'SREC'

