select q'[o'connor]' from dual;

SELECT TRIM(TO_CHAR(100, '9999999.99')) FROM DUAL;


select 
annee_id, annee_debut_id, annee_fin_id, res res_attendu,
case when


annee_id BETWEEN GREATEST(NVL(annee_debut_id,0),annee_id) AND LEAST(NVL(annee_fin_id,9999),annee_id)


then 1 else 0 end res_calcule from (

          SELECT 2014 annee_id, null annee_debut_id, null annee_fin_id, 1 res FROM dual

UNION ALL SELECT 2014 annee_id, 2014 annee_debut_id, 2014 annee_fin_id, 1 res FROM dual
UNION ALL SELECT 2014 annee_id, null annee_debut_id, 2014 annee_fin_id, 1 res FROM dual
UNION ALL SELECT 2014 annee_id, 2014 annee_debut_id, null annee_fin_id, 1 res FROM dual

UNION ALL SELECT 2014 annee_id, 2012 annee_debut_id, 2015 annee_fin_id, 1 res FROM dual
UNION ALL SELECT 2014 annee_id, null annee_debut_id, 2015 annee_fin_id, 1 res FROM dual
UNION ALL SELECT 2014 annee_id, 2012 annee_debut_id, null annee_fin_id, 1 res FROM dual

UNION ALL SELECT 2014 annee_id, 2015 annee_debut_id, 2017 annee_fin_id, 0 res FROM dual
UNION ALL SELECT 2014 annee_id, 2015 annee_debut_id, null annee_fin_id, 0 res FROM dual

UNION ALL SELECT 2014 annee_id, 2011 annee_debut_id, 2013 annee_fin_id, 0 res FROM dual
UNION ALL SELECT 2014 annee_id, null annee_debut_id, 2013 annee_fin_id, 0 res FROM dual
          
) t1;

select
  to_date('31/01/2015', 'dd/mm/YYYY')  + 1
  from dual;

select
  to_char( sysdate, 'dd/mm/YYYY' )
  from dual;