CREATE
OR REPLACE VIEW SRC_HARPEGE_STRUCTURE_CODES AS
SELECT vs.code  c_structure,
       vs2.code c_structuren2
FROM octo.v_structure@octoprod vs
         JOIN octo.v_structure@octoprod vs2 ON vs.niv2_id = vs2.id
WHERE vs.racine_id = (SELECT id FROM octo.v_structure@octoprod WHERE code = 'UNIV')