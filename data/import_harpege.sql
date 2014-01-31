INSERT INTO civilite (id, LIBELLE, SEXE) 
  SELECT civilite_id_seq.nextval, C_CIVILITE, L_CIVILITE, SEXE FROM civilite@harpprod;
  
  
  
  
  SELECT *  FROM civilite@harpprod;
  describe civilite@harpprod;
  
  SELECT SYS_GUID() FROM dual;
  