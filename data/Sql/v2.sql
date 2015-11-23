-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/


CREATE INDEX "OSE"."AII_FK_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("INTERVENANT_ID");

DROP MATERIALIZED VIEW "OSE"."MV_CENTRE_COUT";
CREATE MATERIALIZED VIEW "OSE"."MV_CENTRE_COUT" ("LIBELLE","Z_ACTIVITE_ID","Z_TYPE_RESSOURCE_ID","Z_PARENT_ID","Z_STRUCTURE_ID","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT DISTINCT
  TRIM(B.ktext) libelle,
  CASE
    WHEN a.kostl like '%A' THEN 'accueil' -- Activité (au sens compta analytique) ne devant pas permettre la saisie de référentiel
    WHEN a.kostl like '%B' THEN 'enseignement'
    WHEN a.kostl like '%M' THEN 'pilotage'
  END z_activite_id,
  CASE
    WHEN LENGTH(a.kostl) = 5 THEN 'paye-etat'
    WHEN LENGTH(a.kostl) > 5 THEN 'ressources-propres'
  END z_type_ressource_id,
  NULL z_parent_id,
  CASE WHEN A.kostl = 'P950DRRA' THEN 'ECODOCT' ELSE STR.CODE_HARPEGE END z_structure_id,
  s.id source_id,
  A.kostl source_code
  
FROM
  sapsr3.csks@sifacp A,
  sapsr3.cskt@sifacp B,
  unicaen_corresp_structure_cc str,
  source s
WHERE
    s.code = 'SIFAC'
    and A.kostl=B.kostl(+)
    and A.kokrs=B.kokrs(+)
    and substr( a.kostl, 2, 3 ) = str.code_sifac(+)
    and B.mandt(+)='500'
    and B.spras(+)='F'
    and A.kokrs='UCBN'
    and A.bkzkp !='X'
    and a.kostl LIKE 'P%' AND (a.kostl like '%A' OR a.kostl like '%B' OR a.kostl like '%M')
    AND SYSDATE BETWEEN to_date( NVL(A.datab,'10661231'), 'YYYYMMDD') AND to_date( NVL(A.datbi,'99991231'), 'YYYYMMDD')
    AND STR.CODE_HARPEGE IS NOT NULL -- à désactiver pour trouver les structures non référencées dans la table de correspondance
  
UNION

SELECT
  TRIM(A.post1) libelle,
  CASE
    WHEN a.kostl like '%A' THEN 'accueil'
    WHEN a.fkstl like '%B' THEN 'enseignement'
    WHEN a.fkstl like '%M' THEN 'pilotage'
  END z_activite_id,
  CASE
    WHEN LENGTH(a.fkstl) = 5 THEN 'paye-etat'
    WHEN LENGTH(a.fkstl) > 5 THEN 'ressources-propres'
  END z_type_ressource_id,
  A.fkstl z_parent_id,
  CASE WHEN A.posid = 'P950DRRA' THEN 'ECODOCT' ELSE STR.CODE_HARPEGE END z_structure_id,
  s.id source_id,
  A.posid source_code
FROM
  sapsr3.prps@sifacp A,
  sapsr3.prte@sifacp B,
  unicaen_corresp_structure_cc str,
  source s
WHERE
  s.code = 'SIFAC'
  and A.pspnr=B.posnr(+)
  and substr( A.fkstl, 2, 3 ) = str.code_sifac(+)
  and A.pkokr='UCBN'
  and B.mandt(+)='500'
  and a.fkstl LIKE 'P%' AND (a.fkstl like '%A' OR a.kostl like '%B' OR a.fkstl like '%M')
  AND SYSDATE BETWEEN to_date( NVL(B.pstrt,'10661231'), 'YYYYMMDD') AND to_date( NVL(B.pende,'99991231'), 'YYYYMMDD')
  AND STR.CODE_HARPEGE IS NOT NULL;
  
CREATE OR REPLACE FORCE VIEW "OSE"."V_ELEMENT_TYPE_MODULATEUR" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "TYPE_MODULATEUR_ID"
  )  AS 
  SELECT
  ep.id element_pedagogique_id,
  tms.type_modulateur_id type_modulateur_id
FROM
       element_pedagogique        ep
  JOIN structure                   s ON s.id = ep.structure_id                    
                                    AND ose_divers.comprise_entre( s.histo_creation, s.histo_destruction ) = 1
                                    
  JOIN type_modulateur_structure tms ON tms.structure_id = s.id 
                                    AND ose_divers.comprise_entre( tms.histo_creation, tms.histo_destruction ) = 1
                                    AND ep.annee_id BETWEEN GREATEST(NVL(tms.annee_debut_id,0),ep.annee_id) AND LEAST(NVL(tms.annee_fin_id,9999),ep.annee_id)

UNION

SELECT
  tm_ep.element_pedagogique_id element_pedagogique_id,
  tm_ep.type_modulateur_id type_modulateur_id
FROM
  type_modulateur_ep tm_ep 
WHERE
  ose_divers.comprise_entre( tm_ep.histo_creation, tm_ep.histo_destruction ) = 1;
  
CREATE OR REPLACE FORCE VIEW "OSE"."V_ELEMENT_TYPE_INTERVENTION" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID"
  )  AS 
  SELECT
  ep.id element_pedagogique_id,
  ti.id type_intervention_id
FROM
            element_pedagogique            ep
  JOIN      structure                    s_ep ON s_ep.id = ep.structure_id
  JOIN      type_intervention              ti ON 1=1
  
  LEFT JOIN type_intervention_ep        ti_ep ON ti_ep.element_pedagogique_id = ep.id 
                                             AND ti_ep.type_intervention_id = ti.id
                                             AND 1 = ose_divers.comprise_entre( ti_ep.histo_creation, ti_ep.histo_destruction )
  
  LEFT JOIN type_intervention_structure  ti_s ON s_ep.structure_niv2_id = ti_s.structure_id 
                                             AND ti_s.type_intervention_id = ti.id
                                             AND 1 = ose_divers.comprise_entre( ti_s.histo_creation, ti_s.histo_destruction )
                                             AND ep.annee_id BETWEEN GREATEST(NVL(ti_s.annee_debut_id,0),ep.annee_id) AND LEAST(NVL(ti_s.annee_fin_id,9999),ep.annee_id)
WHERE
  CASE
    WHEN TI_EP.VISIBLE IS NULL THEN
      CASE
        WHEN TI_S.VISIBLE IS NULL THEN TI.VISIBLE
        ELSE TI_S.VISIBLE
      END
    ELSE TI_EP.VISIBLE
  END = 1
ORDER BY
  TI.ORDRE;
  
  
ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD ("ANNEE_DEBUT_ID" NUMBER(*,0));
ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD ("ANNEE_FIN_ID" NUMBER(*,0));
ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TMS_ANNEE_DEBUT_FK" FOREIGN KEY ("ANNEE_DEBUT_ID") REFERENCES "OSE"."ANNEE"("ID") ENABLE;
ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TMS_ANNEE_FIN_FK" FOREIGN KEY ("ANNEE_FIN_ID") REFERENCES "OSE"."ANNEE"("ID") ENABLE;

ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD ("ANNEE_DEBUT_ID" NUMBER(*,0));
ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD ("ANNEE_FIN_ID" NUMBER(*,0));
ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD CONSTRAINT "TIS_ANNEE_DEBUT_FK" FOREIGN KEY ("ANNEE_DEBUT_ID") REFERENCES "OSE"."ANNEE"("ID") ENABLE;
ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD CONSTRAINT "TIS_ANNEE_FIN_FK" FOREIGN KEY ("ANNEE_FIN_ID") REFERENCES "OSE"."ANNEE"("ID") ENABLE;

ALTER TABLE "OSE"."INTERVENANT" ADD ("MONTANT_INDEMNITE_FC" FLOAT(126));


UPDATE "OSE"."TYPE_INTERVENTION_STRUCTURE" SET ANNEE_FIN_ID = 2014 WHERE type_intervention_id = 10;

INSERT INTO CC_ACTIVITE (
    ID,
    CODE,
    LIBELLE,
    FI,
    FA,
    FC,
    FC_MAJOREES,
    REFERENTIEL,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
) VALUES (
    CC_ACTIVITE_id_seq.nextval,
    'accueil',
    'Accueil',
    1,
    1,
    1,
    1,
    0,
    sysdate, (select id from utilisateur where username='lecluse'),
    sysdate, (select id from utilisateur where username='lecluse')
);

-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/