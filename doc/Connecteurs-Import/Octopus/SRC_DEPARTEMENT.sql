CREATE
OR REPLACE FORCE VIEW SRC_DEPARTEMENT AS
SELECT LPAD(d.code, 3, 0) code,
       d.lib_court        libelle,
       s.id               source_id,
       LPAD(d.code, 3, 0) source_code
FROM octo.departement@octoprod d
         JOIN source s ON s.code = 'Octopus'
-- Tout sauf les deux anciens code département de mayotte (remplacé par le code 976)
WHERE d.code NOT IN ('970', '985')

