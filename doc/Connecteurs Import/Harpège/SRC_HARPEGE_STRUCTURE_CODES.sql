CREATE OR REPLACE VIEW SRC_HARPEGE_STRUCTURE_CODES AS
SELECT
  s9.c_structure c_structure,
  COALESCE(s4.c_structure, s5.c_structure, s6.c_structure, s7.c_structure, s8.c_structure, s9.c_structure) c_structure_n2
FROM
  structure@harpprod s9
  LEFT JOIN structure@harpprod s8 ON s8.c_structure = s9.c_structure_pere AND s8.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s7 ON s7.c_structure = s8.c_structure_pere AND s7.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s6 ON s6.c_structure = s7.c_structure_pere AND s6.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s5 ON s5.c_structure = s6.c_structure_pere AND s5.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s4 ON s4.c_structure = s5.c_structure_pere AND s4.c_structure <> 'UNIV';