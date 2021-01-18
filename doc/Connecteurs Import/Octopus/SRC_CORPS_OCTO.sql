CREATE
OR REPLACE FORCE VIEW SRC_CORPS_OCTO AS
SELECT c.lib_long  libelle_long,
       c.lib_court libelle_court,
       s.id        source_id,
       c.c_corps   source_code
FROM corps@octodev c
         JOIN source s
              ON s.code = 'Octopus'
WHERE SYSDATE BETWEEN COALESCE(c.d_ouverture, SYSDATE) AND COALESCE(c.d_fermeture + 1, SYSDATE)
  AND c.c_corps = '843'
