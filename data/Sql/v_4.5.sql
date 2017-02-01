-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/


UPDATE departement SET source_code = '0' || source_code WHERE length(source_code) = 2;

update intervenant set pays_naissance_id = 
  (select id from pays where source_code = pays_naissance_code_insee) 
where pays_naissance_code_insee is not null;

update intervenant set pays_nationalite_id = 
  (select id from pays where source_code = pays_nationalite_code_insee) 
where pays_nationalite_code_insee is not null;

update intervenant set dep_naissance_id = 
  (select id from departement where source_code = dep_naissance_code_insee) 
where dep_naissance_code_insee is not null;


-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

INSERT INTO package_deps (id,p1,p2) values (17, 'OSE_CLOTURE_REALISE', null);
INSERT INTO package_deps (id,p1,p2) values (18, 'OSE_CONTRAT', null);
INSERT INTO package_deps (id,p1,p2) values (19, 'OSE_DOSSIER', null);
INSERT INTO package_deps (id,p1,p2) values (20, 'OSE_FORMULE', null);
INSERT INTO package_deps (id,p1,p2) values (21, 'OSE_PIECE_JOINTE_DEMANDE', null);
INSERT INTO package_deps (id,p1,p2) values (22, 'OSE_PIECE_JOINTE_FOURNIE', null);
INSERT INTO package_deps (id,p1,p2) values (23, 'OSE_SERVICE', null);
INSERT INTO package_deps (id,p1,p2) values (24, 'OSE_SERVICE_REFERENTIEL', null);
INSERT INTO package_deps (id,p1,p2) values (25, 'OSE_SERVICE_SAISIE', null);
INSERT INTO package_deps (id,p1,p2) values (26, 'OSE_VALIDATION_ENSEIGNEMENT', null);
INSERT INTO package_deps (id,p1,p2) values (27, 'OSE_VALIDATION_REFERENTIEL', null);

INSERT INTO SCENARIO (
    ID,
    CODE,
    LIBELLE,
    STRUCTURE_ID,
    DEFINITIF,
    REEL,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
) VALUES (
    SCENARIO_id_seq.nextval,
    'reel',
    'Réel',
    null,
    1,
    1,
    sysdate,
    (select id from utilisateur where username='lecluse'),
    sysdate,
    (select id from utilisateur where username='lecluse')
);



BEGIN
  OSE_EVENT.SET_ACTIF(FALSE);
END;
/



SELECT
  'UPDATE element_pedagogique SET etape_id = ' || en.id || ' WHERE id = ' || ep.id || ';' usql
FROM
  element_pedagogique ep
  JOIN etape eo ON eo.id = ep.etape_id
  JOIN etape en ON en.source_code = eo.source_code AND en.annee_id = ep.annee_id AND NVL(en.histo_destruction,sysdate) = NVL(en.histo_destruction,sysdate)
WHERE
  eo.id <> en.id;



SELECT
  'UPDATE chemin_pedagogique SET etape_id = ' || en.id || ' WHERE id = ' || cp.id || ';' usql
FROM
  chemin_pedagogique cp
  JOIN element_pedagogique ep ON ep.id = cp.element_pedagogique_id
  JOIN etape eo ON eo.id = ep.etape_id
  JOIN etape en ON en.source_code = eo.source_code AND en.annee_id = ep.annee_id AND NVL(en.histo_destruction,sysdate) = NVL(en.histo_destruction,sysdate)
WHERE
  eo.id <> en.id;


  
/
BEGIN
  OSE_EVENT.SET_ACTIF(true);
  OSE_EVENT.force_calculer_tout(2014);
  OSE_EVENT.force_calculer_tout(2015);
  OSE_EVENT.force_calculer_tout(2016);
END;


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/