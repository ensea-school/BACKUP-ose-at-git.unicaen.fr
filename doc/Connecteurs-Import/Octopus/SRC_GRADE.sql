CREATE
OR REPLACE FORCE VIEW SRC_GRADE AS
 SELECT
    g.lib_court libelle_court,
    g.lib_long  libelle_long,
    s.id        source_id,
    g.c_grade   source_code
FROM octo.grade@octoprod g
JOIN octo.grade_map@octoprod gm ON gm.grade_id = g.id AND gm.c_source = 'SIHAM'
JOIN source s ON s.code = 'Octopus'
WHERE SYSDATE BETWEEN COALESCE(g.d_ouverture, SYSDATE) AND COALESCE(g.d_fermeture + 1, SYSDATE) AND histo_destruction IS NULL
