  
  
INSERT INTO CATEGORIE_PRIVILEGE (
  ID,
  CODE,
  LIBELLE
) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'intervenant',
  'Intervenant'
);

INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE
) VALUES (
  privilege_id_seq.nextval,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = 'intervenant' ),
  'fiche',
  'Visualisation de la fiche'
);


select
  --p.id, cp.code categorie, p.code privilege,
  '    const '
    || rpad( upper( replace( cp.code, '-', '_' ) || '_' || replace( p.code, '-', '_' ) ), MAX( length( cp.code ) + length( p.code ) ) OVER (PARTITION BY 1 )+1, ' ' )
    || ' = ' || '''' || cp.code || '-' || p.code || '''' || ';' php_const
from
  privilege p
  join categorie_privilege cp on cp.id = p.categorie_id
order by
  cp.code, p.code;
  
  


-- EXPORT

categorie_privilege
privilege

role

role_privilege
statut_privilege