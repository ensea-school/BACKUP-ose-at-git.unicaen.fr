  
  
INSERT INTO CATEGORIE_PRIVILEGE (
  ID,
  CODE,
  LIBELLE
) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'enseignement',
  'Enseignement'
);

INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE,
  ORDRE
)
SELECT 
  privilege_id_seq.nextval id,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c ) CATEGORIE_ID,
  t1.p CODE,
  t1.l LIBELLE,
  (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c )) + rownum ORDRE
FROM (

      SELECT 'intervenant' c, 'calcul-hetd' p, 'Calcul HETD' l FROM dual
--UNION SELECT 'enseignement' c, 'export-csv'    p, 'Export CSV' l FROM dual

) t1;

select
  /*/
  p.id, cp.code categorie, p.code, p.ordre privilege /*/
  '    const '
    || rpad( upper( replace( cp.code, '-', '_' ) || '_' || replace( p.code, '-', '_' ) ), MAX( length( cp.code ) + length( p.code ) ) OVER (PARTITION BY 1 )+1, ' ' )
    || ' = ' || '''' || cp.code || '-' || p.code || '''' || ';' php_const /**/
from
  privilege p
  join categorie_privilege cp on cp.id = p.categorie_id
order by
  cp.code, p.ordre;
  



update privilege set ordre = 1 where id = 6;
update privilege set ordre = 2 where id = 5;
update privilege set ordre = 3 where id = 10;

