CREATE
OR REPLACE VIEW SRC_HARPEGE_STRUCTURE_CODES AS
SELECT vs.code  c_structure,
       vs2.code c_structuren2
FROM v_structure@ vs
         JOIN v_structure vs2 ON vs.niv2_id = vs2.id
WHERE vs.racine_id = (SELECT id FROM v_structure WHERE code = 'UNIV')