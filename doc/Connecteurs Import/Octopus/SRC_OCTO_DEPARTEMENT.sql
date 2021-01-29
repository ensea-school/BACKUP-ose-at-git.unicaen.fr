CREATE
OR REPLACE FORCE VIEW SRC_OCTO_DEPARTEMENT AS
SELECT d.code      code,
       d.lib_court libelle,
       s.id        source_id,
       d.code      source_code
FROM departement@octodev d
         JOIN source s
              ON s.code = 'Octopus'