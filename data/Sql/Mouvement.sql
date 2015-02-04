-- domaines fonctionnels
SELECT
  A.fkber "Domaine Fonctionnel",
  B.fkbtx "Libellé",
  A.datab "Date début de validité", -- la plus petite des deux
  A.datbis "Date de fin de validité",
  A.date_exp "Date d'expiration"
FROM
  sapsr3.tfkb@sifacp A,
  sapsr3.tfkbt@sifacp B
WHERE
    A.mandt=B.mandt
and A.fkber=B.fkber
and B.SPRAS='F'
and A.mandt='500';

-- centres de cout
SELECT
  A.kostl CC,
  A.kokrs "Périm Analytique",
  A.datab "date début validite",
  A.datbi "date fin de validité",
  B.ktext "Libellé",
  A.verak "Centre Financier",
  A.bukrs "Société",
  A.func_area "Domaine Fonctionnel",
  A.PRCTR "Centre de Profit",
  A.gsber "Domaine d'activité",
  A.bkzkp "Blocage couts primaires"
FROM
  sapsr3.csks@sifacp A,
  sapsr3.cskt@sifacp B
WHERE
    A.kostl=B.kostl(+)
and A.kokrs=B.kokrs(+)
and B.mandt(+)='500'
and B.spras(+)='F'
and A.kokrs='UCBN'
and A.bkzkp !='X'
order by 1;

------------EOTP-------------------

SELECT
  A.posid "Code Eotp",
  A.post1 "Désignation",
  A.pkokr "Périm Analytique",
  A.pbukr "Société",
  A.fkstl "CC associé",
  A.prctr "CP associé",
  A.func_area "Dom Fonctionnel",
  B.pstrt "Date début",
  B.pende "Date fin"
FROM
  sapsr3.prps@sifacp A,
  sapsr3.prte@sifacp B
WHERE
  A.pspnr=B.posnr(+)
and A.pkokr='UCBN'
and B.mandt(+)='500'
and A.fkstl like 'P%B';


--------dates EOtp ds PRTE---------------------

SELECT
  *
FROM
  sapsr3.prte@sifacp
WHERE
  mandt='500';
  and posnr='00000567';
  



-- CENTRES DE COUT --
SELECT DISTINCT
  B.ktext libelle,
  CASE
    WHEN a.kostl like '%B' THEN 'enseignement'
    WHEN a.kostl like '%M' THEN 'pilotage'
  END z_activite_id,
  CASE
    WHEN LENGTH(a.kostl) = 5 THEN 'paye-etat'
    WHEN LENGTH(a.kostl) > 5 THEN 'ressources-propres'
  END z_type_ressource_id,
  
  NULL z_parent_id,
  STR.CODE_HARPEGE z_structure_id,

  
  
  
  
  
  A.kostl code
  
FROM
  sapsr3.csks@sifacp A,
  sapsr3.cskt@sifacp B,
  unicaen_corresp_structure_cc str 
WHERE
    A.kostl=B.kostl(+)
    and A.kokrs=B.kokrs(+)
    and substr( a.kostl, 2, 3 ) = str.code_sifac(+)
    and B.mandt(+)='500'
    and B.spras(+)='F'
    and A.kokrs='UCBN'
    and A.bkzkp !='X'
    and a.kostl LIKE 'P%' AND (a.kostl like '%B' OR a.kostl like '%M')
    AND SYSDATE BETWEEN to_date( A.datab, 'YYYYMMDD') AND to_date( A.datbi, 'YYYYMMDD')
    AND STR.CODE_HARPEGE IS NOT NULL -- à désactiver pour trouver les structures non référencées dans la table de correspondance
  