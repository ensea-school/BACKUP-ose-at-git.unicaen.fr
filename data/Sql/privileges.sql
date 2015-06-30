  
  
INSERT INTO CATEGORIE_PRIVILEGE (
  ID,
  CODE,
  LIBELLE
) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'droit',
  'Gestion des droits d''accès'
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
SELECT 'droit' c, 'privilege-visualisation' p, 'Privilèges - Visualisation' l FROM dual
UNION SELECT 'droit' c, 'privilege-edition' p, 'Privilèges - Édition' l FROM dual
UNION SELECT 'droit' c, 'affectation-visualisation' p, 'Affectations - Visualisation' l FROM dual
UNION SELECT 'droit' c, 'affectation-edition' p, 'Affectations - Édition' l FROM dual

) t1;

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
  



update privilege set ordre = 3 where id = 37;
update privilege set ordre = 4 where id = 36;
update privilege set ordre = 5 where id = 35;
update privilege set ordre = 6 where id = 34;



UPDATE CATEGORIE_PRIVILEGE SET CODE = 'droit' WHERE CODE = 'privilege';
update privilege set code = 'role-visualisation' where code = 'visualisation' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
update privilege set code = 'role-edition' where code = 'edition' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
