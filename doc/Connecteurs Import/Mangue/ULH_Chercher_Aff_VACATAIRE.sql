create or replace FUNCTION       ULH_Chercher_Aff_VACATAIRE
(
  vacid INTEGER
)
RETURN CHAR
IS
  -- Action : AFFECTATION
  -- créé par EC juillet 2018 pour OSE (utilisé dans MV_intervenant)
  -- utilisé également dans la vue ULHN_V_LEOCARTE
  CURSOR c1
  IS SELECT 
  --aff.CVAF_HEURES, il peut y avoir des cas où le plus grand nbre d'heures n'est pas sur l'aff principale
  va.d_deb_vacation, str.LC_STRUCTURE
     FROM   MANGUE.VACATAIRES_AFFECTATION aff,
            mangue.vacataires va,
	        grhum.STRUCTURE_ULR str
     WHERE  str.c_structure = aff.c_structure
	 AND    ((va.d_fin_vacation IS NULL) OR (va.d_fin_vacation >= TO_DATE(TO_CHAR(SYSDATE,'dd/mm/YYYY'),'dd/mm/YYYY')))
     --AND    va.d_deb_vacation <= TO_DATE(TO_CHAR(SYSDATE,'dd/mm/YYYY'),'dd/mm/YYYY')
     and    va.VAC_ID =  vacid
     AND    aff.VAC_ID =  vacid
     AND    va.tem_valide= 'O'
     AND    aff.tem_principale = 'O'
	 ORDER BY 
     --aff.CVAF_HEURES DESC, 
     va.d_deb_vacation DESC;
  -- variables
  r_affectation c1%ROWTYPE;
  lAffectation  VARCHAR2(500);
BEGIN
  lAffectation := '';
  -- Action 1
  OPEN c1;
  LOOP
     FETCH c1 INTO r_affectation;
     EXIT WHEN c1%NOTFOUND;
	 lAffectation := lAffectation||r_affectation.lc_structure;
  END LOOP;
  CLOSE c1;
-- cas des vacataires sans affectation : on ne retourne rien
  IF ((lAffectation = '') OR (lAffectation IS NULL))
  THEN lAffectation := '';
  END IF;
  RETURN lAffectation;
END;