-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/



UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'DE';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'DEPA';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'CDEPA';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'CEH';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'CRI';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'CIP';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'CC';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1132') WHERE code = 'CCC';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '102') WHERE code = 'RD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '102') WHERE code = 'RMD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'CETPD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'RPF';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'CMEPNF';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '102') WHERE code = 'DEA';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'OCSPS';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'OCSPT';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'PDAFD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '102') WHERE code = 'ACPD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '102') WHERE code = 'CDFOD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'RMFOD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'VP';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'CIE';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'REP';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'RDFDP';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'REQP';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'RMI';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'PPPI';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'MPREAPF';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'RBAIP';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'RMPPVCA';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'DURC';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'DED';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'PDE';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'VPD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'DC';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'CM';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'DAA';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'DSCG';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'PCD';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1132') WHERE code = 'EECU';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '101') WHERE code = 'ER';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'VAEVAP_AVC';
UPDATE fonction_referentiel SET domaine_fonctionnel_id = (SELECT id FROM domaine_fonctionnel WHERE source_code = '1153') WHERE code = 'VAEVAP_PJVA';



-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/