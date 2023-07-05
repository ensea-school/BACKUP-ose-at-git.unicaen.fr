CREATE
OR REPLACE FORCE VIEW SRC_CORPS AS
SELECT c.lib_long  libelle_long,
       c.lib_court libelle_court,
       s.id        source_id,
       c.c_corps   source_code
FROM corps@octoprod c
         JOIN source s
              ON s.code = 'Octopus'
WHERE SYSDATE BETWEEN COALESCE(c.d_ouverture, SYSDATE) AND COALESCE(c.d_fermeture + 1, SYSDATE) and c.d_fermeture IS NOT NULL and c.histo_destruction IS NULL