CREATE
OR REPLACE FORCE VIEW SRC_UO AS
SELECT
sm.valeur uo,
s2.libelle_court || ' (' || sm.valeur || ')' libelle_court_uo,
s2.libelle_long || ' (' || sm.valeur || ')' libelle_long_uo
FROM v_structure@octoprod s1
JOIN v_structure@octoprod s2 ON s1.niv2_id = s2.id
JOIN structure_mapping@octoprod sm ON sm.structure_id = s1.id AND sm.source_id = 'SIHAM'
WHERE s1.type_id IN (2,3)
ORDER BY s2.libelle_court, valeur ASC