 CREATE OR REPLACE FORCE EDITIONABLE VIEW "MANGUE"."ULHN_V_DERNIER_CONTRAT" ("NO_INDIVIDU", "NO_SEQ_CONTRAT", "D_FIN_CONTRAT_TRAV") AS 
  SELECT IND.NO_INDIVIDU, NO_SEQ_CONTRAT, nvl(ec.D_FIN_ANTICIPEE, D_FIN_CONTRAT_TRAV) dfin
      FROM grhum.INDIVIDU_ulr IND
          INNER JOIN grhum.personnel_ulr PE
             ON IND.NO_INDIVIDU = PE.NO_DOSSIER_PERS
          INNER JOIN mangue.CONTRAT ec
                       ON     ec.NO_DOSSIER_PERS = IND.NO_INDIVIDU
     inner join -- sur contrat le plus recent
     (
     select no_dossier_pers,
CASE WHEN MAX(NVL(d_fin_contrat_trav, TO_DATE('31.12.9999', 'DD.MM.RRRR'))) = TO_DATE('31.12.9999', 'DD.MM.RRRR') THEN NULL ELSE MAX(d_fin_contrat_trav) END maxcontrat
from MANGUE.CONTRAT
     WHERE  d_deb_contrat_trav <= TO_DATE(TO_CHAR(sysdate,'DD/MM/YYYY'),'DD/MM/YYYY')
     AND    tem_annulation = 'N'
     group by NO_DOSSIER_PERS
     ) ec2 ON ec.no_dossier_pers = ec2.no_dossier_pers AND ec.D_FIN_CONTRAT_TRAV=ec2.maxcontrat
    UNION
SELECT IND.NO_INDIVIDU, NO_SEQ_CONTRAT, null as dfin
      FROM grhum.INDIVIDU_ulr IND
          INNER JOIN grhum.personnel_ulr PE
             ON IND.NO_INDIVIDU = PE.NO_DOSSIER_PERS
          INNER JOIN mangue.CONTRAT ec
                       ON     ec.NO_DOSSIER_PERS = IND.NO_INDIVIDU
                       where ec.D_FIN_CONTRAT_TRAV is null;
