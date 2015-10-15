  
  /*
INSERT INTO CATEGORIE_PRIVILEGE (
  ID,
  CODE,
  LIBELLE
) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'discipline',
  'Gestion des disciplines'
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

--      SELECT 'droit' c, 'role-visualisation' p, 'Rôles - Visualisation' l FROM dual
--UNION SELECT 'droit' c, 'role-edition' p, 'Rôles - Édition' l FROM dual
      SELECT 'discipline' c, 'gestion' p, 'Gestion' l FROM dual
UNION SELECT 'discipline' c, 'visualisation' p, 'Visualisation' l FROM dual
UNION SELECT 'discipline' c, 'edition' p, 'Édition' l FROM dual

) t1;*/

select
  /*/
  p.id, cp.code categorie, p.code privilege, p.ordre ordre /*/
  '    const '
    || rpad( upper( replace( cp.code, '-', '_' ) || '_' || replace( p.code, '-', '_' ) ), MAX( length( cp.code ) + length( p.code ) ) OVER (PARTITION BY 1 )+1, ' ' )
    || ' = ' || '''' || cp.code || '-' || p.code || '''' || ';' php_const /**/
from
  privilege p
  join categorie_privilege cp on cp.id = p.categorie_id
order by
  cp.code, p.ordre;
  






--UPDATE CATEGORIE_PRIVILEGE SET CODE = 'droit' WHERE CODE = 'privilege';
--update privilege set code = 'role-visualisation' where code = 'visualisation' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
--update privilege set code = 'role-edition' where code = 'edition' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
