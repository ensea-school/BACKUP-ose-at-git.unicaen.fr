create or replace FUNCTION       Trouve_lc_structure_pere(c_structure_fille VARCHAR2)
-- Procedure de recupEration de la structure pere d'un service
RETURN VARCHAR2
IS
  lc_structure_pere structure_ulr.lc_structure%type;
BEGIN
SELECT sp.lc_structure INTO lc_structure_pere
FROM STRUCTURE_ULR s, STRUCTURE_ULR sp
WHERE c_structure_fille = s.c_structure
AND s.c_structure_pere = sp.c_structure ;
   RETURN lc_structure_pere;
END;
 