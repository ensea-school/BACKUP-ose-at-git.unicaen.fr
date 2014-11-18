
  CREATE MATERIALIZED VIEW "OSE"."ANTHONY_MV_HARPEGE" ("NO_INDIVIDU", "C_CIVILITE", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "D_NAISSANCE", "C_PAYS_NAISSANCE", "LL_PAYS_NAISSANCE", "C_DEPARTEMENT", "LL_DEPARTEMENT", "C_COMMUNE_NAISSANCE", "VILLE_DE_NAISSANCE", "C_PAYS_NATIONALITE", "LL_PAYS_NATIONALITE", "NO_TELEPHONE", "NO_TEL_PORTABLE", "NO_E_MAIL", "NO_INSEE", "CLE_INSEE", "ID_IND_BANQUE", "C_PAYS_ISO", "CLE_CONTROLE", "C_BANQUE", "C_GUICHET", "NO_COMPTE", "CLE_RIB", "C_BANQUE_BIC", "C_PAYS_BIC", "C_EMPLACEMENT", "C_BRANCHE", "CAV_D_DEB_CONTRAT_TRAV", "CAV_D_FIN_CONTRAT_TRAV", "AA_NO_SEQ_AFFECTATION", "AA_D_DEB_AFFECTATION", "AA_D_FIN_AFFECTATION", "AR_NO_SEQ_AFFE_RECH", "AR_D_FIN_AFFE_RECH", "UCBN_FLAG_ENS_NO_INDIVIDU", "UCBN_FLAG_ENS_DEB", "UCBN_FLAG_ENS_FIN", "CC_NO_SEQ_CHERCHEUR", "CC_D_DEB_STR_TRAV", "CC_D_FIN_STR_TRAV", "C_TYPE_CONTRAT_TRAV", "C_TYPE_POPULATION", "DATE_DEPART", "C_STRUCTURE_DEPART")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS WITH dernier_rib AS
  (
  SELECT no_dossier_pers, MAX(ID_IND_BANQUE) KEEP (DENSE_RANK LAST ORDER BY d_creation) id_ind_banque
  FROM individu_banque@harpprod GROUP BY no_dossier_pers
  ),
     depart AS
  (
  SELECT DISTINCT
    i.no_individu,
    MAX(greatest(
      CASE WHEN aa.no_seq_affectation IS NOT NULL THEN nvl(aa.d_fin_affectation,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
      CASE WHEN ar.no_seq_affe_rech IS NOT NULL THEN nvl(ar.d_fin_affe_rech,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
      CASE WHEN ufe.no_individu IS NOT NULL THEN nvl(ufe.fin,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
      CASE WHEN cc.no_seq_chercheur IS NOT NULL THEN nvl(cc.d_fin_str_trav,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END
    )) over(partition by i.no_individu)
     date_depart
  FROM
    individu@harpprod i
    LEFT JOIN ucbn_flag_enseignant@harpprod ufe ON ufe.no_individu = i.no_individu
    LEFT JOIN chercheur@harpprod cc ON cc.no_individu = i.no_individu
    LEFT JOIN affectation@harpprod aa ON aa.no_dossier_pers = i.no_individu
    LEFT JOIN affectation_recherche@harpprod ar ON ar.no_dossier_pers = i.no_individu
  )
SELECT
  -- Etat Civil
  individu.no_individu
  ,individu.c_civilite
  ,individu.nom_usuel
  ,individu.prenom
  ,individu.nom_patronymique
  ,individu.d_naissance
  ,pays_naissance.c_pays         "C_PAYS_NAISSANCE"
  ,pays_naissance.ll_pays        "LL_PAYS_NAISSANCE"
  ,departement.c_departement
  ,departement.ll_departement
  ,individu.c_commune_naissance
  ,individu.ville_de_naissance
  ,pays_nationalite.c_pays       "C_PAYS_NATIONALITE"
  ,pays_nationalite.ll_pays      "LL_PAYS_NATIONALITE"
  ,individu_telephone.no_telephone
  ,individu.no_tel_portable
  ,individu_e_mail.no_e_mail
  ,code_insee.no_insee
  ,code_insee.cle_insee
  -- Pour les coordonnees bancaires:
  ,ib.id_ind_banque
  ,ib.c_pays_iso
  ,ib.cle_controle
  ,ib.c_banque
  ,ib.c_guichet
  ,ib.no_compte
  ,ib.cle_rib
  ,ib.c_banque_bic
  ,ib.c_pays_bic
  ,ib.c_emplacement
  ,ib.c_branche
  -- Pour les structures associees
  ,cav.d_deb_contrat_trav "CAV_D_DEB_CONTRAT_TRAV"
  ,cav.d_fin_contrat_trav "CAV_D_FIN_CONTRAT_TRAV"
  ,aa.no_seq_affectation  "AA_NO_SEQ_AFFECTATION"
  ,aa.d_deb_affectation   "AA_D_DEB_AFFECTATION"
  ,aa.d_fin_affectation   "AA_D_FIN_AFFECTATION"
  ,ar.no_seq_affe_rech    "AR_NO_SEQ_AFFE_RECH"
  ,ar.d_fin_affe_rech     "AR_D_FIN_AFFE_RECH"
  ,ufe.no_individu        "UCBN_FLAG_ENS_NO_INDIVIDU"
  ,ufe.debut              "UCBN_FLAG_ENS_DEB"
  ,ufe.fin                "UCBN_FLAG_ENS_FIN"
  ,cc.no_seq_chercheur    "CC_NO_SEQ_CHERCHEUR"
  ,cc.d_deb_str_trav      "CC_D_DEB_STR_TRAV"
  ,cc.d_fin_str_trav      "CC_D_FIN_STR_TRAV"
  -- Pour le statut
  ,ct.c_type_contrat_trav
  ,ca.c_type_population
  -- Pour le dernier statut
  ,CASE depart.date_depart
    WHEN to_date('01/01/1000','DD/MM/YYYY') THEN NULL
    ELSE depart.date_depart
   END date_depart
  ,pbs_divers__cicg.c_structure_globale@harpprod(depart.no_individu, CASE depart.date_depart WHEN to_date('01/01/1000','DD/MM/YYYY') THEN SYSDATE else depart.date_depart END ) c_structure_depart
FROM
  -- Etat Civil
  individu@harpprod                     individu
  LEFT JOIN pays@harpprod               pays_naissance ON (pays_naissance.c_pays = individu.c_pays_naissance)
  LEFT JOIN departement@harpprod        departement ON (departement.c_departement = individu.c_dept_naissance)
  LEFT JOIN pays@harpprod               pays_nationalite ON (pays_nationalite.c_pays = individu.c_pays_nationnalite)
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail ON (individu_e_mail.no_individu = individu.no_individu)
  LEFT JOIN individu_telephone@harpprod individu_telephone ON (individu_telephone.no_individu = individu.no_individu
                                                               AND individu_telephone.tem_tel_principal='O'
                                                               AND individu_telephone.tem_tel='O')
  LEFT JOIN code_insee@harpprod         code_insee ON (code_insee.no_dossier_pers = individu.no_individu)
  -- Pour la banque
  LEFT JOIN dernier_rib ON (individu.no_individu = dernier_rib.no_dossier_pers)
  LEFT JOIN individu_banque@harpprod ib ON (dernier_rib.id_ind_banque = ib.id_ind_banque)
  -- Pour les structures attachees
  LEFT JOIN ucbn_flag_enseignant@harpprod ufe ON ufe.no_individu = individu.no_individu
  LEFT JOIN chercheur@harpprod cc ON cc.no_individu = individu.no_individu
  LEFT JOIN affectation@harpprod aa ON aa.no_dossier_pers = individu.no_individu
  LEFT JOIN affectation_recherche@harpprod ar ON ar.no_dossier_pers = individu.no_individu
  -- Pour le statut
  LEFT JOIN carriere@harpprod ca ON (ca.no_dossier_pers = individu.no_individu AND ca.no_seq_carriere = aa.no_seq_carriere)
  LEFT JOIN contrat_travail@harpprod ct ON (individu.no_individu = ct.no_dossier_pers AND aa.no_contrat_travail = ct.no_contrat_travail)
  LEFT JOIN contrat_avenant@harpprod cav ON (cav.no_dossier_pers = ct.no_dossier_pers AND cav.no_contrat_travail = ct.no_contrat_travail )
  -- Pour le dernier statut
  LEFT JOIN depart ON (individu.no_individu=depart.no_individu)
;

   COMMENT ON MATERIALIZED VIEW "OSE"."ANTHONY_MV_HARPEGE"  IS 'snapshot table for snapshot OSE.ANTHONY_MV_HARPEGE';
