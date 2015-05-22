  
  
INSERT INTO CATEGORIE_PRIVILEGE (
  ID,
  CODE,
  LIBELLE
) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'modification-service-du',
  'Modification de service dû'
);

INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE
) VALUES (
  privilege_id_seq.nextval,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = 'modif-service-du' ),
  'association',
  'Association'
);

INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE
) VALUES (
  privilege_id_seq.nextval,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = 'modif-service-du' ),
  'visualisation',
  'Visualisation'
);

INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE
) VALUES (
  privilege_id_seq.nextval,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = 'modif-service-du' ),
  'edition',
  'Édition'
);

INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE
) VALUES (
  privilege_id_seq.nextval,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = 'mise-en-paiement' ),
  'export-paie',
  'Export vers le logiciel de paie'
);

select
  --cp.code categorie,
  --p.code privilege,
  '    const '
    || rpad( upper( replace( cp.code, '-', '_' ) || '_' || replace( p.code, '-', '_' ) ), MAX( length( cp.code ) + length( p.code ) ) OVER (PARTITION BY 1 )+1, ' ' )
    || ' = ' || '''' || cp.code || '-' || p.code || '''' || ';' php_const
from
  privilege p
  join categorie_privilege cp on cp.id = p.categorie_id
order by
  cp.code, p.code