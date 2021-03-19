/* ============= PARTIE_B_SIHAM_REF  =================================

 script A lancer une fois pour le passage de OSE V14 à V15
 
 Mise à jour des data passé au format V15
 ===================================================================*/

--- A faire après ALTER TABLE + recompil de toutes les fonctions et procedures


-------------------------------------------------------------------------------------------------
-- UM_INTERVENANT : maj RIB_HORS_SEPA passé 
-------------------------------------------------------------------------------------------------
-- après compil de toutes les fonctions et procedures : maj RIB_HORS_SEPA passé
begin
OSE.UM_INIT_RIB_HORS_SEPA();
end;
-- OSETEST le 07/01/2021 :  Lancement maj rib_hors_sepa :  nb update :2   -- 3 attendus en prod 
select * from um_intervenant where rib_hors_sepa <> 0;
-- ok 2

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
    UM_SYNCHRO_ADRESSE_INTERVENANT(v_source_id, 2018, to_date('01/01/2015','DD/MM/YYYY'));
    UM_SYNCHRO_ADRESSE_INTERVENANT(v_source_id, 2019, to_date('01/01/2015','DD/MM/YYYY'));
    UM_SYNCHRO_ADRESSE_INTERVENANT(v_source_id, 2020, to_date('01/01/2015','DD/MM/YYYY'));
    dbms_output.put_line('      => Apres SYNCHRO ADR intervenant : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
END;
/
select rpad('_____________ Fin : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'),106,'_') from dual;
spool off;
