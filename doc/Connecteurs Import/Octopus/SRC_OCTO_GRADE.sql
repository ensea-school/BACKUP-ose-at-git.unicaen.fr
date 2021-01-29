CREATE
OR REPLACE FORCE VIEW SRC_OCTO_GRADE AS
SELECT g.lib_court libelle_court,
       g.lib_long  libelle_long,
       s.id        source_id,
       g.c_grade   source_code,
       null        echelle,
       null        corps_id
FROM grade@octodev g
         JOIN source s ON s.code = 'Octopus'
