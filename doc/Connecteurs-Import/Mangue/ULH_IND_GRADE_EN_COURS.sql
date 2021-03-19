create or replace FUNCTION ULH_IND_GRADE_EN_COURS (v_num individu.no_individu%TYPE) RETURN grade.c_grade%TYPE AS 
-- EC avril 2018 (pour OSE)
-- retourne le grade 
-- correspondant a l'element de carriere en cours
-- de l'individu passe en parametre
v_grade grade.c_grade%TYPE := '';
CURSOR c_grade IS 
  SELECT distinct(c_grade) 
  FROM mangue.element_carriere 
  JOIN grade USING (c_grade)
  inner join
     (
     select no_dossier_pers, max(d_effet_element) as maxeffet from MANGUE.ELEMENT_CARRIERE
     WHERE  ((d_fin_element IS NULL) OR (d_fin_element >= TO_DATE(TO_CHAR(sysdate,'DD/MM/YYYY'),'DD/MM/YYYY')))
     AND   d_effet_element <= TO_DATE(TO_CHAR(sysdate,'DD/MM/YYYY'),'DD/MM/YYYY')
     AND    tem_valide = 'O'
     group by NO_DOSSIER_PERS
     ) ec2 on mangue.element_carriere.no_dossier_pers = ec2.no_dossier_pers and mangue.element_carriere.d_effet_element=ec2.maxeffet
  WHERE mangue.element_carriere.no_dossier_pers = v_num
;
BEGIN
  OPEN c_grade;
  LOOP
    FETCH c_grade INTO v_grade;
    EXIT WHEN c_grade%NOTFOUND;
  END LOOP;
  CLOSE c_grade;
 RETURN v_grade;  
END ULH_IND_GRADE_EN_COURS;