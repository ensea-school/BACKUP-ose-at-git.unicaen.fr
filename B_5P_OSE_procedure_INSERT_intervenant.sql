CREATE OR REPLACE PROCEDURE OSE.UM_INSERT_INTERVENANT(p_source_id number, p_annee_id number, p_d_deb_annee_univ date, p_d_fin_annee_univ date, p_date_systeme date) IS
/* ====================================================================================================
  # Detail du connecteur PARTIE B/ SIHAM_INTERV : SYNCHRO DES INTERVENANTS - Avec user OSE
   
  PROCEDURE OSE.UM_INSERT_INTERVENANT

  Cette procédure permet, à partir des dossiers sélectionnés (flag "TODO" ou "A_INS" dans OSE.UM_TRANSFERT_INDIVIDU),
  de sélectionner d'insérer ou updater les dossiers dans OSE.UM_INTERVENANT
    
  Appelée par : lance_synchro_Siham_Ose.sql
  Paramètres : cf. explications script appelant ci-dessus
  
  Sortie :     - Maj Table OSE.UM_INTERVENANT (en update ou insert - voir D_horodatage)
            - Maj Table OSE.UM_TRANSFERT_INDIVIDU flag témoins traitements d'update ou insert à "DONE"
            - Maj Table OSE.UM_SYNCHRO_A_VALIDER : détection changement de statut automatique ou manuel
            
  -- v2.0b 24/01/20 MYP : var v_stat_transfert : augmentation taille variable
  -- v2.1  06/02/20 MYP : test date fin fonction pas dépassée à p_date_systeme
  -- v2.2  21/07/20 MYP : test <= p_date_systeme sinon ne remonte pas modserv pour synchro en avance au 01/09 new annee
  -- v2.2b 19/11/20 MYP : adaptation prép V15 : v_annee_id remplacé par param p_annee_id
  -- v2.2c 01/03/21 MYP : correction no_insee def depuis Siham pers nées apres 2000
  -- v3.0  07/01-05/03/21 MYP : adaptations pour OSE V15
  -- v3.1  14/06/21 MYP : correction taille variable v_maj_statut_a_faire + d_validation null quand MULTI_AUTO 'AI' + date_deb_statut au 01/09 si 1er enreg
  -- v3.2  15/06/21 MYP : report modifs V14 depuis 03/2021 
            -- v2.3   19/04/21 MYP : test en dur pour laisser passer 2 dossiers hors UM HXC0B00005 avec MCE, comme si que recherche et MCE uo liée
            -- v2.3b  27/04/21 MYP : validé claire pourtous les HX%, avec MCE, comme si que recherche et MCE uo liée
            -- + correction pb id_grade_ose à null pour les CTRL Permanents  + date_deb_statut au 01/09 si 1er enreg
  -- v4.0  21/09/21 MYP : pb update grade_id doit se faire dans le bloc update statut validé
  -- v4.1  11/10/21 MYP : remplissage um_intervenant.EMP_SOURCE_CODE provenant d'orec : pour rafp
  -- v4.2  25/01/22 MYP : qd de IE à IE forcer date_deb au 01/09 (idem statut_actuel)
  -- v4.2b 04/02/22 MYP : dblink .world
  -- v4.3  25/03/22 MYP : pour les dossiers devenant HOSE, remonter test END IF pour les tracer dans cgmt statut table UM_SYNCHRO_A_VALIDER
  -- v5.0  30/05/22 MYP : test sexe pour affecter civilite + 17/06/22 param v_uo_a_exclure
  -- v5.1  20/07/22 MYP : param C_ORG_RATTACH passage de valeur unique à multiple codes UAI => variable + test avec in (v_org_rattach)

=====================================================================================================*/

-- VARIABLES DE TRAITEMENT ----------------------------
v_stat_transfert            VARCHAR2(1000)		:= ''    ; -- v1.14b -- v2.0b
v_nb_a_valider              NUMBER(5)           := 0    ;
v_maj_statut_a_faire        VARCHAR2(15)        := 'OUI'; -- v3.1 14/06/21
v_statut_siham              VARCHAR2(8)         := ''    ; -- v3.0
v_ose_mail_pro              VARCHAR2(255)       := ''    ; -- v3.0
v_id_grade_ose              NUMBER(9)           := 0    ; -- v3.0
v_tem_transfert_ose         VARCHAR2(1)         := 'N'    ; -- v3.0
v_param_gestion_statut      VARCHAR2(100)		:= ''    ;
v_new_date_deb_statut		DATE				:= '';   -- v4.2 25/01/22
v_uo_a_exclure				varchar2(100)		:= ''; 	 -- v5.0 17/06/22
v_org_rattach				varchar2(100)		:= ''; 	 -- v5.1 20/07/22

-- v3.0 objet tableau de type T_UM_ENREG_STATUT(ID, CODE_STATUT, CODE_TYPE_INTERVENANT, DATE_DEB_STATUT, DATE_FIN_STATUT, NB_H_MCE) cf. script create table
v_statut_actuel            T_UM_ENREG_STATUT     := T_UM_ENREG_STATUT(0,'','',null, null,0);
v_statut_new            T_UM_ENREG_STATUT     := T_UM_ENREG_STATUT(0,'HOSE','',null, null,0);    

/*================== curseur cur_ose_update_intervenant =========================*/
cursor cur_ose_update_intervenant is
    select ose_i.ID, i.nudoss, i.matcle, i.uid_ldap 
           ,i.qualit, i.nomuse, i.prenom, i.nompat
    from OSE.UM_TRANSFERT_INDIVIDU i,
         OSE.UM_INTERVENANT ose_i
    where i.annee_id = p_annee_id        -- v1.14
        and i.TEM_OSE_UPDATE = 'TODO'
        and i.MATCLE = ose_i.SOURCE_CODE -- v3.0
        and ose_i.annee_id = p_annee_id
        and ose_i.date_deb_statut <= p_date_systeme and ose_i.date_fin_statut >= p_date_systeme  -- v3.0 maj du statut de la periode de synchro
    order by i.matcle    
;
/*================== curseur cur_ose_insert_intervenant =========================*/
cursor cur_ose_insert_intervenant is
    select i.nudoss, i.matcle, i.uid_ldap 
           ,i.qualit, i.nomuse, i.prenom, i.nompat, i.TEM_OSE_INSERT
    from OSE.UM_TRANSFERT_INDIVIDU i
    where i.TEM_OSE_INSERT in ('TODO','A_INS')    -- TODO = 1er INSERT / A_INS = multi_statut à inserer auto
        and i.annee_id = p_annee_id        -- v1.14
    order by i.matcle    
;

/*================== curseur cur_intervenant ===============================*/
cursor cur_intervenant(p_siham_matricule VARCHAR2, p_nudoss NUMBER, p_d_deb_annee_univ date, p_d_fin_annee_univ date) is
SELECT distinct
V_INTERV.civilite_id
,V_INTERV.nom_usuel
,V_INTERV.prenom
,V_INTERV.nom_patronymique
,trunc(V_INTERV.date_naissance) as date_naissance
--,V_INTERV.ville_naissance_code_insee            -- v3.0  07/01/2021
,V_INTERV.ville_naissance_libelle
,V_INTERV.numero_insee
,V_INTERV.numero_insee_cle
,V_INTERV.numero_insee_prov
,V_INTERV.iban
,V_INTERV.bic
,V_INTERV.rib_hors_sepa                            -- v3.0  07/01/2021
,V_INTERV.pays_naissance_id
,V_INTERV.dep_naissance
,V_INTERV.pays_nationalite_id
--------- STRUCTURE UO / COMPOSANTE -------------------------------------
-- pour OSE : ID de la structure OSE.STRUCTURE (correspondant à UO d'aff principale HIE niv3 ou aff FUN pédagogique ou pour les IE Vac : UO d'OREC)
,V_INTERV.structure_id
,V_INTERV.matricule        as source_code
--------- TEL et MAIL pour OSE : suivant si IE ou PERM ------------------
,V_INTERV.tel_pro                                             as ose_tel_pro
,V_INTERV.tel_mobile_perso                                    as ose_tel_mobile
-- ##A_PERSONNALISER_CHOIX_SIHAM## : si pas besoin supprimer V_BI_PASSEPORT et DBLink @BI.WORLD et remonter directement le mail suivant vos règles
,V_BI_PASSEPORT.um_passeport        -- = '1' si passeport info validee
,V_INTERV.mail_pro                     -- mail institutionnel
,V_INTERV.mail_perso                -- mail personnel dans Siham

--------- STATUT INTERV -- GRADE -----------------------------------------
-- ,V_INTERV.statut_id        -- v3.0 statut calculé dans prog principal et non dans Select cf um_affecte_statut
-- , ancien_statut_id        -- v3.0 statut calculé dans prog principal et non dans Select
,V_INTERV.id_grade_ose       -- v1.10 - 11/04/2019  -- v3.0 id_grade_ose pour les IE calculé dans prog principal et non dans Select
,V_INTERV.W_DATEFF_GRADE
--------- OREC -- infos vacataires ---------------------------------------
,V_INTERV.orec_code_uo
,V_INTERV.orec_lib_categorie
,V_INTERV.tem_present_orec
,V_INTERV.recrutement
,V_INTERV.type_employeur
,case  -- temps partagés rectorat -- v1.5b- 09/11/2018  -- v1.10 12/04/2019 tem_partage_rectorat
    when V_INTERV.W_GROUPE_HIE = 'DA' and V_INTERV.tem_partage_rectorat = 'O' then 'PARTAGE RECTORAT'
    else V_INTERV.employeur
end                                                                 as employeur
-- si modserv not null = si tps partage rectorat
,case when V_INTERV.modserv_ville_service_recto is not null then V_INTERV.modserv_ville_service_recto
    else V_INTERV.orec_ville_service_recto
end                                                                 as ville_service_rectorat
,V_INTERV.nb_h_service_rectorat
-------- temps partagés Rectorat -- v1.5b- 09/11/2018  -- v1.10 12/04/2019 tem_partage_rectorat

,case when V_INTERV.W_GROUPE_HIE = 'DA' and V_INTERV.tem_partage_rectorat = 'O' then V_INTERV.quotite_remu_um 
    else 0
end as quotite_temps_partage
-- ,tem_transfert_ose    -- v3.0 tem_transfert_ose calculé dans prog principal et non dans Select    
-------- DEPART DEFINITIF EN COURS D'ANNEE -------------------------------
,V_INTERV.cause_depart_def 
,case when V_INTERV.cause_depart_def is not null then
        -- idem case ci-dessus pour date_deb_etat dans le cas de cause depart renseignee
        case when V_INTERV.etat_annee_univ = 'ACTIF' and p_date_systeme > trunc(V_INTERV.date_fin_etat) and p_date_systeme < p_d_fin_annee_univ then 
                trunc(V_INTERV.date_fin_etat+1)
            else trunc(V_INTERV.date_deb_etat) -- on laisse la date de l'affectation
        end
    else null
end     as date_depart_def

-------- SPECIALITE / DICIPLINE / CNU ------------------------------------
,V_INTERV.type_specialite        as TYPE_SPECIALITE
,V_INTERV.specialite            as SPECIALITE

-- Pour savoir si passeport informatique et compte validé
,V_INTERV.uid_ldap

------- INFOS SIHAM supplémentaires utiles pour contrôles DRH ou multi statut -------
,V_INTERV.W_STATUT_PIP
,V_INTERV.W_TEM_ENSEIG
,V_INTERV.W_GROUPE_HIE
,V_INTERV.W_CODE_EMPLOI
,V_INTERV.W_LIB_EMPLOI
,V_INTERV.W_TYPE_FONCTION
,V_INTERV.W_FONCTION
,V_INTERV.W_DATDEB_FC
,V_INTERV.W_DATFIN_FC
,V_INTERV.W_NB_HEURE_MCE
-- ancien_nb_heure_mce    -- pour detecter chgt nb heure mce -- v3.0 multi statut traité dans prog principal dans UM_RECUP_INTERV_STATUT
,V_INTERV.W_STRUCTURE_UO
,V_INTERV.W_POSITION
,V_INTERV.W_LIB_POSITION

,V_INTERV.W_TEM_FONC			-- v3.0 infos compl pour multi statut
,V_INTERV.W_MODSERV				-- v3.0 infos compl pour multi statut
,V_INTERV.W_OREC_TYPE_VAC		-- v3.0 infos compl pour multi statut
,V_INTERV.DATE_DEB_ETAT			-- v3.0 infos compl pour multi statut = date deb affectation
,V_INTERV.W_DATDEB_POSITION		-- v3.0 infos compl pour multi statut
,V_INTERV.W_DATEFF_MODSERV		-- v3.0 infos compl pour multi statut
,V_INTERV.W_DATEFF_STATUT		-- v3.0 infos compl pour multi statut
,V_INTERV.W_CORPS				-- v3.0 infos compl pour multi statut
,V_INTERV.emp_source_code		-- v4.1 11/10/2021											 

/* *********************************** FIN GROS SELECT *************************************************/
FROM
(     ----------------- SELECT DE V_INTERV -----------------------
    select distinct
    -- v_aff_gene = affectation principale -----------------------------
    (select id from ose.civilite where sexe = v_aff_gene.sexe) as civilite_id   	-- v5.0 30/05/22 
    ,trim(v_aff_gene.nomuse)                    as nom_usuel
    ,trim(v_aff_gene.prenom)                    as prenom
    ,trim(v_aff_gene.nompat)                    as nom_patronymique
    ,v_aff_gene.recrutement                        as recrutement                    -- info orec
    ,v_aff_gene.type_emp                        as type_employeur                -- info orec
    ,v_aff_gene.employeur                        as employeur                     -- info orec
    ,v_aff_gene.orec_ville_service_recto         as orec_ville_service_recto     -- info orec
    ,v_aff_gene.nb_h_service_rectorat            as nb_h_service_rectorat        -- info orec
    ,v_aff_gene.orec_code_uo                    as orec_code_uo                    -- info orec
    ,v_aff_gene.orec_lib_categorie                as orec_lib_categorie            -- info orec
    ,v_aff_gene.type_transfert                    as type_transfert                -- info pop um_transfert_individu
    ,case when v_aff_gene.recrutement is not null then 'O' 
            else 'N' end                        as tem_present_orec                -- pour tester si vacataire dossier validé orec
    ,v_uo_ose.id                                as structure_id                    -- pour OSE
    ,v_aff_gene.etat_annee_univ                    as etat_annee_univ                -- ACTIF si present / INACTIF si parti en cours d annee
    ,v_aff_gene.deb_affect                        as date_deb_etat                -- Date debut de ACTIF/INACTIF
    ,v_aff_gene.fin_affect                        as date_fin_etat                -- Date fin de ACTIF/INACTIF
    ,v_aff_gene.cause_depart_def                as cause_depart_def             -- Si depart definitif en cours d annee

    ,trim(v_aff_gene.matcle)                    as matricule                      -- v3.0 renommage source_code en matricule
    ,v_aff_gene.uid_ldap                        as uid_ldap                        -- uid dans ldap pour @BI.WORLD et verif si passeport univ valide
    --, statut_id         -- v3.0 AFFECTATION statut_id et dat_statut_ose:  calculé en 1 seule fonction UM_AFFECTE_STATUT et dans begin prog principal plutot que dans le select
    --, dat_statut_ose     -- v3.0 AFFECTATION statut_id et dat_statut_ose:  calculé en 1 seule fonction UM_AFFECTE_STATUT et dans begin prog principal plutot que dans le select
    
    -- naissance ----------------------------------------
    ,v_naiss.datnai                            as date_naissance
    --,v_naiss.comnai                            as ville_naissance_code_insee    -- v3.0  07/01/2021
    ,v_naiss.vilnai                         as ville_naissance_libelle
    ,v_naiss.id_pays_naiss                    as pays_naissance_id    
    ,v_naiss.depnai                            as dep_naissance        
    ,v_naiss.id_pays_nat                    as pays_nationalite_id  
    -- coordonnées ---------------------------------------
    ,substr(trim(v_tel.tel_pro),1,20)            as tel_pro
    ,substr(trim(v_tel.tel_perso),1,20)            as tel_perso
    ,substr(trim(v_tel.tel_mobile_pro),1,20)    as tel_mobile_pro
    ,substr(trim(v_tel.tel_mobile_perso),1,20)    as tel_mobile_perso
    ,trim(v_tel.mail_pro)                        as mail_pro
    ,trim(v_tel.mail_perso)                        as mail_perso
    -- nss ------------------------------------------------
    ,nvl(v_nss.insee_def,v_nss.insee_prov) as numero_insee
    ,case when v_nss.insee_def is null then 
        v_nss.cle_insee_prov
        else v_nss.cle_insee_def
    end    as numero_insee_cle
    ,case when v_nss.insee_def is null then 1
            else 0
    end                                        as numero_insee_prov    -- flag insee prov
    -- bq -------------------------------------------------
    ,v_banque.iban                            as iban
    ,v_banque.bic                            as bic
    ,v_banque.rib_hors_sepa                    as rib_hors_sepa        -- v3.0  07/01/2021
    -- GRADE_ID suivant grade ou statut SIHAM -------------
    ,nvl(v_corps_grade.id_grade_ose,v_statut.id_grade_ose)    as id_grade_ose        --- id du grade OSE (grade ou statut_pip)
    
    -- Zones W_... Infos SIHAM  pour verif DSIN BGME ------
    ,v_corps_grade.dateff                    as W_DATEFF_GRADE
    ,v_corps_grade.corps                    as W_CORPS
    
    ,trim(v_statut.statut_pip)                as W_STATUT_PIP
    ,v_statut.dateff                        as W_DATEFF_STATUT
    ,trim(v_statut.tem_enseig)                as W_TEM_ENSEIG
    ,trim(v_corps_grade.groupe_hierarchique)    as W_GROUPE_HIE      --v1.14
    ,trim(v_aff_gene.code_emploi)            as W_CODE_EMPLOI
    ,trim(v_aff_gene.lib_emploi)            as W_LIB_EMPLOI
    ,trim(v_fonction.type_fonction)            as W_TYPE_FONCTION
    ,trim(v_fonction.code_fonction)            as W_FONCTION
    ,v_fonction.nb_heure_mce                as W_NB_HEURE_MCE          -- v1.3 DOC MCE
    ,v_fonction.datdeb                        as W_DATDEB_FC
    ,v_fonction.datfin                        as W_DATFIN_FC
    ,trim(v_aff_gene.uo)                    as W_STRUCTURE_UO    
    ,trim(v_position.code_position)            as W_POSITION
    ,trim(v_position.ll_position)            as W_LIB_POSITION
    ,v_position.datdeb                        as W_DATDEB_POSITION      --v1.15b
    ,v_special.type_specialite                as TYPE_SPECIALITE
    ,v_special.specialite                    as SPECIALITE
    
    ,v_aff_gene.tem_fonc                    as W_TEM_FONC            -- v3.0 remonter pour multi-statut
    ,v_modserv.code_modserv                    as W_MODSERV            -- v3.0 remonter pour multi-statut
    ,v_aff_gene.orec_type_vac                as W_OREC_TYPE_VAC        -- v3.0 remonter pour multi-statut
	,v_aff_gene.emp_source_code											-- v4.1 11/10/2021
    -- modalites de service ----------------------------------
    ,v_modserv.quotite_remu_um                as quotite_remu_um
    ,v_modserv.ville_service_rectorat        as modserv_ville_service_recto
    ,v_modserv.code_modserv                    as code_modserv
    ,v_modserv.dateff                        as W_DATEFF_MODSERV
    ,v_modserv.RNE_partage                     as RNE_partage
    ,v_modserv.tem_partage_rectorat            as tem_partage_rectorat  -- v1.10

    from
    (      ---- v_aff_gene AFFECTATION PRINCIPALE POUR OSE -----------------------------------------------------------------        
        ---  prendre la dernière affectation sur l'année univ en cours ou future affectation (demandé par notre DRH pour planning) + gérer flag ACTIF/INACTIF
        ---- aff_hie sauf pour les HR 1er aff_fun
        select distinct 
        v_aff_princ.nudoss
        ,v_aff_princ.matcle
        ,v_aff_princ.uid_ldap
        ,v_aff_princ.qualit
        ,v_aff_princ.nomuse
        ,v_aff_princ.prenom
        ,v_aff_princ.nompat
        ,v_aff_princ.recrutement                  -- info orec
        ,v_aff_princ.type_emp                      -- info orec
        ,v_aff_princ.tem_fonc                      -- info orec
        ,v_aff_princ.employeur                    -- info orec
        ,v_aff_princ.orec_ville_service_recto     -- info orec
        ,v_aff_princ.nb_h_service_rectorat        -- info orec
        ,v_aff_princ.orec_code_uo                -- info orec
        ,v_aff_princ.uo                             -- uo_affect_hie non recherche HR niveau fin
        ,v_aff_princ.orec_lib_categorie            -- info orec
        ,v_aff_princ.type_transfert                -- info type population de la table um_transfert_individu
        ,v_aff_princ.type_aff
        -- ##A_PERSONNALISER_CHOIX_SIHAM## : uo principale pour OSE du niveau supérieur choisi : composantes/directions 
        -- pour les vacataires UO_SUP venant de Siham null donc on prend celle de Orec
        ,case when OSE.UM_AFFICH_UO_SUP(v_aff_princ.uo) is null then orec_code_uo
            else OSE.UM_AFFICH_UO_SUP(v_aff_princ.uo)
        end as uo_affect_princ
        ,v_aff_princ.date_deb                             as deb_affect
        ,v_aff_princ.date_fin                             as fin_affect
        ,v_aff_princ.etat                                as etat_annee_univ   --- ACTIF / INACTIF
        ,v_aff_princ.cause_depart_def                     as cause_depart_def  --- motif depart definitif
        ,trim(v_aff_princ.code_emploi)                    as code_emploi
        ,trim(emp_lib.lbjblg)                             as lib_emploi
        ,v_aff_princ.orec_type_vac                      -- v1.8 - 11/02/2019
		,v_aff_princ.emp_source_code					-- v4.1 11/10/2021
		,v_aff_princ.sexe								-- v5.0 30/05/22		
        from 
        ( --- affectations HIE hors recherche ou aff FUN HE% enfin structure péda dans OSE
            SELECT 
            v_aff.nudoss
            ,v_aff.matcle
            ,v_aff.uid_ldap
            ,v_aff.qualit
            ,v_aff.nomuse
            ,v_aff.prenom
            ,v_aff.nompat
            ,v_aff.recrutement
            ,v_aff.type_emp
            ,v_aff.tem_fonc
            ,v_aff.employeur
            ,v_aff.orec_ville_service_recto
            ,v_aff.nb_h_service_rectorat
            ,v_aff.orec_code_uo
            ,v_aff.uo                 -- uo_affect_hie non HR
            ,v_aff.orec_lib_categorie
            ,v_aff.etat              -- ACTIF / INACTIF    
            ,v_aff.date_deb
            ,v_aff.date_fin
            ,v_aff.code_emploi
            ,v_aff.cause_depart_def
            ,v_aff.type_aff
            ,v_aff.type_transfert
            ,v_aff.orec_type_vac					-- v1.8 - 11/02/2019
			,v_aff.emp_source_code					-- v4.1 11/10/2021	
			,v_aff.sexe								-- v5.0 30/05/22			
            -- numérotation periodes la rnum = 1 sera celle pour OSE priorité à celle ACTIVE + la dernière 
            -- sinon c est la prochaine INACTIVE et future (pour saisie en juin des affect de septembre)
            ,row_number() over (partition by v_aff.nudoss order by v_aff.etat, v_aff.date_deb desc, v_aff.uo) as rnum
            FROM
            (    -- v_aff (hie ou fun si hie recherche) -------------------------------------------------------------------------------------------
                --- affectations HIE hors recherche ou aff FUN HE% enfin structure péda dans OSE
                SELECT distinct
                    v_aff_hie.nudoss
                    ,v_aff_hie.matcle
                    ,v_aff_hie.uid_ldap
                    ,v_aff_hie.qualit
                    ,v_aff_hie.nomuse
                    ,v_aff_hie.prenom
                    ,v_aff_hie.nompat
                    ,v_aff_hie.recrutement
                    ,v_aff_hie.type_emp
                    ,v_aff_hie.tem_fonc
                    ,v_aff_hie.employeur
                    ,v_aff_hie.ville_service_rectorat as orec_ville_service_recto
                    ,v_aff_hie.nb_h_service_rectorat
                    ,v_aff_hie.orec_code_uo
                    ,v_aff_hie.uo
                    ,v_aff_hie.orec_lib_categorie
                    ,v_aff_hie.code_emploi
                    ,v_aff_hie.etat      -- ACTIF / INACTIF    
                    ,v_aff_hie.date_deb
                    ,v_aff_hie.date_fin
                    ,v_aff_hie.cause_depart_def
                    ,'HIE'      as type_aff
                    ,v_aff_hie.type_transfert
                    ,v_aff_hie.orec_type_vac				-- v1.8 - 11/02/2019
					,v_aff_hie.emp_source_code				-- v4.1 11/10/2021	
					,v_aff_hie.sexe							-- v5.0 30/05/22
                    FROM 
                        ( -- v_aff_hie -------------------------------------------------------------------------------------------
                            select i.nudoss
                            ,i.matcle , trim(i.uid_ldap) as uid_ldap
                            ,trim(i.qualit) as qualit ,trim(i.nomuse) as nomuse ,trim(i.prenom) as prenom ,trim(i.nompat) as nompat
                            ,trim(i.recrutement) as recrutement
                            ,i.type_emp, trim(i.tem_fonctionnaire) as tem_fonc    -- infos orec
                            ,i.employeur, i.ville_service_rectorat, i.nb_h_service_rectorat
                            --- UO de niveau supérieur niveau 3 composantes/directions dans Siham cf detail fonction
                            ,OSE.UM_AFFICH_UO_SUP(i.orec_code_uo) as orec_code_uo
                            ,i.orec_lib_categorie
                            ,trim(aff_hie.uo) as uo,  trim(aff_hie.code_emploi) as code_emploi
                            -- etat ACTIF si present ou INACTIF si dossier clos
                            ,case when i.date_depart_def is not null and (i.date_depart_def-1 between aff_hie.date_deb and aff_hie.date_fin) 
                                    and trunc(i.date_depart_def-1) <= p_date_systeme and i.date_depart_def-1 <= p_d_fin_annee_univ then
                                'INACTIF'
                                else aff_hie.etat 
                            end as etat
                            ,case when i.date_depart_def is not null and (i.date_depart_def-1 between aff_hie.date_deb and aff_hie.date_fin) 
                                             and trunc(i.date_depart_def-1) <= p_date_systeme and i.date_depart_def-1 <= p_d_fin_annee_univ then 
                                                i.date_depart_def
                                        else aff_hie.date_deb                                
                            end as date_deb
                            ,case when i.date_depart_def is not null and (i.date_depart_def-1 between aff_hie.date_deb and aff_hie.date_fin)
                                        and trunc(i.date_depart_def-1) <= p_date_systeme and i.date_depart_def-1 <= p_d_fin_annee_univ then 
                                            to_date('31/12/2999','DD/MM/YYYY')
                                    else aff_hie.date_fin                                
                              end as date_fin
                            , -- CAUSE DEPART DEFINITIF renseignee dans UM_TRANSFERT_INDIVIDU
                              case when i.date_depart_def is not null and i.date_depart_def-1 between aff_hie.date_deb and aff_hie.date_fin 
                                            and trunc(i.date_depart_def-1) <= p_date_systeme and i.date_depart_def-1 <= p_d_fin_annee_univ then 
                                            i.cause_depart_def
                                    else ''                                
                             end as cause_depart_def
                            ,i.type_transfert
                            ,i.orec_type_vac                -- v1.8 - 11/02/2019
							,i.emp_source_code				-- v4.1 11/10/2021
							,i.sexe							-- v5.0 30/05/22							
                          from 
                              ---- TABLE SOURCE DES MATRICULES A TRAITER ALIMENTEE PAR PROCEDURE SELECT_INTERVENANT + INFOS OREC
                            OSE.UM_TRANSFERT_INDIVIDU i
                            ,( -- affectation HIE
                                select aff_hie.nudoss, aff_hie.idou00 as uo, trunc(aff_hie.dtef00) as date_deb, trunc(aff_hie.dten00) as date_fin, trim(aff_hie.idjb00) as code_emploi
                                    ,case     when trunc(aff_hie.dtef00) > p_d_fin_annee_univ then 'INACTIF'   -- pas encore là car saisie à l'avance
                                            when trunc(aff_hie.dten00) < p_d_deb_annee_univ then 'INACTIF'   -- période passée
                                      else 'ACTIF'
                                    end as ETAT
                                    ,trim(aff_hie.idps00) as code_poste                                    
                                from hr.zy3b@SIHAM.WORLD aff_hie         -- affectation HIE
                                where aff_hie.nudoss = p_nudoss
                                -- ##A_PERSONNALISER_CHOIX_SIHAM##
                                and trim(aff_hie.idou00) not in (v_uo_a_exclure) 	-- v5.0 17/06/22 voir remplissage variable
                                and ( -- affectations sur annee univ
                                      ( trunc(aff_hie.dtef00) <= p_d_fin_annee_univ
                                        and trunc(aff_hie.dtef00) <= p_date_systeme
                                        -- v0.2 le 12/04/2018
                                        and trunc(aff_hie.dtef00) <= add_months(p_d_fin_annee_univ,12)
                                        and trunc(aff_hie.dten00) >= p_d_deb_annee_univ
                                      ) 
                                      -- ##A_PERSONNALISER_CHOIX_SIHAM## : ou affectation future si aucune sur annee univ (demandé par notre DRH pour planning)
                                      or ( --aff_hie.dtef00 >=  p_d_fin_annee_univ and
                                            trunc(aff_hie.dtef00) > p_date_systeme
                                            and not exists (
                                                    select 1
                                                    from hr.zy3b@SIHAM.WORLD         -- affectation HIE
                                                    where nudoss = p_nudoss
                                                    -- ##A_PERSONNALISER_CHOIX_SIHAM##
                                                    and trim(idou00) not in (v_uo_a_exclure) 	-- v5.0 17/06/22 voir remplissage variable
                                                    and ( trunc(dtef00) <= p_d_fin_annee_univ
                                                            and trunc(dtef00) <= p_date_systeme
                                                            and trunc(dten00) >= p_d_deb_annee_univ
                                                           )
                                                                    
                                            )
                                        )
                                    )
                            ) aff_hie
                          where 
                            i.annee_id = p_annee_id  -- v1.14
                            and i.matcle = p_siham_matricule  --v1.14b
                            and i.nudoss = aff_hie.nudoss
                            -- v1.10- 11/04/2019 -- exclure si en détachement sauf si dossier présent dans orec -- ##A_PERSONNALISER_CHOIX_SIHAM## 'HZP0000003' code UO pour hors établ
                            and (aff_hie.uo <> 'HZP0000003' 
                                or (aff_hie.uo = 'HZP0000003' and i.orec_type_vac is not null)
                                )
                        ) v_aff_hie
                UNION ALL
                --- affectations HIE recherche => prendre la 1ere affect FUN HE% : structure pédagogique dans ose
                SELECT distinct
                    v_aff_fun.nudoss
                    ,v_aff_fun.matcle
                    ,v_aff_fun.uid_ldap
                    ,v_aff_fun.qualit
                    ,v_aff_fun.nomuse
                    ,v_aff_fun.prenom
                    ,v_aff_fun.nompat
                    ,v_aff_fun.recrutement
                    ,v_aff_fun.type_emp
                    ,v_aff_fun.tem_fonc
                    ,v_aff_fun.employeur
                    ,v_aff_fun.ville_service_rectorat as orec_ville_service_recto
                    ,v_aff_fun.nb_h_service_rectorat
                    ,v_aff_fun.orec_code_uo
                    ,v_aff_fun.uo
                    ,v_aff_fun.orec_lib_categorie
                    ,v_aff_fun.code_emploi                    
                    ,v_aff_fun.etat      -- ACTIF / INACTIF    
                    ,v_aff_fun.date_deb
                    ,v_aff_fun.date_fin
                    ,v_aff_fun.cause_depart_def        
                    ,'FUN'
                    ,v_aff_fun.type_transfert
                    ,v_aff_fun.orec_type_vac                        -- v1.8 - 11/02/2019
					,v_aff_fun.emp_source_code						-- v4.1 11/10/2021
					,v_aff_fun.sexe									-- v5.0 30/05/22
                FROM 
                ( -- v_aff_fun -------------------------------------------------------------------------------------------
                    select i.nudoss
                    ,i.matcle ,i.uid_ldap
                    ,i.qualit ,i.nomuse ,i.prenom ,i.nompat
                    ,trim(i.recrutement) as recrutement
                    ,i.type_emp, i.tem_fonctionnaire as tem_fonc    -- infos orec
                    ,i.employeur, i.ville_service_rectorat, i.nb_h_service_rectorat
                    ,OSE.UM_AFFICH_UO_SUP(i.orec_code_uo) as orec_code_uo
                    ,i.orec_lib_categorie
                    ,aff_fun.uo, aff_hie.code_emploi
                    -- etat et dates revues si depart def
                    ,case when i.date_depart_def is not null and (i.date_depart_def-1 between aff_fun.date_deb and aff_fun.date_fin) 
                                    and trunc(i.date_depart_def-1) <= p_date_systeme and i.date_depart_def-1 <= p_d_fin_annee_univ then
                                'INACTIF'
                                else aff_fun.etat 
                    end as etat
                    ,case when i.date_depart_def is not null and (i.date_depart_def-1 between aff_fun.date_deb and aff_fun.date_fin) 
                                     and trunc(i.date_depart_def-1) <= p_date_systeme and i.date_depart_def-1 <= p_d_fin_annee_univ then 
                                        i.date_depart_def
                                else aff_fun.date_deb                                
                    end as date_deb
                    ,case when i.date_depart_def is not null and (i.date_depart_def-1 between aff_fun.date_deb and aff_fun.date_fin)
                                and trunc(i.date_depart_def-1) <= p_date_systeme and i.date_depart_def-1 <= p_d_fin_annee_univ then 
                                    to_date('31/12/2999','DD/MM/YYYY')
                            else aff_fun.date_fin                                
                      end as date_fin
                    , -- CAUSE DEPART DEF renseignee meme si inactif
                      case when i.date_depart_def is not null and i.date_depart_def-1 between aff_fun.date_deb and aff_fun.date_fin 
                                    and trunc(i.date_depart_def-1) <= p_date_systeme and i.date_depart_def-1 <= p_d_fin_annee_univ then 
                                    i.cause_depart_def
                            else ''                                
                      end as cause_depart_def
                    ,i.type_transfert
                    ,i.orec_type_vac                        -- v1.8 - 11/02/2019
					,i.emp_source_code						-- v4.1 11/10/2021
					,i.sexe							-- v5.0 30/05/22
                  from 
                    ---- TABLE SOURCE DES MATRICULES A TRAITER DANS UM_TRANSFERT_INDIVIDU + INFOS OREC
                    OSE.UM_TRANSFERT_INDIVIDU i
                    ,( select aff_hie.nudoss, trunc(aff_hie.dtef00) as date_deb, trunc(aff_hie.dten00) as date_fin, trim(aff_hie.idjb00) as code_emploi
                        ,trim(aff_hie.idps00) as code_poste
                        from hr.zy3b@SIHAM.WORLD aff_hie        -- affectation HIE
                        -- ##A_PERSONNALISER_CHOIX_SIHAM## suivant codage affectation recherche : HR
                        where aff_hie.nudoss = p_nudoss
                            and (aff_hie.idou00 like 'HR%' or aff_hie.idou00 like 'HFM%' or aff_hie.idou00 like 'HX%'  --v2.3b 27/04/21
                                    --or (aff_hie.idou00 = 'HXC0B00005' and aff_hie.nudoss in (152599, 161861))  -- v2.3 19/04/2021 test en dur
                                 )  -- v1.8c MUSE comme HR%
                    ) aff_hie
                    ,( -- aff_fun -------------------------------------------------------------------------------------------
                        (     -- affectation FUN normale saisie dans siham - affectation 
                            select aff_fun.nudoss , aff_fun.idou00 as uo, trunc(aff_fun.dtef00) as date_deb, trunc(aff_fun.dten00) as date_fin
                                    ,case when trunc(aff_fun.dtef00) >= p_d_fin_annee_univ then 'INACTIF'  -- pas encore là car saisie à l'avance
                                    when trunc(aff_fun.dten00) < p_d_deb_annee_univ then 'INACTIF'   -- période passée
                                      else 'ACTIF'
                                    end as ETAT
                                from hr.zy3c@SIHAM.WORLD aff_fun  -- affectation FUN
                                where aff_fun.nudoss = p_nudoss
                                    and aff_fun.tytrst = 'FUN' 
                                    and substr(aff_fun.idou00,1,3) in
                                        ( --- structures pédagogiques dans ose -- ##A_PERSONNALISER_CHOIX_OSE## suivant les uo existantes dans OSE table UM_STRUCTURE
                                          select distinct substr(source_code,1,3) as deb_uo
                                          from OSE.UM_STRUCTURE where niveau =2
                                        )
                                    and ( -- affectations sur annee univ
                                              ( trunc(aff_fun.dtef00) <= p_d_fin_annee_univ
                                                and trunc(aff_fun.dtef00) <= p_date_systeme
                                                -- v0.2 le 12/04/2018
                                                and trunc(aff_fun.dtef00) <= add_months(p_d_fin_annee_univ,12)
                                                and trunc(aff_fun.dten00) >= p_d_deb_annee_univ
                                              ) -- ou affectation future si aucune sur annee univ
                                              or 
                                              -- ##A_PERSONNALISER_CHOIX_SIHAM## : ou affectation future si aucune sur annee univ (demandé par notre DRH pour planning)
                                              ( trunc(aff_fun.dtef00) > p_date_systeme
                                                and not exists (
                                                        select 1
                                                        from hr.zy3c@SIHAM.WORLD -- affectation FUN
                                                        where nudoss = p_nudoss
                                                        and tytrst = 'FUN' 
                                                        and substr(aff_fun.idou00,1,3) in
                                                        ( --- structures péda dans ose 
                                                          select distinct substr(source_code,1,3) as deb_uo
                                                          from OSE.UM_STRUCTURE where niveau =2
                                                            )
                                                        and ( trunc(dtef00) <= p_d_fin_annee_univ
                                                                and trunc(dtef00) <= p_date_systeme
                                                                and trunc(dten00) >= p_d_deb_annee_univ
                                                           )    
                                                        )
                                                )
                                        )
                        )
                    
                        union  
                        ( -- ##A_PERSONNALISER_CHOIX_SIHAM## : v1.7 - 31/01/2019 aff_fun liee aux fonctions MCE pour les HB 100 pour cent recherche et CONV_MCE
                        -- Pour les CONV_MCE qui n ont que des structures recherche dans Aff HIE + FUN 
                        -- on recupere l UO FUN LIEE a la fonction MCE si coche HERITEE non remplie
                            select distinct 
                            aff_fc.nudoss
                            ,aff_fc.uoliee
                            ,trunc(aff_fc.datdeb)             as datdeb
                            ,trunc(aff_fc.datfin)             as datfin
                            ,case when trunc(aff_fc.datdeb) >= p_d_fin_annee_univ then 'INACTIF'  -- pas encore là car saisie à l'avance
                                when trunc(aff_fc.datfin) < p_d_deb_annee_univ then 'INACTIF'    -- période passée
                                  else 'ACTIF'
                                end as ETAT
                        from 
                            hr.zyv1@SIHAM.WORLD aff_fc,
                            hr.zd00@SIHAM.WORLD reg_fc,         -- reglementation
                            hr.zd01@SIHAM.WORLD lib_reg_fc,
                            hr.zd08@SIHAM.WORLD comm_fc,          -- commentaire sur codes
                            hr.zd00@SIHAM.WORLD reg_typ_fc,
                            hr.zd01@SIHAM.WORLD lib_reg_typ_fc
                        where 
                            aff_fc.nudoss = p_nudoss
                            -- fonction MCE AVEC UO LIEE
                            and aff_fc.foncti = reg_fc.cdcode
                            and reg_fc.cdstco='ITG'
                            -- ##A_PERSONNALISER_CHOIX_OSE##  suivant codage fonction avec mission d'enseignement UPD2 ou UD... (sauf UPD1)
                            and aff_fc.typfon in ('UPD','UME') -- v1.13
                            and trim(aff_fc.foncti) <> 'UPD1'
                            and reg_fc.nudoss = lib_reg_fc.nudoss
                            and reg_fc.nudoss = comm_fc.nudoss(+)
                            -- type_fonction
                            and trim(aff_fc.typfon) = trim(reg_typ_fc.cdcode)
                            and reg_typ_fc.cdstco='VA1'
                            and reg_typ_fc.nudoss = lib_reg_typ_fc.nudoss
                        
                            and ( (trunc(aff_fc.datfin) >= p_d_deb_annee_univ
                                    and trunc(aff_fc.datdeb) <= p_d_fin_annee_univ
                                    and trunc(aff_fc.datdeb) <= p_date_systeme
                                    -- v1.3 - 12/10/2018 - MYP - fonctions arretant en cours d annee
                                    -- v1.12- 02/09/2019 test ou =
                                    and ( trunc(aff_fc.datfin) >= p_date_systeme
                                      and p_date_systeme <= p_d_fin_annee_univ )
                                   )
                                   or
                                   -- ##A_PERSONNALISER_CHOIX_SIHAM## : ou affectation future si aucune sur annee univ (demandé par notre DRH pour planning)
                                   ( trunc(aff_fc.datdeb) > p_date_systeme
                                     and not exists (select 1
                                            from hr.zyv1@SIHAM.WORLD aff_fc,
                                                hr.zd00@SIHAM.WORLD reg_fc, -- reglementation
                                                hr.zd01@SIHAM.WORLD lib_reg_fc,
                                                hr.zd00@SIHAM.WORLD reg_typ_fc,
                                                hr.zd01@SIHAM.WORLD lib_reg_typ_fc
                                            where aff_fc.nudoss = p_nudoss
                                            -- fonction
                                            and aff_fc.foncti = reg_fc.cdcode
                                            and reg_fc.cdstco='ITG'
                                            and aff_fc.typfon in ('UPD','UME')  -- v1.13 - ##A_PERSONNALISER_CHOIX_SIHAM##
                                            and reg_fc.nudoss = lib_reg_fc.nudoss
                                            -- type_fonction
                                            and trim(aff_fc.typfon) = trim(reg_typ_fc.cdcode)
                                            and reg_typ_fc.cdstco='VA1'
                                            and aff_fc.typfon in ('UPD','UME')  -- v1.13  - ##A_PERSONNALISER_CHOIX_SIHAM##
                                            and reg_typ_fc.nudoss = lib_reg_typ_fc.nudoss
                                            and trunc(aff_fc.datfin) >= p_d_deb_annee_univ
                                            and trunc(aff_fc.datdeb) <= p_d_fin_annee_univ
                                            -- v1.3 - 12/10/2018 - MYP - fonction s arretant en cours d annee
                                            -- v1.12- 02/09/2019 test ou =
                                            and ( trunc(aff_fc.datfin) >= p_date_systeme
                                                and p_date_systeme <= p_d_fin_annee_univ )
                                            )
                                   )
                            )
                            and trim(aff_fc.uoliee) is not null
                            -- coche HERITEE non remplie cad pas heritee d un poste donc sur emploi uo 
                            and aff_fc.flhr00 = 0
            
                        )    
                    ) aff_fun
                  where 
                    i.annee_id = p_annee_id  --v1.14
                    and i.matcle = p_siham_matricule  -- v1.14b
                    and i.nudoss = aff_hie.nudoss
                    and aff_hie.nudoss = aff_fun.nudoss
                    and ( -- debut aff_fun dans periode de l'aff_hie
                            to_char(aff_fun.date_deb,'YYYY-MM-DD') between to_char(aff_hie.date_deb,'YYYY-MM-DD') and to_char(aff_hie.date_fin,'YYYY-MM-DD')
                        or ( -- debut aff_fun avant debut aff_hie et fin aff_fun apres debut aff_hie
                            to_char(aff_fun.date_deb,'YYYY-MM-DD') < to_char(aff_hie.date_deb,'YYYY-MM-DD') 
                            and to_char(aff_fun.date_fin,'YYYY-MM-DD') >= to_char(aff_hie.date_deb,'YYYY-MM-DD'))
                        )
                ) v_aff_fun
            ) v_aff
        ) v_aff_princ
        ,hr.zc00@SIHAM.WORLD             emp        -- emplois
        ,hr.zc01@SIHAM.WORLD             emp_lib -- libelle
        -- v1.6c
        where v_aff_princ.rnum = 1
            and trim(v_aff_princ.code_emploi) = trim(emp.idjb00)
            and emp.nudoss = emp_lib.nudoss(+)
    ) v_aff_gene
    ,(select id, source_code, trim(libelle_court) as lc_str, trim(libelle_long) as ll_str
          from OSE.UM_STRUCTURE     -- table des UO retenues pour OSE
    ) v_uo_ose
    , OSE.CIVILITE ose_civ
    ,(    ---- v_corps_grade -----------------------------------------------------------------
        SELECT distinct
        v_gr.nudoss
        ,v_gr.corps
        ,v_gr.adecod as grade
        ,dateff
        ,datfin
        ,ose_gr.id as id_grade_ose
        ,v_groupe_hie.groupe_hierarchique  --v1.14b
        ,row_number() over (partition by v_gr.nudoss order by v_gr.dateff desc, v_gr.corps desc, v_gr.datfin) as rnum     
        from
        (    select nudoss
            ,trim(corps)    as corps
            ,trim(adecod)   as adecod
            ,trunc(dateff)     as dateff
            ,trunc(datfin)    as datfin
            from hr.zygr@SIHAM.WORLD         --carriere administrative
            where nudoss = p_nudoss
                -- toutes les périodes sur l'année univ
                and trunc(datfin) >= p_d_deb_annee_univ
                and trunc(dateff) <= p_d_fin_annee_univ
                and trunc(dateff) <= p_date_systeme
                -- v0.8c 24/07/2018
                and trim(corps) <> '000' and trim(adecod) <> '0000'
                -- and numcar = 1    -- carriere normale (pas secondaire) 
            UNION
            select nudoss
            ,decode(trim(adecod),null,'000',corps)
            ,decode(trim(adecod),null,'0000',adecod)
            ,trunc(dateff) as dateff
            ,decode(to_char(datxxx,'YYYY-MM-DD'),'2999-12-31',datxxx, datxxx-1)
            from hr.zyfa@SIHAM.WORLD          -- administration origine
            where nudoss = p_nudoss
                and rtrim(orgori,' ') is null
                -- toutes les périodes sur l'année univ
                and decode(to_char(finpre,'YYYY-MM-DD'),'0001-01-01','2999-12-31', to_char(finpre,'YYYY-MM-DD')) >= to_char(p_d_deb_annee_univ,'YYYY-MM-DD')
                and trunc(dateff) <= p_d_fin_annee_univ
                and trunc(dateff) <= p_date_systeme
                -- v0.8c 24/07/2018
                and trim(adecod) is not null and trim(adecod) is not null
        ) v_gr
        , OSE.UM_GRADE ose_gr
        ,(    ---- v_groupe_hie --------------------------------- -- v1.14b
            select rtrim(g.cdcode,' ') cdcode
            ,trim(h.liblon)     as liblon
            ,i.cdhiec             as groupe_hierarchique
            from hr.zd00@SIHAM.WORLD g,
                hr.zd01@SIHAM.WORLD h,
                hr.zd63@SIHAM.WORLD i
            where g.nudoss = h.nudoss
            and g.nudoss = i.nudoss
            and g.cdstco = 'HJB'
        ) v_groupe_hie -------------------------------------------
        where trunc(datfin) <> trunc(dateff)  -- lignes annulées remplacées
        and v_gr.adecod = trim(ose_gr.source_code(+))
        and v_gr.adecod = v_groupe_hie.cdcode(+)
    ) v_corps_grade
    ,( ---- v_naiss -----------------------------------------------------------------
        select naiss.nudoss, trunc(naiss.datnai) as datnai
            , trim(naiss.comnai) as comnai, upper(trim(naiss.vilnai)) as vilnai
            , trim(naiss.paynai) as paynai, O_pays_naiss.id as id_pays_naiss
            , trim(reg_pnaiss.cdcode) as cdcode, trim(lreg_pnaiss.liblon) as ll_pays_naiss
            , trim(lreg_pnaiss.libabr) as lc_pays_naiss
            -- v0.7 - 15/06/2018 - MYP - format dept naiss   ##A_PERSONNALISER_CHOIX_SIHAM##
            , decode(lpad(nvl(trim(naiss.depnai),'0'), 3, '0'),'004','404', lpad(nvl(trim(naiss.depnai),'0'), 3, '0')) as depnai
            , trim(lreg.liblon) as ll_dep, trim(lreg.libabr) as lc_dep
            , trim(naiss.natemp) as natemp
            , trim(v_nation.nation) as nation
            , O_pays_nat.id as id_pays_nat
        from hr.zy10@SIHAM.WORLD naiss
             ,hr.zd00@SIHAM.WORLD reg              -- reglementation pour dept naissance
             ,hr.zd01@SIHAM.WORLD lreg             -- libelle reglementation
             ,hr.zd00@SIHAM.WORLD reg_pnaiss      -- reglementation pur pays naissance
             ,hr.zd01@SIHAM.WORLD lreg_pnaiss     -- libelle reglementation
             ,( -- v1.8c : nationalité meme si pas principale  -- liste nationalités -- v1.6c
                select nudoss, nation.nation, nation.FLPRCI
                -- v1.9b- 04/04/2019
                ,row_number() over (partition by nudoss order by nation.FLPRCI desc) as rnum
                from hr.zy12@SIHAM.WORLD nation
                where 
                 (nation.FLPRCI =1    -- principale
                    or nation.FLPRCI <>1 and nation.nulign = ( select min(nation2.nulign)
                                                              from hr.zy12@SIHAM.WORLD nation2
                                                              where nation2.nudoss = nation.nudoss
                                                            )
                 )
             ) v_nation
             , OSE.UM_PAYS O_pays_naiss
             , OSE.UM_PAYS O_pays_nat
        where
            -- departement naiss
            naiss.nudoss = p_nudoss
            and trim(naiss.depnai) = trim(reg.cdcode(+))
            and reg.cdstco(+) = 'UGJ'
            and reg.nudoss = lreg.nudoss(+)
            and trim(naiss.paynai) = trim(O_pays_naiss.source_code(+))
            -- pays naissance
            and trim(naiss.paynai) = trim(reg_pnaiss.cdcode(+))
            and reg_pnaiss.cdstco(+) = 'UIN'
            and reg_pnaiss.nudoss = lreg_pnaiss.nudoss(+)
            and lreg_pnaiss.cdlang(+) = 'F'
            -- pays nationalite -- v1.6c
            and naiss.nudoss = v_nation.nudoss(+)   -- v1.8c criteres reportés dans vue nation
            and v_nation.nation = trim(O_pays_nat.source_code(+))
            --and naiss.natemp = trim(O_pays_nat.source_code(+))
            -- v1.9b- 04/04/2019
            and v_nation.rnum(+) = 1
     ) v_naiss
    ,(    ---- v_tel -----------------------------------------------------------------
        select 
            nudoss
            -- ##A_PERSONNALISER_CHOIX_SIHAM## suivant codage tél
            ,trim(TRANSLATE(upper(trim(max(decode(typtel,'TPR', numtel,'')))), '? -_./@ABCDEFGHIJKLMNOPQRSTUVWXYZ', ' ' )) as tel_pro            
            ,trim(TRANSLATE(upper(trim(max(decode(typtel,'TPE', numtel,'')))), '? -_./@ABCDEFGHIJKLMNOPQRSTUVWXYZ', ' ' )) as tel_perso
            ,trim(TRANSLATE(upper(trim(max(decode(typtel,'PPR', numtel,'')))), '? -_./@ABCDEFGHIJKLMNOPQRSTUVWXYZ', ' ' )) as tel_mobile_pro
            ,trim(TRANSLATE(upper(trim(max(decode(typtel,'PPE', numtel,'')))), '? -_./@ABCDEFGHIJKLMNOPQRSTUVWXYZ', ' ' )) as tel_mobile_perso
            ,trim(max(decode(typtel,'MPR', numtel,''))) as mail_pro
            ,trim(max(decode(typtel,'MPE', numtel,''))) as mail_perso
        from hr.zy0h@SIHAM.WORLD
        where nudoss = p_nudoss
            -- ##A_PERSONNALISER_CHOIX_SIHAM## suivant type de coordonnées personnelles
            and typtel in ('TPR','TPE','PPR','PPE','MPR','MPE')
         -- and nudoss = 105905
        group by nudoss
     ) v_tel
    ,(    ---- v_statut -----------------------------------------------------------------
        select st.nudoss
            ,trim(st.statut)     as statut_pip
            ,trim(lreg.liblon)     as lib_statut
            ,trunc(st.dateff)    as dateff
            ,trunc(st.datxxx-1) as datfin        --v1.15b
            ,ens.enseig         as tem_enseig
            ,''                 as id_grade_ose -- v1.10 
            ,row_number() over (partition by st.nudoss order by st.dateff desc) as rnum 
            from hr.zyfl@SIHAM.WORLD st        -- statut pip
            ,hr.zd00@SIHAM.WORLD reg          -- reglementation
            ,hr.zd01@SIHAM.WORLD lreg            -- libelle reglementation
            ,hr.zdvp@SIHAM.WORLD ens            -- recup temoin enseig
            --, OSE.UM_GRADE ose_gr -- v1.10 
        where st.nudoss = p_nudoss
        and st.statut <> '00000'
        -- reglementation HJ8
        and st.statut = reg.cdcode
        and reg.cdstco='HJ8'
        and reg.nudoss=lreg.nudoss
        and reg.nudoss = ens.nudoss(+)
        -- and trim(st.statut) = trim(ose_gr.source_code(+)) -- v1.10 
        -- statut actif à p_date_systeme
        and ((    trunc(st.datxxx-1) >= p_d_deb_annee_univ
                and trunc(st.dateff) <= p_d_fin_annee_univ
                and trunc(st.dateff) <= p_date_systeme
                -- v1.3 - 12/10/2018 - MYP - fonction s arretant en cours d annee
                -- and ( trunc(st.datxxx-1) >= p_date_systeme -- v1.15 04/11/2019 sinon pb si aucun statut en cours à date systeme
                -- v1.12- 02/09/2019 - MYP - Pb recup statut_pip de siham dans v_statut : test p_date_systeme <= p_d_fin_annee_univ
                  and p_date_systeme <= p_d_fin_annee_univ --)
             )
            or -- ##A_PERSONNALISER_CHOIX_SIHAM## : ou futur si aucun sur annee univ (demandé par notre DRH pour planning)
            ( trunc(st.dateff) > p_date_systeme
              and not exists (select 1
                            from hr.zyfl@SIHAM.WORLD st
                                ,hr.zd00@SIHAM.WORLD reg 
                            where st.nudoss = p_nudoss
                            and st.statut <> '00000'
                            -- reglementation HJ8
                            and st.statut = reg.cdcode
                            and reg.cdstco='HJ8'
                            -- statut actif à p_date_systeme
                            and trunc(st.datxxx-1) >= p_d_deb_annee_univ
                            and trunc(st.dateff) <= p_d_fin_annee_univ
                            and trunc(st.dateff) <= p_date_systeme
                            -- v1.3 - 12/10/2018 - MYP - fonction s arretant en cours d annee
                            -- and ( trunc(st.datxxx-1) >= p_date_systeme -- v1.15 04/11/2019 sinon pb si aucun statut en cours à date systeme
                            -- v1.12- 02/09/2019 - MYP - Pb recup statut_pip de siham dans v_statut : test p_date_systeme <= p_d_fin_annee_univ
                                and p_date_systeme <= p_d_fin_annee_univ --)
                            )
            )
        )
    ) v_statut
    ,(    ---- v_nss -----------------------------------------------------------------
        select tous_nss.nudoss, 
            trim(max(decode(tous_nss.type_insee,'DEFI',tous_nss.no_insee,''))) as insee_def
            ,trim(max(decode(tous_nss.type_insee,'DEFI',tous_nss.cle_insee,''))) as cle_insee_def
            ,trim(max(decode(tous_nss.type_insee,'PROV',tous_nss.no_insee,''))) as insee_prov
            ,trim(max(decode(tous_nss.type_insee,'PROV',tous_nss.cle_insee,''))) as cle_insee_prov
        from
        ( 
            select a.nudoss, 'PROV' as type_insee, substr(trim(a.seepro),1,13) no_insee, substr(trim(a.seepro),14,2) cle_insee
             from hr.zyfx@SIHAM.WORLD a -- insee_prov
         where a.nudoss = p_nudoss
            and a.seepro <> '               '
            --and a.nudoss = '144829' 
         union
            select a.nudoss, 'DEFI' as type_insee, trim(a.sssexe||lpad(to_char(a.ssanne),2,'0')||lpad(a.ssmois,2,'0')||a.ssdept||a.sscomm||a.ssnord) as no_insee, trim(a.ssclef) as cle_insee -- v2.2c 01/03/2021
            from hr.zyff@SIHAM.WORLD a
            where a.nudoss = p_nudoss
        ) tous_nss
        group by tous_nss.nudoss
    ) v_nss
    ,(  ---- v_fonction -----------------------------------------------------------------
        select distinct 
            aff_fc.nudoss
            ,trunc(aff_fc.datdeb)             as datdeb
            ,trunc(aff_fc.datfin)             as datfin
            ,trim(aff_fc.typfon)               as type_fonction
            ,trim(lib_reg_typ_fc.liblon)       as lib_type_fonction
            ,trim(aff_fc.foncti)               as code_fonction
            ,trim(lib_reg_fc.liblon)           as lib_fonction
            ,trim(comm_fc.txcomm)           as commentaire
            ,comm_fc.numord                    as nb_heure_mce
            ,row_number() over (partition by aff_fc.nudoss order by aff_fc.datdeb desc, decode(aff_fc.foncti,'UPD2',1,0) desc, aff_fc.datfin desc) as rnum            
        from 
            hr.zyv1@SIHAM.WORLD aff_fc,
            hr.zd00@SIHAM.WORLD reg_fc,         -- reglementation
            hr.zd01@SIHAM.WORLD lib_reg_fc,
            hr.zd08@SIHAM.WORLD comm_fc,          -- commentaire sur codes
            hr.zd00@SIHAM.WORLD reg_typ_fc,
            hr.zd01@SIHAM.WORLD lib_reg_typ_fc
        where aff_fc.nudoss = p_nudoss
            -- fonction
            and aff_fc.foncti = reg_fc.cdcode
            and reg_fc.cdstco='ITG'
            -- ##A_PERSONNALISER_CHOIX_SIHAM## suivant codage fonction avec mission enseignement
            and aff_fc.typfon in ('UPD','UME')  -- v1.13
            and trim(aff_fc.foncti) <> 'UPD1'
            and reg_fc.nudoss = lib_reg_fc.nudoss
            and reg_fc.nudoss = comm_fc.nudoss(+)
            -- type_fonction
            and trim(aff_fc.typfon) = trim(reg_typ_fc.cdcode)
            and reg_typ_fc.cdstco='VA1'
            and reg_typ_fc.nudoss = lib_reg_typ_fc.nudoss
        
            and ( (trunc(aff_fc.datfin) >= p_d_deb_annee_univ
                    and trunc(aff_fc.datdeb) <= p_d_fin_annee_univ
                    and trunc(aff_fc.datdeb) <= p_date_systeme
                    -- v1.3 - 12/10/2018 - MYP - fonction s arretant en cours d annee
                    -- v1.12- 02/09/2019 test ou =
                    and  trunc(aff_fc.datfin) >= p_date_systeme  -- v1.15 04/11/2019 sinon pb si aucune fc en cours à date systeme -- v2.1 réactivation
                      and p_date_systeme <= p_d_fin_annee_univ  --)
                   )
                   or -- ##A_PERSONNALISER_CHOIX_SIHAM## : ou futur si aucun sur annee univ (demandé par notre DRH pour planning)
                   ( trunc(aff_fc.datdeb) > p_date_systeme
                     and not exists (select 1
                            from hr.zyv1@SIHAM.WORLD aff_fc,
                                hr.zd00@SIHAM.WORLD reg_fc, -- reglementation
                                hr.zd01@SIHAM.WORLD lib_reg_fc,
                                hr.zd00@SIHAM.WORLD reg_typ_fc,
                                hr.zd01@SIHAM.WORLD lib_reg_typ_fc
                            where aff_fc.nudoss = p_nudoss
                            -- fonction
                            and aff_fc.foncti = reg_fc.cdcode
                            and reg_fc.cdstco='ITG'
                            and aff_fc.typfon in ('UPD','UME')  -- v1.13  -- ##A_PERSONNALISER_CHOIX_SIHAM##
                            and reg_fc.nudoss = lib_reg_fc.nudoss
                            -- type_fonction
                            and trim(aff_fc.typfon) = trim(reg_typ_fc.cdcode)
                            and reg_typ_fc.cdstco='VA1'
                            and aff_fc.typfon in ('UPD','UME')  -- v1.13  -- ##A_PERSONNALISER_CHOIX_SIHAM##
                            and reg_typ_fc.nudoss = lib_reg_typ_fc.nudoss
                            and trunc(aff_fc.datfin) >= p_d_deb_annee_univ
                            and trunc(aff_fc.datdeb) <= p_d_fin_annee_univ
                            -- v1.3 - 12/10/2018 - MYP - fonction s arretant en cours d annee
                            -- v1.12- 02/09/2019 test ou =
                            and trunc(aff_fc.datfin) >= p_date_systeme -- v1.15 04/11/2019 sinon pb si aucun statut en cours à date systeme -- v2.1 réactivation
                                and p_date_systeme <= p_d_fin_annee_univ --)
                            )
                   )
            )
        and trunc(aff_fc.datfin) > add_months(p_d_deb_annee_univ,1)-1  -- v1.15b MCE plus d'un mois : évite de détecter le mois de fin des DOC_MCE
    ) v_fonction
    ,( ---- v_banque -----------------------------------------------------------------
        select bq.nudoss
        ,trim(bq.cpiban)     as iban
        ,trim(bq.swift)     as bic
        ,trunc(bq.datdeb)      as datdeb
        ,case when trim(d.liblon) = 'Virement hors SEPA' then 1 else 0 end as rib_hors_sepa     -- v3.0 07/01/2021
        ,row_number() over (partition by bq.nudoss order by bq.datdeb desc) as rnum
        from hr.zy0i@SIHAM.WORLD bq,         -- coord bancaires
            hr.ZD00@SIHAM.WORLD c,         -- reglementation        -- v3.0 07/01/2021
            hr.ZD01@SIHAM.WORLD d                                    -- v3.0 07/01/2021
        where bq.nudoss = p_nudoss
            and bq.modpai= c.CDCODE 
            and c.cdstco = 'DRN'
            and c.nudoss = d.nudoss
            and d.cdlang = 'F'
    ) v_banque
    ,(  ---- v_position admin ---------------------------------------------------------
    SELECT v_pos.nudoss
    ,v_pos.datdeb
    ,v_pos.datfin
    ,v_pos.code_position
    ,v_pos.ll_position
    ,row_number() over (partition by v_pos.nudoss order by v_pos.datdeb desc, v_pos.datfin desc) as rnum
   FROM
   ( select distinct b.nudoss
        ,trunc(b.dateff)   as datdeb
        ,trunc(b.datxxx-1) as datfin
        ,trim(b.SITCOD) as code_position
        ,trim(d.liblon) as ll_position
        from 
        hr.zypo@SIHAM.WORLD b,
        hr.ZD00@SIHAM.WORLD c, 
        hr.ZD01@SIHAM.WORLD d,
        hr.zy1s@SIHAM.WORLD f,
        hr.ZD00@SIHAM.WORLD g,
        hr.ZD01@SIHAM.WORLD h 
        where b.nudoss = p_nudoss
        and b.SITCOD <> 'FINAC'
        and b.nudoss = f.nudoss
        and trunc(f.dtef1s)   <= trunc(b.datxxx-1)
        and trunc(f.datxxx-1) >= trunc(b.dateff)
        and trunc(b.dateff)   <= p_d_fin_annee_univ
        and trunc(b.datxxx-1) >= p_d_deb_annee_univ
        and trunc(b.dateff)   <= p_date_systeme
        and b.SITCOD=c.CDCODE 
        and c.cdstco = 'HKK'
        and c.nudoss=d.nudoss
        and d.cdlang = 'F'
        and rtrim(f.CGSTAT,' ') = rtrim(g.CDCODE,' ')
        and g.cdstco = 'UAJ'
        and g.nudoss=h.nudoss
        and h.cdlang = 'F'
        ) v_pos
    order by v_pos.nudoss
    ) v_position
    ,(  ---- v_specialite ------------------------------------------------------
    SELECT v_spe.nudoss, trim(v_spe.type_specialite) as type_specialite
        , v_spe.datdeb, v_spe.datfin
        , trim(v_spe.specialite) as specialite
        , trim(v_spe.discipline) as discipline
    ,row_number() over (partition by v_spe.nudoss order by v_spe.datdeb desc, v_spe.datfin desc) as rnum
    from (    SELECT spe.nudoss, trim(spe.typspe) AS type_specialite, trunc(spe.datdeb) AS datdeb
                    , trunc(spe.datfin) AS datfin, trim(spe.specia) AS specialite, trim(spe.discip) AS discipline
            FROM hr.zyvs@SIHAM.WORLD spe
            WHERE spe.nudoss = p_nudoss
                and trunc(spe.datdeb) <= add_months(p_d_fin_annee_univ,12)
                and trunc(spe.datfin) >= p_d_deb_annee_univ
                and trim(spe.typspe) <> '000'
        ) v_spe
    ) v_special
    ,(  ---- v_modserv ------------------------------------------------------
        select 
    v_ms.matcle
    ,v_ms.nudoss
    ,v_ms.codtra       as code_modserv  -- code temps incomplet
    ,v_ms.quotite_remu_um               -- quotite temps incomplet
    ,v_ms.lib_tps
    ,v_ms.RNE_partage   -- rne partage avec rectorat
    ,v_ms.lib_etabl
    ,v_ms.cdpost
    ,v_ms.ville
    ,v_ms.ville_service_rectorat
    ,v_ms.dateff
    , v_ms.datxxx as datfin
    -- v1.10 12/04/2019 : gestion témoin services partagés rectorat
    ,v_ms.tem_partage_rectorat
    ,row_number() over (partition by v_ms.nudoss order by v_ms.dateff desc, v_ms.lib_etabl) as rnum
    from (select distinct
        trim(i.matcle) as matcle
        ,a.nudoss
        ,trim(a.codtra) as codtra
        ,a.numera
        ,a.denomi
        ,round(a.numera*(100/a.denomi),0) as quotite_remu_um
        ,trim(lrep1.liblon) lib_tps
        ,trunc(a.dateff) as dateff
        ,trunc(a.datxxx) as datxxx
        ,trim(lieu.IDWKLO) RNE_partage
        ,trunc(lieu.dtef00)
        -- ##A_PERSONNALISER_CHOIX_SIHAM## suivant code RNE
        ,case when trim(lieu.IDWKLO) in (v_org_rattach) then ''  -- v5.1 20/07/22
            else trim(lrep2.liblon)
        end as lib_etabl
        ,case when trim(lieu.IDWKLO) in (v_org_rattach) then ''  -- v5.1 20/07/22
            else trim(adr.cdpost)
        end as cdpost
        ,case when trim(lieu.IDWKLO) in (v_org_rattach) then ''   -- v5.1 20/07/22
            else substr(adr.zonadd, length(trim(adr.cdpost))+2,length(trim(adr.zonadd))-length(trim(adr.cdpost))+2)
        end as ville
        ,case when trim(lieu.IDWKLO) in (v_org_rattach) then ''  -- v5.1 20/07/22
            else trim(lrep2.liblon)||'#'||trim(adr.cdpost)||'#'||substr(adr.zonadd, length(trim(adr.cdpost))+2,length(trim(adr.zonadd))-length(trim(adr.cdpost))+2)
        end as ville_service_rectorat
        -- v1.10 12/04/2019 : Temps incomplet 50% et RNE de lieu de travail autre que UM
        ,case when trim(a.codtra) = 'TI050' and trim(lieu.IDWKLO) not in (v_org_rattach) then 'O'  -- v5.1 20/07/22
            else 'N'
        end as tem_partage_rectorat
        from
            hr.zytl@SIHAM.WORLD a,    -- modalite service
            hr.zd00@SIHAM.WORLD rep1,
            hr.zd01@SIHAM.WORLD lrep1,
            hr.zy00@SIHAM.WORLD i,
            hr.zy39@SIHAM.WORLD lieu,  -- lieu de travail 
            hr.zd00@SIHAM.WORLD rep2,
            hr.zd01@SIHAM.WORLD lrep2,
            hr.zd0F@SIHAM.WORLD adr
        where a.nudoss = p_nudoss
        and trunc(a.datxxx-1) >= p_d_deb_annee_univ
        and trunc(a.dateff)   <= p_d_fin_annee_univ
        and trunc(a.dateff)   <= p_date_systeme        -- v2.2 - 21/07/2020 - MYP - test <= p_date_systeme sinon ne remonte pas modserv
        and ( 
                (    a.codtra like 'TI0%'                  -- ##A_PERSONNALISER_CHOIX_SIHAM## suivant codage TI Temps Incomplet
                    and a.codtra = rep1.cdcode
                    and rep1.cdstco = 'UHU'
                    and rep1.nudoss = lrep1.nudoss
                    and a.nudoss = i.nudoss
                    -- rne lieu de travail
                    and a.nudoss = lieu.nudoss
                    and lieu.idwklo not in (v_org_rattach) -- ##A_PERSONNALISER_CHOIX_SIHAM##  --v5.1 20/07/22
                    and trunc(lieu.dten00) >= p_d_deb_annee_univ
                    and trunc(lieu.dtef00) <= p_d_fin_annee_univ
                    and lieu.idwklo = rep2.cdcode
                    and rep2.cdstco = 'DRE'
                    and rep2.nudoss = lrep2.nudoss
                    and rep2.nudoss = adr.nudoss
                ) 
                or (
                    a.codtra = rep1.cdcode
                    and rep1.cdstco = 'UHU'
                    and rep1.nudoss = lrep1.nudoss
                    and a.nudoss = i.nudoss
                    -- rne lieu de travail
                    and a.nudoss = lieu.nudoss
                    and lieu.idwklo in (v_org_rattach)        -- ##A_PERSONNALISER_CHOIX_SIHAM##  --v5.1 20/07/22
                    and trunc(lieu.dten00) >= p_d_deb_annee_univ
                    and trunc(lieu.dtef00) <= p_d_fin_annee_univ
                    and lieu.idwklo = rep2.cdcode
                    and rep2.cdstco = 'DRE'
                    and rep2.nudoss = lrep2.nudoss
                    and rep2.nudoss = adr.nudoss
                )
            ) 
        ) v_ms
    ) v_modserv
where  
trim(v_aff_gene.uo_affect_princ) = v_uo_ose.source_code(+)
and v_aff_gene.nudoss = v_corps_grade.nudoss(+) and v_corps_grade.rnum(+) = 1
-- and v_corps_grade.grade = v_groupe_hie.cdcode(+) --v1.14b
and v_aff_gene.nudoss = v_naiss.nudoss(+)
and v_aff_gene.nudoss = v_tel.nudoss(+)
and v_aff_gene.nudoss = v_nss.nudoss(+)    
and v_aff_gene.nudoss = v_banque.nudoss(+)     and v_banque.rnum(+) = 1

-- v1.15b jointure v_statut : dernier statut dans la per d affectation
and v_aff_gene.nudoss = v_statut.nudoss(+)         --and v_statut.rnum(+) = 1
and v_statut.dateff(+) <= v_aff_gene.fin_affect
and v_statut.datfin(+) >= v_aff_gene.deb_affect
and v_statut.rnum(+) = ceil(1)         -- 1er enreg trouvé dont le rnum est le plus proche de 1

--- v1.15b jointure v_fonction : dernière fonction dans la per d affectation
and v_aff_gene.nudoss = v_fonction.nudoss(+)     --and v_fonction.rnum(+) = 1
and v_fonction.datdeb(+) <= v_aff_gene.fin_affect
and v_fonction.datfin(+) >= v_aff_gene.deb_affect
and v_fonction.rnum(+) = ceil(1)     -- 1er enreg trouvé dont le rnum est le plus proche de 1

--- v1.15b jointure v_position : dernière position dans la per d affectation
and v_aff_gene.nudoss = v_position.nudoss(+)     --and v_position.rnum(+) = 1
and v_position.datdeb(+) <= v_aff_gene.fin_affect
and v_position.datfin(+) >= v_aff_gene.deb_affect
and v_position.rnum(+) = ceil(1)     -- 1er enreg trouvé dont le rnum est le plus proche de 1

--- v1.15b jointure v_special : dernière spécialité dans la per d affectation
and v_aff_gene.nudoss = v_special.nudoss(+)     --and v_special.rnum(+) = 1
and v_special.datdeb(+) <= v_aff_gene.fin_affect
and v_special.datfin(+) >= v_aff_gene.deb_affect
and v_special.rnum(+) = ceil(1)     -- 1er enreg trouvé dont le rnum est le plus proche de 1

--- v1.15b jointure v_modserv : dernière modalite de service dans la per d affectation
and v_aff_gene.nudoss = v_modserv.nudoss(+)     --and v_modserv.rnum(+) = 1
and v_modserv.dateff(+) <= v_aff_gene.fin_affect
and v_modserv.datfin(+) >= v_aff_gene.deb_affect
and v_modserv.rnum(+) = ceil(1)     -- 1er enreg trouvé dont le rnum est le plus proche de 1

) V_INTERV,
-- V_ST_INTERV         -- v3.0 statut calculé dans prog principal et non dans Select cf um_affecte_statut
( -------------- BI (LDAP): recup si validation passeport informatique université
    select v_uid, mail, supannempid, date_maj, um_passeport
    from BI_PERS.PERS_2_INTERMEDIAIRE@BI.WORLD
    where um_passeport = '1'
) V_BI_PASSEPORT
WHERE V_INTERV.uid_ldap = V_BI_PASSEPORT.v_uid(+)
;

/*=======================================================================================================*/
/*====================================== PROG PRINCIPAL PROCEDURE =======================================*/
BEGIN
    dbms_output.put_line(' ');
    dbms_output.put_line('   Lancement export intervenants pour OSE : ');
    
    -- ##A_PERSONNALISER_CHOIX_SIHAM## : table UM_PARAM_ETABL -- v3.0 096/03/2021
    select trim(valeur) INTO v_param_gestion_statut     from UM_PARAM_ETABL where code = 'GESTION_STATUT';      -- valeurs possibles : UNIQUE_MANUEL/UNIQUE_AUTO/MULTI_MANUEL/MULTI_AUTO
	select trim(valeur) INTO v_uo_a_exclure 			from UM_PARAM_ETABL where code = 'C_UO_A_EXCLURE';		-- v5.0 17/06/22
	select trim(valeur) INTO v_org_rattach 				from UM_PARAM_ETABL where code = 'C_ORG_RATTACH';		-- v5.1 20/07/22
    
    --*****************************************************************************************
    --**                           INSERT OSE_INTERVENANT                                          **
    --*****************************************************************************************
    dbms_output.put_line('    - insert dans OSE.UM_INTERVENANT ... '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
    FOR cur_pop in cur_ose_insert_intervenant LOOP
        --dbms_output.put_line('   => insert dans OSE.UM_INTERVENANT ... '||cur_pop.matcle);
    
        for c1 in cur_intervenant(cur_pop.matcle, cur_pop.nudoss, p_d_deb_annee_univ, p_d_fin_annee_univ) loop
      
            -- VARIABLES CALCULEES ---------------------------------------------------------- v3.0 08/03/2021 Dans prog principal au lieu de calculer dans SELECT de c1
            OSE.UM_MAJ_INSERT_STATUT(cur_pop.matcle,p_annee_id); -- SI INSERT MULTI-STATUT MANUEL (tem_cvalidation saisi = 'I') : maj date fin période préc.
            -------------
            if c1.W_STRUCTURE_UO like 'HZP%' then v_statut_siham := 'HB001';  -- ##A_PERSONNALISER_CHOIX_SIHAM## TITU sur UO detachement mais dans Orec : faire comme si Hébergé
                else v_statut_siham := c1.W_STATUT_PIP;
            end if; 
            -------------
            v_statut_new := T_UM_ENREG_STATUT(0,'HOSE','',null, null,0);    -- v3.0 08/03/2021 Affecte statut et date debut statut dans un objet T_ENREG_STATUT
            
            if cur_pop.TEM_OSE_INSERT = 'A_INS' then  -- insertion auto du nouveau multi-statut stocké dans UM_SYNCHRO_A_VALIDER
                select OSE.UM_RECUP_NEW_MULTI_STATUT_AUTO(p_annee_id, cur_pop.matcle)     INTO v_statut_new from dual;
            else
                --- OSE.UM_AFFECTE_STATUT affecte à la fois le statut et les dates de période statut
                select OSE.UM_AFFECTE_STATUT(v_statut_siham, c1.W_GROUPE_HIE, c1.W_FONCTION, c1.W_TEM_FONC,
                            c1.W_CODE_EMPLOI, c1.recrutement, c1.W_MODSERV, c1.W_POSITION, c1.W_CORPS, c1.W_OREC_TYPE_VAC,
                            p_d_deb_annee_univ, c1.date_deb_etat, c1.W_DATDEB_POSITION, c1.W_DATEFF_GRADE, c1.W_DATDEB_FC, c1.W_DATEFF_MODSERV,
                            c1.W_DATEFF_STATUT, p_date_systeme, p_d_fin_annee_univ)        INTO v_statut_new from dual;
            end if;
            -------------
            v_ose_mail_pro := c1.mail_pro;        -- ##A_PERSONNALISER_CHOIX_SIHAM## : si pas besoin de tester um_passeport: supprimer V_BI_PASSEPORT et DBLink @BI.WORLD et remonter directement le mail suivant vos règles
            if v_statut_new.code_type_intervenant = 'E' then   -- v3.0  07/01/2021    --- SI IE + passeport non valide : mail pro sinon mail perso 
                    if c1.um_passeport <> '1' then v_ose_mail_pro := c1.mail_perso; end if;
            end if;
            -------------
            v_id_grade_ose := c1.id_grade_ose;        -- ID du GRADE DANS OSE.UM_GRADE
            if v_id_grade_ose is null then -- pour les non titulaires -- v3.2  15/06/21
            
                if ( c1.W_STATUT_PIP like 'HB%' or (c1.W_STATUT_PIP like 'C%' and OSE.UM_EST_CTR_PERM_OU_VAC(c1.W_STATUT_PIP) <> 0) ) then
                
                    if v_statut_new.code_type_intervenant = 'E' then
                        -- ##A_PERSONNALISER_CHOIX_OSE##  -- la lettre permet d'afficher un libellé grade différent dans OSE suivant si Vac ou Perm
                        -- Si V sera couplé avec libelle catégorie OREC dans la vue SRC_...
                        -- if UM_EXISTE_GRADE(c1.W_STATUT_PIP||'V') <> 0 then  -- v3.2  15/06/21
                        v_id_grade_ose:= UM_EXISTE_GRADE(c1.W_STATUT_PIP||'V');      -- VACATAIRE CODE GRADE AVEC V ou sans lettre    
                    else 
                        --if UM_EXISTE_GRADE(c1.W_STATUT_PIP||'P') <> 0 then -- v3.2  15/06/21
                        v_id_grade_ose:= UM_EXISTE_GRADE(c1.W_STATUT_PIP||'P');  -- PERMANENT CODE GRADE AVEC P ou sans lettre
                        --     end if; -- v3.2  15/06/21
                        -- end if; -- v3.2  15/06/21
                    end if;
                else
                    v_id_grade_ose:= UM_EXISTE_GRADE(c1.W_STATUT_PIP);
                end if;
                
            end if;
            -------------
            v_tem_transfert_ose := 'N';  -- TEMOIN DOSSIER A ENVOYER A OSE (dossier concerné par la GSE dans ose)
            if v_statut_new.id = 0 then v_tem_transfert_ose := 'N';                                -- si statut non géré on n'envoie pas le dossier dans ose
            else if v_statut_new.code_type_intervenant = 'P' then v_tem_transfert_ose := 'O';    -- PERM
                 else if c1.tem_present_orec ='O' then v_tem_transfert_ose := 'O';              -- IE et dans OREC
                      else v_tem_transfert_ose := 'N';
                      end if;    
                 end if;
            end if;
            
            -- FIN VARIABLES CALCULEES ----------------------------------------------------------
              
          IF v_tem_transfert_ose = 'N' THEN     -- flag HOSE = HORS OSE non géré dans OSE -- v3.0 inversion test IF/ELSE pour plus clair
            update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_INSERT = 'HOSE' where ose_tr.MATCLE = c1.source_code and ose_tr.annee_id = p_annee_id;  -- v1.14; -- v1.14b -- v3.0
            COMMIT;
          ELSE
            --dbms_output.put_line('    - insert dans OSE.UM_INTERVENANT ... '||v_statut_new.id||' '||v_statut_new.code_statut||' '||v_statut_new.date_deb_statut);
          
            -- v3.2 15/06/2021 rectif date_deb_statut si 1er insert de l'année : début dès le 01/09/N
            if UM_EXISTE_INTERVENANT(p_annee_id, cur_pop.matcle) = 0 then v_statut_new.date_deb_statut := p_d_deb_annee_univ; end if; -- v3.2  15/06/21
          
            -- v1.6 - CAS des DOC MCE avec mission ens se terminant en septembre : on ne les insere pas, on les insèrera plus tard si mce reconduit à partir de octobre
            if ( UM_EST_DOC_MCE(v_statut_new.id) and c1.W_DATFIN_FC < add_months(p_d_deb_annee_univ,1) and c1.tem_present_orec = 'N') then    
                update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_INSERT = 'F_MCE' where ose_tr.MATCLE = c1.source_code and ose_tr.annee_id = p_annee_id; -- v1.14 -- v3.0
                COMMIT;
            else    
                BEGIN
                insert into OSE.UM_INTERVENANT(CIVILITE_ID, NOM_USUEL, PRENOM, NOM_PATRONYMIQUE
                    ,DATE_NAISSANCE, VILLE_NAISSANCE_LIBELLE    -- v3.0  07/01/2021
                    ,TEL_PRO, TEL_MOBILE, EMAIL_PRO             -- v3.0  07/01/2021
                    ,STATUT_ID, STRUCTURE_ID                         
                    ,SOURCE_ID
                    ,SOURCE_CODE
                    ,NUMERO_INSEE, NUMERO_INSEE_CLE, NUMERO_INSEE_PROVISOIRE
                    ,IBAN, BIC
                    ,ANNEE_ID 
                    ,GRADE_ID
                    ,PAYS_NAISSANCE_ID, DEP_NAISSANCE, PAYS_NATIONALITE_ID
                    ,W_STATUT_PIP
                    ,W_TEM_ENSEIG
                    ,W_GROUPE_HIE
                    ,W_CODE_EMPLOI
                    ,W_LIB_EMPLOI
                    ,W_TYPE_FONCTION
                    ,W_FONCTION
                    ,W_STRUCTURE_UO
                    ,W_POSITION
                    ,W_LIB_POSITION
                    ,DATE_DEPART_DEF
                    ,CAUSE_DEPART_DEF
                    ,DATE_HORODATAGE
                    ,EMPLOYEUR
                    ,ville_service_rectorat
                    ,TYPE_EMPLOYEUR
                    ,TYPE_SPECIALITE
                    ,SPECIALITE
                    ,RECRUTEMENT
                    ,QUOTITE_TEMPS_PARTAGE
                    ,OREC_CODE_UO
                    ,NB_H_SERVICE_RECTORAT
                    ,orec_lib_categorie
                    ,W_NB_HEURE_MCE
                    ,RIB_HORS_SEPA                            -- v3.0  07/01/2021
                    ,DATE_DEB_STATUT                        -- v3.0  07/01/2021
                    ,DATE_FIN_STATUT                        -- v3.0  08/03/2021
                    ,DATE_HORODATAGE_STATUT
					,EMP_SOURCE_CODE						-- v4.1 11/10/2021											 
                    ) 
                values (c1.civilite_id, c1.nom_usuel ,c1.prenom ,c1.nom_patronymique
                    ,c1.date_naissance
                    --,c1.ville_naissance_code_insee    -- v3.0  07/01/2021
                    ,c1.ville_naissance_libelle
                    ,c1.ose_tel_pro
                    ,c1.ose_tel_mobile
                    ,v_ose_mail_pro                -- v3.0  08/03/2021
                    ,v_statut_new.id            -- v3.0  08/03/2021
                    ,c1.structure_id
                    -- DISCIPLINE_ID                    
                    ,p_source_id                -- variable parametree coresp id siham
                    ,c1.source_code
                    ,c1.numero_insee
                    ,c1.numero_insee_cle
                    ,c1.numero_insee_prov        -- insee_prov que si pas de definitif                    
                    ,c1.iban
                    ,c1.bic
                    ,p_annee_id                     -- id de l annee universitaire    
                    ,v_id_grade_ose
                    -- MONTANT_INDEMNITE_FC
                    -- CRITERE_RECHERCHE    
                    -- CODE
                    -- SUPANN_EMP_ID
                    ,c1.pays_naissance_id        -- id dans OSE.UM_PAYS
                    ,c1.dep_naissance
                    ,c1.pays_nationalite_id        
                    -- ##A_PERSONNALISER_CHOIX_SIHAM## ajout de champs W_ de verification des infos Siham bien utile pour les verifs
                    ,c1.W_STATUT_PIP
                    ,c1.W_TEM_ENSEIG
                    ,c1.W_GROUPE_HIE
                    ,c1.W_CODE_EMPLOI
                    ,c1.W_LIB_EMPLOI
                    ,c1.W_TYPE_FONCTION
                    ,c1.W_FONCTION
                    ,c1.W_STRUCTURE_UO
                    ,c1.W_POSITION
                    ,c1.W_LIB_POSITION
                    ,c1.date_depart_def            
                    ,c1.cause_depart_def
                    ,sysdate                                -- DATE_HORODATAGE
                    ,c1.EMPLOYEUR
                    ,c1.ville_service_rectorat
                    ,decode(v_statut_new.code_statut,'ATV_G','', 'ATV_R','', c1.type_employeur) -- type_employeur -- v3.0 08/03/2021
                    ,c1.TYPE_SPECIALITE
                    ,c1.SPECIALITE
                    ,c1.RECRUTEMENT
                    ,c1.quotite_temps_partage
                    ,c1.orec_code_uo
                    ,c1.nb_h_service_rectorat
                    ,c1.orec_lib_categorie
                    ,c1.W_NB_HEURE_MCE
                    ,c1.rib_hors_sepa                        -- v3.0  07/01/2021
                    ,v_statut_new.date_deb_statut            -- v3.0  07/01/2021
                    ,v_statut_new.date_fin_statut            -- v3.0  07/01/2021
                    ,sysdate                                -- DATE_HORODATAGE_STATUT -- v3.0  07/01/2021
					,c1.emp_source_code						-- v4.1 11/10/2021												
                    );        
                EXCEPTION
                    when no_data_found then 
                            dbms_output.put_line  ('   !!! Pb insert OSE_INTERVENANT - NO DATA FOUND : '||trim(c1.source_code));
                            update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_INSERT = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v3.0
                    when too_many_rows then 
                            dbms_output.put_line  ('   !!! Pb insert OSE_INTERVENANT - TOO MANY ROWS : '||trim(c1.source_code));
                            update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_INSERT = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v3.0
                    when others then
                            dbms_output.put_line  ('   !!! Pb insert OSE_INTERVENANT - OTHERS : '||trim(c1.source_code)||' : '||SQLERRM);
                            update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_INSERT = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v3.0
                    COMMIT;
                END;
                update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_INSERT = 'DONE' 
                where ose_tr.MATCLE = c1.source_code and ose_tr.TEM_OSE_INSERT in ('TODO','A_INS') and ose_tr.annee_id = p_annee_id; -- v3.0
                
                -- si INSERT car multi-statut validé alors maj flag traité
                OSE.UM_MAJ_UM_SYNCHRO_A_VALIDER(cur_pop.matcle, p_annee_id);
                
                COMMIT;
            end if;    
          END IF; --- fin v1.6 CAS DOC MCE     
        end loop;
    END LOOP;     -- FIN LOOP INSERT OSE_INTERVENANT
    
    --*****************************************************************************************
    --**                         UPDATE OSE_INTERVENANT                                      **
    --*****************************************************************************************
    
    dbms_output.put_line('    - update dans UM_INTERVENANT ... '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
    FOR cur_pop in cur_ose_update_intervenant LOOP
        
        for c1 in cur_intervenant(cur_pop.matcle, cur_pop.nudoss, p_d_deb_annee_univ, p_d_fin_annee_univ) loop
                
            -- VARIABLES CALCULEES ---------------------------------------------------------- v3.0 08/03/2021 Dans prog principal au lieu de calculer dans SELECT de c1
            if c1.W_STRUCTURE_UO like 'HZP%' then v_statut_siham := 'HB001'; -- ##A_PERSONNALISER_CHOIX_SIHAM## TITU sur UO detachement mais dans Orec : comme si Hébergé
                else v_statut_siham := c1.W_STATUT_PIP;
            end if; 
            -------------
            OSE.UM_MAJ_INSERT_STATUT(cur_pop.matcle,p_annee_id); -- SI flagué Update mais INSERT MULTI-STATUT MANUEL (tem_validation saisi = 'I') : maj date fin pér préc.
            -------------
            v_statut_new := T_UM_ENREG_STATUT(0,'HOSE','',null, null,0);    -- v3.0 08/03/2021 Affecte statut et date debut statut dans un objet  T_ENREG_STATUT
            select OSE.UM_AFFECTE_STATUT(v_statut_siham, c1.W_GROUPE_HIE, c1.W_FONCTION, c1.W_TEM_FONC,
                        c1.W_CODE_EMPLOI, c1.recrutement, c1.W_MODSERV, c1.W_POSITION, c1.W_CORPS, c1.W_OREC_TYPE_VAC,
                        p_d_deb_annee_univ, c1.date_deb_etat, c1.W_DATDEB_POSITION, c1.W_DATEFF_GRADE, c1.W_DATDEB_FC, c1.W_DATEFF_MODSERV,
                        c1.W_DATEFF_STATUT, p_date_systeme, p_d_fin_annee_univ) 
            INTO v_statut_new from dual; 
            v_statut_new.NB_H_MCE := c1.W_NB_HEURE_MCE;
            -------------
            v_statut_actuel := T_UM_ENREG_STATUT(0,'','',null, null,0);
            select OSE.UM_RECUP_INTERV_STATUT(cur_pop.matcle, p_annee_id, p_date_systeme)
            INTO v_statut_actuel from dual;        -- statut deja existant -- v3.0 a p_date_systeme
            -------------
            v_ose_mail_pro := c1.mail_pro;             -- ##A_PERSONNALISER_CHOIX_SIHAM## : si pas besoin supprimer V_BI_PASSEPORT et DBLink @BI.WORLD et remonter directement le mail suivant vos règles
            if v_statut_new.code_type_intervenant = 'E' then   -- v3.0  07/01/2021    --- SI IE + passeport valide : mail pro sinon mail perso 
                    if c1.um_passeport <> '1' then v_ose_mail_pro := c1.mail_perso; end if;
            end if;
            -------------
            v_id_grade_ose := c1.id_grade_ose;        -- ID du GRADE DANS OSE.UM_GRADE
            if v_id_grade_ose is null then -- pour les non titulaires -- v3.2  15/06/21
                if ( c1.W_STATUT_PIP like 'HB%' or (c1.W_STATUT_PIP like 'C%' and OSE.UM_EST_CTR_PERM_OU_VAC(c1.W_STATUT_PIP) <> 0) ) then 
                
                    if v_statut_new.code_type_intervenant = 'E' then
                        -- ##A_PERSONNALISER_CHOIX_OSE##  -- la lettre permet d'afficher un libellé grade différent dans OSE suivant si Vac ou Perm
                        -- Si V sera couplé avec libelle catégorie OREC dans la vue SRC_...
                        -- if UM_EXISTE_GRADE(c1.W_STATUT_PIP||'V') <> 0 then  -- v3.2  15/06/21
                        v_id_grade_ose:= UM_EXISTE_GRADE(c1.W_STATUT_PIP||'V');      -- VACATAIRE CODE GRADE AVEC V ou sans lettre    
                    else 
                        --if UM_EXISTE_GRADE(c1.W_STATUT_PIP||'P') <> 0 then -- v3.2  15/06/21
                        v_id_grade_ose:= UM_EXISTE_GRADE(c1.W_STATUT_PIP||'P');  -- PERMANENT CODE GRADE AVEC P ou sans lettre
                        --     end if; -- v3.2  15/06/21
                        -- end if; -- v3.2  15/06/21
                    end if;
                else
                    v_id_grade_ose:= UM_EXISTE_GRADE(c1.W_STATUT_PIP);
                end if;
            end if;
            -------------
            v_tem_transfert_ose := 'N';          -- TEMOIN DOSSIER A ENVOYER A OSE (dossier concerné par la GSE dans ose)
            if v_statut_new.id = 0 then v_tem_transfert_ose := 'N';                                -- si statut non géré on n'envoie pas le dossier dans ose
            else if v_statut_new.code_type_intervenant = 'P' then v_tem_transfert_ose := 'O';    -- PERM
                 else if c1.tem_present_orec ='O' then v_tem_transfert_ose := 'O';              -- IE et dans OREC
                      else v_tem_transfert_ose := 'N';
                      end if;    
                 end if;
            end if;

            -- FIN VARIABLES CALCULEES ----------------------------------------------------------
        
          IF v_tem_transfert_ose = 'N' THEN     -- flag HOSE = HORS OSE non géré dans OSE -- v3.0 inversion test IF/ELSE pour plus clair
                update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'HOSE' where ose_tr.MATCLE = c1.source_code and ose_tr.annee_id = p_annee_id;  -- v 3.0
                COMMIT;
          ELSE
            ----------------------------------------------------------------------------------------------------------------------
            -- UPDATE OSE.UM_INTERVENANT -- PARTIE 1 : zones d'informations toujours mises a jour
            -- v3.0 update statut séparé suite à rmq Lyon2 : le renommage ou maj mail doit être fait meme si chgt statut pas validé
            ----------------------------------------------------------------------------------------------------------------------    
            --dbms_output.put_line('1 : update partie 1 '||' ID um_intervenant '||cur_pop.ID||' '||sysdate);
            BEGIN
                update OSE.UM_INTERVENANT
                set  CIVILITE_ID			= c1.civilite_id
                    ,NOM_USUEL				= c1.nom_usuel
                    ,PRENOM					= c1.prenom
                    ,NOM_PATRONYMIQUE		= c1.nom_patronymique
                    ,DATE_NAISSANCE			= c1.date_naissance
                    --,VILLE_NAISSANCE_CODE_INSEE = c1.ville_naissance_code_insee    -- v3.0  07/01/2021
                    ,VILLE_NAISSANCE_LIBELLE = c1.ville_naissance_libelle
                    ,TEL_PRO				= c1.ose_tel_pro
                    ,TEL_MOBILE				= c1.ose_tel_mobile
                    ,EMAIL_PRO				= v_ose_mail_pro        	-- v3.0  08/03/2021
                    ,STRUCTURE_ID			= c1.structure_id
                    ,SOURCE_ID				= p_source_id            	-- variable parametree coresp id siham
                    ,NUMERO_INSEE			= c1.numero_insee
                    ,NUMERO_INSEE_CLE		= c1.numero_insee_cle
                    ,NUMERO_INSEE_PROVISOIRE = c1.numero_insee_prov
                    ,IBAN					= c1.iban
                    ,BIC					= c1.bic
                    ,ANNEE_ID				= p_annee_id
                    --,GRADE_ID             = v_id_grade_ose        	-- v4.0  21/09/21
                    ,PAYS_NAISSANCE_ID		= c1.pays_naissance_id
                    ,DEP_NAISSANCE			= c1.dep_naissance
                    ,PAYS_NATIONALITE_ID 	= c1.pays_nationalite_id
                    ,W_TEM_ENSEIG   		= c1.W_TEM_ENSEIG
                    ,W_STRUCTURE_UO     	= c1.W_STRUCTURE_UO
                    ,DATE_DEPART_DEF    	= c1.date_depart_def
                    ,CAUSE_DEPART_DEF    	= c1.cause_depart_def
                    ,DATE_HORODATAGE    	= sysdate
                    ,EMPLOYEUR            	= c1.EMPLOYEUR
                    ,ville_service_rectorat	= c1.ville_service_rectorat
                    ,nb_h_service_rectorat	= c1.nb_h_service_rectorat
                    ,TYPE_EMPLOYEUR			= c1.TYPE_EMPLOYEUR
                    ,TYPE_SPECIALITE		= c1.TYPE_SPECIALITE
                    ,SPECIALITE				= c1.SPECIALITE
                    ,OREC_CODE_UO			= c1.orec_code_uo
                    ,orec_lib_categorie		= c1.orec_lib_categorie
                    ,RIB_HORS_SEPA			= c1.rib_hors_sepa        	-- v3.0  07/01/2021
					,EMP_SOURCE_CODE		= c1.emp_source_code		-- v4.1 11/10/2021															 
                where ID = cur_pop.ID;

                EXCEPTION
                    when no_data_found then 
                            dbms_output.put_line  ('   !!! Pb update UM_INTERVENANT PARTIE 1 - NO DATA FOUND : '||trim(c1.source_code)||' ID : '||cur_pop.ID);
                            update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v1.14
                            commit;
                    when too_many_rows then 
                            dbms_output.put_line  ('   !!! Pb update UM_INTERVENANT PARTIE 1 - TOO MANY ROWS : '||trim(c1.source_code)||' ID : '||cur_pop.ID);
                            update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v1.14
                            commit;
                    when others then
                            dbms_output.put_line  ('   !!! Pb update UM_INTERVENANT PARTIE 1 - OTHERS : '||trim(c1.source_code)||' ID : '||cur_pop.ID||' : '||SQLERRM);
                            update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v1.14
                            commit;
                END;
                update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'DONE' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.TEM_OSE_UPDATE = 'TODO' and ose_tr.annee_id = p_annee_id;  -- v1.14
            COMMIT;

            ----------------------------------------------------------------------------------------------------------------------
            -- UPDATE OSE.UM_INTERVENANT -- PARTIE  2 : STATUT suivant param GESTION_STATUT si UNIQUE ou MULTIPLE et en AUTO ou MANUEL
            -- v3.0 update statut séparé suite à rmq Lyon2 + flag DATE_HORODATAGE_STATUT
            ----------------------------------------------------------------------------------------------------------------------

		  END IF;    -- fin v_tem_transfert_ose = 'O'  -- v4.3 25/03/22 : END IF car sinon pas de trace de changement des HOSE

            v_maj_statut_a_faire := 'NON';         -- update a faire : par defaut NON
            -- dbms_output.put_line('    - update dans OSE.UM_INTERVENANT ... '||cur_pop.matcle||' : '||v_statut_new.id||' '||v_statut_new.code_statut||' '||v_statut_new.date_deb_statut);
                        
            --dbms_output.put_line('2 :'||v_tem_transfert_ose||' '||v_maj_statut_a_faire||' '||v_statut_new.id||'ancien :'||v_statut_actuel.id||' '||sysdate);    -- v3.0 08/03/21 v_statut_actuel
            ------------ FLAG CHANGEMENT STATUT EN COURS D ANNEE -------------------
            -- changement de statut en cours d'annee
            if (v_statut_new.id <> v_statut_actuel.id and v_statut_actuel.id <> 0 and v_statut_actuel.id is not null) then    -- v3.0 08/03/21 v_statut_actuel
            
                -- traces dans la table de suivi des matricules de la dernière synchro en cours
                if UM_MAJ_UM_TRANSFERT_INDIVIDU(cur_pop.matcle,p_annee_id, v_param_gestion_statut||' : '||v_statut_actuel.CODE_STATUT||'->'||v_statut_new.CODE_STATUT||' au '||to_char(v_statut_new.DATE_DEB_STATUT,'DD/MM/YYYY'))
                        = false then
                        dbms_output.put_line('    - update UM_INTERVENANT PARTIE 2 ... '||cur_pop.matcle||' maj zone UM_TRANSFERT_INDIVIDU.CHANGEMENT_STATUT ko');
                end if;
                
                case when v_param_gestion_statut = 'UNIQUE_AUTO' then 
                        v_maj_statut_a_faire := 'AUTO';
                        -- traces dans la table historique des changements de statut
                        if UM_AJOUT_UM_SYNCHRO_A_VALIDER(cur_pop.matcle,p_annee_id,'A', sysdate, sysdate, v_statut_actuel,v_statut_new, v_param_gestion_statut) = false then
                            dbms_output.put_line('    - update UM_INTERVENANT PARTIE 2 ... '||cur_pop.matcle||' changement statut détecté mais ajout dans UM_SYNCHRO_A_VALIDER ko');
                        end if;
                    when v_param_gestion_statut = 'MULTI_AUTO' then 
                        v_maj_statut_a_faire := 'AUTO_INSERT';
                        -- traces dans la table historique des changements de statut
                        if UM_AJOUT_UM_SYNCHRO_A_VALIDER(cur_pop.matcle,p_annee_id,'AI', sysdate, null, v_statut_actuel,v_statut_new, v_param_gestion_statut) = false then   -- v3.1 pour tem AI il faut d_validation en auto
                            dbms_output.put_line('    - update UM_INTERVENANT PARTIE 2 ... '||cur_pop.matcle||' changement statut détecté mais ajout dans UM_SYNCHRO_A_VALIDER ko');
                        end if;
                    when v_param_gestion_statut in ('UNIQUE_MANUEL','MULTI_MANUEL') then
                        v_maj_statut_a_faire := 'NON';
                        -- SI CAS SPECIFIQUE : IE à IE ---
                        if (v_statut_actuel.code_type_intervenant = 'E' and v_statut_new.code_type_intervenant = 'E') then        -- IE à IE : maj auto, on flague pour écraser le statut précédent
                            v_maj_statut_a_faire := 'AUTO';
                            if UM_AJOUT_UM_SYNCHRO_A_VALIDER(cur_pop.matcle,p_annee_id,'A', sysdate, sysdate, v_statut_actuel,v_statut_new, v_param_gestion_statut) = false then
                                dbms_output.put_line('    - update UM_INTERVENANT PARTIE 2 ... '||cur_pop.matcle||' changement statut détecté mais ajout dans UM_SYNCHRO_A_VALIDER ko');
                            end if;
                        else
                            -- VRAIMENT MANUEL ---
                            -- traces dans la table historique des changements de statut temoin = '?', pas date de validation
                            if UM_AJOUT_UM_SYNCHRO_A_VALIDER(cur_pop.matcle,p_annee_id,'?', null, null, v_statut_actuel,v_statut_new, v_param_gestion_statut) = false then
                                dbms_output.put_line('    - update UM_INTERVENANT PARTIE 2 ... '||cur_pop.matcle||' changement statut détecté mais ajout dans UM_SYNCHRO_A_VALIDER ko');
                            end if;

                            -- On récupère si le changement de statut est validé dans UM_SYNCHRO_A_VALIDER : Si tem_validation = O alors on écrase le statut précédent
                            -- remarque : au moment de la détection, le changement de statut n'est pas validé donc on ne fait pas la maj.
                            v_maj_statut_a_faire := UM_CHGT_STATUT_VALIDE(cur_pop.matcle, p_annee_id, v_statut_actuel, v_statut_new);  -- v3.1
                            dbms_output.put_line('      => CHGT STATUT ... '||cur_pop.matcle||' '||v_statut_actuel.code_statut||'->'||v_statut_new.code_statut||' : Maj OSE = '||v_maj_statut_a_faire);
                        end if;
                end case;
                
            else  ------------ PAS CHGT STATUT MAIS CHANGEMENT DU NB_HEURES_MCE -------------------
                if (v_statut_actuel.nb_h_mce <> 0 and v_statut_actuel.nb_h_mce <> v_statut_new.nb_h_mce and v_statut_new.nb_h_mce <> 0) then
                        
                    v_maj_statut_a_faire := 'NON';
                    -- traces dans la table de suivi des matricules de la dernière synchro en cours
                    if UM_MAJ_UM_TRANSFERT_INDIVIDU(cur_pop.matcle,p_annee_id, v_param_gestion_statut||' : '||' CHGT NB_H_MCE : '||v_statut_actuel.nb_h_mce||'->'||v_statut_new.nb_h_mce||' au '||to_char(v_statut_new.DATE_DEB_STATUT,'DD/MM/YYYY'))
                            = false then
                            dbms_output.put_line('    - update UM_INTERVENANT PARTIE 2 ... '||cur_pop.matcle||' maj zone UM_TRANSFERT_INDIVIDU.CHANGEMENT_STATUT ko');
                    end if;
                    
                    case when v_param_gestion_statut in ('UNIQUE_AUTO', 'MULTI_AUTO') then 
                                v_maj_statut_a_faire := 'AUTO';
                                -- traces dans la table historique des changements de statut : même temoin_validation car de toute façon on n'ajoute pas on écrase
                                if UM_AJOUT_UM_SYNCHRO_A_VALIDER(cur_pop.matcle,p_annee_id,'A', sysdate, sysdate, v_statut_actuel,v_statut_new, v_param_gestion_statut) = false then
                                    dbms_output.put_line('    - update UM_INTERVENANT PARTIE 2 ... '||cur_pop.matcle||' changement NB_H_MCE détecté mais ajout dans UM_SYNCHRO_A_VALIDER ko');
                                end if;
                        when v_param_gestion_statut in ('UNIQUE_MANUEL', 'MULTI_MANUEL') then
                            -- traces dans la table historique des changements de statut temoin = '?', pas date de validation
                            if UM_AJOUT_UM_SYNCHRO_A_VALIDER(cur_pop.matcle,p_annee_id,'?', null, null, v_statut_actuel,v_statut_new, v_param_gestion_statut) = false then
                                dbms_output.put_line('    - update UM_INTERVENANT PARTIE 2 ... '||cur_pop.matcle||' changement NB_H_MCE détecté mais ajout dans UM_SYNCHRO_A_VALIDER ko');
                            end if;
                                
                            -- On récupère si le changement NB_H_MCE est validé dans UM_SYNCHRO_A_VALIDER : Si tem_validation = O alors on écrase le statut précédent
                            v_maj_statut_a_faire := UM_CHGT_STATUT_VALIDE(cur_pop.matcle, p_annee_id, v_statut_actuel, v_statut_new);    -- v3.1
                            dbms_output.put_line('      => CHGT NB HEURE MCE ... '||cur_pop.matcle||' : Maj OSE = '||v_maj_statut_a_faire);        
                    end case;
                end if;
            end if;    
        
            if v_maj_statut_a_faire in ('AUTO','OUI') then    -- v3.0
                    -- Statut écrasé automatiquement ou validé dans UM_SYNCHRO_A_VALIDER par 'O' pour écraser (!! si validé par I pour Insert sera traité à part)
                    BEGIN
                    --dbms_output.put_line('3 :'||c1.tem_transfert_ose||' '||v_maj_statut_a_faire||' '||v_statut_new.id||sysdate);                    
					v_new_date_deb_statut := v_statut_new.date_deb_statut;   	-- v4.2  25/01/22
					if v_statut_actuel.code_type_intervenant = 'E' and v_statut_new.code_type_intervenant = 'E' THEN
							v_new_date_deb_statut := v_statut_actuel.date_deb_statut;
					end if;

                    update OSE.UM_INTERVENANT        -- maj de tous les champs utiles pour le statut ou MCE (si pas de chgt statut ou si chgt statut validé)
                    set                    
                        STATUT_ID             = v_statut_new.id
                        ,SOURCE_CODE         = c1.source_code
                        ,GRADE_ID             = v_id_grade_ose                -- v4.0  21/09/21
                        ,W_STATUT_PIP         = c1.W_STATUT_PIP
                        ,W_GROUPE_HIE         = c1.W_GROUPE_HIE
                        ,W_CODE_EMPLOI         = c1.W_CODE_EMPLOI
                        ,W_LIB_EMPLOI         = c1.W_LIB_EMPLOI
                        ,W_FONCTION         = c1.W_FONCTION
                        ,W_TYPE_FONCTION     = c1.W_TYPE_FONCTION
                        ,W_POSITION         = c1.W_POSITION
                        ,W_LIB_POSITION     = c1.W_LIB_POSITION
                        ,W_NB_HEURE_MCE        = v_statut_new.nb_h_mce
                        ,RECRUTEMENT        = c1.RECRUTEMENT
                        ,QUOTITE_TEMPS_PARTAGE = c1.quotite_temps_partage
                        ,DATE_DEB_STATUT    = v_new_date_deb_statut            -- v4.2  25/01/22
                        ,DATE_FIN_STATUT     = v_statut_new.date_fin_statut            -- v3.0  08/03/2021
                        ,DATE_HORODATAGE_STATUT = sysdate
                    where ID = cur_pop.ID;
        
                    EXCEPTION
                        when no_data_found then 
                                dbms_output.put_line  ('   !!! Pb update UM_INTERVENANT PARTIE 2 - NO DATA FOUND : '||trim(c1.source_code)||' ID : '||cur_pop.ID);
                                update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v1.14
                                commit;
                        when too_many_rows then 
                                dbms_output.put_line  ('   !!! Pb update UM_INTERVENANT PARTIE 2 - TOO MANY ROWS : '||trim(c1.source_code)||' ID : '||cur_pop.ID);
                                update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v1.14
                                commit;
                        when others then
                                dbms_output.put_line  ('   !!! Pb update UM_INTERVENANT PARTIE 2 - OTHERS : '||trim(c1.source_code)||' ID : '||cur_pop.ID||' : '||SQLERRM);
                                update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'PB' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.annee_id = p_annee_id;  -- v1.14
                                commit;
                    END;
                    update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'DONE' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.TEM_OSE_UPDATE = 'TODO' and ose_tr.annee_id = p_annee_id;  -- v1.14
                    COMMIT;
            else
                    if v_maj_statut_a_faire = 'AUTO_INSERT' then 
                        -- changement de statut à valider MANUELLEMENT ----
                        update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'A_INS' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.TEM_OSE_UPDATE = 'DONE' 
                                                                                                        and ose_tr.annee_id = p_annee_id and ose_tr.changement_statut like 'MULTI_AUTO :%';  -- v3.1 annul test TODO car update déjà fait
                    else
                        -- changement de statut à valider MANUELLEMENT ----
                        update OSE.UM_TRANSFERT_INDIVIDU ose_tr set ose_tr.TEM_OSE_UPDATE = 'CHGT' where ose_tr.MATCLE = cur_pop.matcle and ose_tr.TEM_OSE_UPDATE = 'TODO' and ose_tr.annee_id = p_annee_id;  -- v1.14
                    end if;
                    COMMIT;
            end if; -- fin v_maj_statut_a_faire PARTIE 2

 ---         END IF;    -- fin v_tem_transfert_ose = 'O'  -- v4.3 25/03/22 :remonter le END IF car sinon pas de trace de changement des HOSE
        end loop;
    END LOOP;          -- FIN LOOP UPDATE OSE_INTERVENANT
    
    
    --******** TRAITEMENTS APRES BOUCLE ****************************************************************************************************
    
    ---------- Statistiques sur etat TODO/DONE/HOSE/CHGT ---------------------------------------------------------------------
    select UM_STAT_TRANSFERT_INDIVIDU(p_annee_id) into v_stat_transfert from dual;
    dbms_output.put_line(' ');
    dbms_output.put_line('   '||p_annee_id||' => Témoins (UM_TRANSFERT_INDIVIDU) :');   -- v1.14
    dbms_output.put_line('      '||v_stat_transfert);                                         -- v1.14
    
    ---------- si flag Multi-statut à traiter auto  ---------------------
    select count(*) into v_nb_a_valider from OSE.UM_TRANSFERT_INDIVIDU where tem_ose_update = 'A_INS';
    dbms_output.put_line(p_annee_id||'   !! Dossiers avec MULTI-STATUT validé AUTO : '||v_nb_a_valider);  --v3.1 11/03/21
    
    --dbms_output.put_line('   '||p_annee_id||'***** Fin des INSERT/UPDATE ...'||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
END;
/
