/* ============= PARTIE_B_SIHAM_REF  =================================

 script A lancer une fois pour le passage de OSE V14 à V15
 
 Mise à jour des data passé au format V15
 
 -- v1.0 02/02/21 MYP - creation
 -- v1.1 07/06/21 MYP - complément pour formatage UM_ADRESSE_INTERVENANT.NO_VOIE
 
 ===================================================================*/

--- A faire après ALTER TABLE B_1T_OSE_alter_tables.sql + recompil de toutes les fonctions et procedures


/* === V1.1 16/07/21 - MYP : suppression fonctions inutiles =========================================================*/

-------------------------------------------------------------------------------------------------
-- RACCOURCIR LE CHAMP NO_VOIRIE
-------------------------------------------------------------------------------------------------
-- Après avoir fait la resynchro ci-dessus :

update OSE.UM_ADRESSE_INTERVENANT set no_voie = trim(no_voie); -- pour éviter les espaces qui étaient déjà présents dans la version précédente

-- VERIFICATION + UPDATE :
select max(length(no_voie)) from OSE.UM_ADRESSE_INTERVENANT; 
-- verifier que le max est <= 4 : si ok alors faire la manip ci-dessous :

ALTER TABLE OSE.UM_ADRESSE_INTERVENANT MODIFY NO_VOIE VARCHAR2(4);
-- OSEPREP2 le 07/06/21 : Table altered.


/* === V1.0 03/2021 - OSE v15 : maj RIB + formatage des adresses découpées ==============================================*/

-------------------------------------------------------------------------------------------------
-- UM_INTERVENANT : maj RIB_HORS_SEPA passé 
-------------------------------------------------------------------------------------------------
-- après compil de toutes les fonctions et procedures : maj RIB_HORS_SEPA passé
begin
OSE.UM_INIT_RIB_HORS_SEPA();
end;
-- OSETEST le 07/01/2021 :  Lancement maj rib_hors_sepa :  nb update :2   -- 3 attendus en prod 
select * from um_intervenant where rib_hors_sepa <> 0;
-- OSETEST : ok 2 -- OSEPREP2 le 07/06 : ok 1

-------------------------------------------------------------------------------------------------
-- REFORMATAGE UM_ADRESSE_INTERVENANT passé
-------------------------------------------------------------------------------------------------
DECLARE
v_source_id                   number(2) := 0;
  
BEGIN
    select id INTO v_source_id from OSE.SOURCE where code = 'Siham';     -- ##A_PERSONNALISER_CHOIX_OSE## suivant votre declaration de connecteur
    
    -- UM_SYNCHRO_VOIRIE(v_source_id); -- normalement deja fait avec synchro referentiel sinon activer cette ligne
    dbms_output.put_line('_____');
    dbms_output.put_line(rpad('_____ ETAPE 3 : MAJ ADRESSES (UM_INTERVENANT_ADRESSE) : ',106,'_'));
	-- Synchro ADR pour tous les INTERVENANTS de l'année (synchronisés depuis date horodatage de 2015 pour etre large)
	
	-- ##A_PERSONNALISER_CHOIX_OSE## suivant les années déjà présentes en BDD
    UM_SYNCHRO_ADRESSE_INTERVENANT(v_source_id, 2018, to_date('01/01/2015','DD/MM/YYYY'));
    UM_SYNCHRO_ADRESSE_INTERVENANT(v_source_id, 2019, to_date('01/01/2015','DD/MM/YYYY'));
    UM_SYNCHRO_ADRESSE_INTERVENANT(v_source_id, 2020, to_date('01/01/2015','DD/MM/YYYY'));
    dbms_output.put_line('      => Apres SYNCHRO ADR intervenant : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
END;
/
select rpad('_____________ Fin : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'),106,'_') from dual;
spool off;



