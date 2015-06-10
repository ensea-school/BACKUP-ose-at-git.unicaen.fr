  
  
INSERT INTO CATEGORIE_PRIVILEGE (
  ID,
  CODE,
  LIBELLE
) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'import',
  'Import'
);

INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE,
  ORDRE
) VALUES (
  privilege_id_seq.nextval,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = 'import' ),
  'fiche',
  'Visualisation de la fiche',
  1
);


select
  p.id, cp.code categorie, p.code privilege
  --'    const '
  --  || rpad( upper( replace( cp.code, '-', '_' ) || '_' || replace( p.code, '-', '_' ) ), MAX( length( cp.code ) + length( p.code ) ) OVER (PARTITION BY 1 )+1, ' ' )
  --  || ' = ' || '''' || cp.code || '-' || p.code || '''' || ';' php_const
from
  privilege p
  join categorie_privilege cp on cp.id = p.categorie_id
order by
  cp.code, p.ordre;
  



update privilege set ordre = 1 where id = 6;
update privilege set ordre = 2 where id = 5;
update privilege set ordre = 3 where id = 10;

