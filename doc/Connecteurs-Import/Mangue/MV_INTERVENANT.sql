  CREATE MATERIALIZED VIEW MV_INTERVENANT AS
  WITH
i AS (

SELECT DISTINCT
    code,
    z_statut_id,
    FIRST_VALUE(z_discipline_id_cnu) OVER (partition by code, z_statut_id order by validite_fin desc)      z_discipline_id_cnu,
    FIRST_VALUE(z_discipline_id_sous_cnu) OVER (partition by code, z_statut_id order by validite_fin desc) z_discipline_id_sous_cnu,
    FIRST_VALUE(z_discipline_id_spe_cnu) OVER (partition by code, z_statut_id order by validite_fin desc)  z_discipline_id_spe_cnu,
    FIRST_VALUE(z_discipline_id_dis2deg) OVER (partition by code, z_statut_id order by validite_fin desc)  z_discipline_id_dis2deg,
    MIN(source_code) OVER (partition by code, z_statut_id)                                                 source_code,
    MIN(validite_debut) OVER (partition by code, z_statut_id)                                              validite_debut,
    MAX(validite_fin) OVER (partition by code, z_statut_id)                                                validite_fin
  FROM
(    -- les CONTRACTUELS 
      SELECT
          ct.no_dossier_pers                                 code,
          CASE -- lien entre le contrat de travail Mangue et le statut d'intervenant OSE
            WHEN ct.c_type_contrat_trav IN ('MC','MA')                          THEN 'ASS_MI_TPS'
            WHEN ct.c_type_contrat_trav IN ('C3030','C3031','C3032','C3033','C3034','C3035','C3036')    THEN 'ATER'
            WHEN ct.c_type_contrat_trav IN ('C3037','C3038','C3039','C3040','C3041','C3042','C3043')    THEN 'ATER_MI_TPS'
			WHEN (ct.c_type_contrat_trav IN ('CN322') and cav.c_grade = '6904')  THEN 'DOCTOR'
			WHEN (ct.c_type_contrat_trav IN ('CN322','COMDOC') and cav.c_grade = '6902')  THEN 'NON_AUTORISE'
            WHEN ct.c_type_contrat_trav IN ('DO','C0322','CN109','COMDOC')       THEN 'DOCTOR'
            WHEN (ct.c_type_contrat_trav IN ('C3094','CDI02') and cav.num_quot_recrutement=100) THEN 'ENS_CONTRACT'
            WHEN (ct.c_type_contrat_trav IN ('C3094','CDI02') and cav.num_quot_recrutement<100) THEN 'ENS_CONTRACT_50'
            WHEN ct.c_type_contrat_trav IN ('LT','LB','C2046')                   THEN 'LECTEUR'
            WHEN ct.c_type_contrat_trav IN ('MB','MP','C2043')                   THEN 'MAITRE_LANG'
            WHEN ct.c_type_contrat_trav IN ('CDI01','C3066','C3097','C6')        THEN 'BIATSS'
    	    WHEN (ct.c_type_contrat_trav like 'COMU%' or ct.c_type_contrat_trav IN ('CA','COMCDI','C0701')) THEN 'NON_AUTORISE'   
			ELSE 'BIATSS' 
		    END                                         				  						z_statut_id, 	 		   
		  cnu.c_section_cnu																		z_discipline_id_cnu, -- cnu = grhum.CNU@dbl_grhum
          case when cnu.c_sous_section_cnu like '00' then null else cnu.c_sous_section_cnu end  z_discipline_id_sous_cnu, 
          null as                               												z_discipline_id_spe_cnu,
          CAV.c_disc_second_degre                        										z_discipline_id_dis2deg,
		  ct.no_dossier_pers || '-c-' || CAV.no_SEQ_CONTRAT                                     source_code,  
		    COALESCE(CAV.d_deb_contrat_av,to_date('01/01/1900', 'dd/mm/YYYY'))                  validite_debut, 
		    COALESCE(CAV.d_fin_contrat_av,to_date('01/01/9999', 'dd/mm/YYYY'))                  validite_fin 
		  FROM
          mangue.contrat_avenant@dbl_grhum   CAV
          JOIN mangue.contrat@dbl_grhum   ct ON ct.NO_SEQ_CONTRAT = CAV.NO_SEQ_CONTRAT
		  JOIN mangue.ULHN_V_DERNIER_CONTRAT@dbl_grhum   vd  -- Vu ULHN pour n'avoir que le dernier contrat
		  ON vd.no_seq_contrat = cav.no_seq_contrat AND vd.no_individu = ct.no_dossier_pers
		  LEFT JOIN grhum.CNU@dbl_grhum    				cnu ON CAV.no_cnu = cnu.no_cnu
			WHERE CAV.d_deb_contrat_av-184 <= SYSDATE    
            AND   CAV.TEM_ANNULATION <> 'O'
UNION ALL
-- les TITULAIRES   
    SELECT
          a.no_dossier_pers                                  				code,   
          CASE -- lien entre le type de population MANGUE et le statut d'intervenant OSE
            WHEN CA.c_type_population IN ('DA','OA','DC')              THEN 'ENS_2ND_DEG'
            WHEN CA.c_type_population IN ('SA')                        THEN 'ENS_CH'
            WHEN CA.c_type_population IN ('AE','BA','IA','MA')         THEN 'BIATSS'
          --ELSE 'AUTRES' -- Choix RH ULHN 
		  	ELSE 'BIATSS'
			END 																				z_statut_id,  
		  cnu.c_section_cnu  		                            								z_discipline_id_cnu,
          case when cnu.c_sous_section_cnu like '00' then null else cnu.c_sous_section_cnu end  z_discipline_id_sous_cnu, 
          null as                                  			  									z_discipline_id_spe_cnu,
          psc.c_disc_sd_degre                                 									z_discipline_id_dis2deg,
          a.no_dossier_pers || '-a-' || a.no_seq_affectation          						    source_code,
		  COALESCE(a.d_deb_affectation,to_date('01/01/1900', 'dd/mm/YYYY'))  					validite_debut,
          COALESCE(a.d_fin_affectation,to_date('01/01/9999', 'dd/mm/YYYY')) 					validite_fin
		FROM
          mangue.affectation@dbl_grhum  a
		  JOIN grhum.individu_ulr@dbl_grhum individu ON individu.no_individu=a.no_dossier_pers 
          LEFT JOIN  mangue.carriere@dbl_grhum  CA   ON CA.no_dossier_pers = a.no_dossier_pers 
          LEFT JOIN  mangue.carriere_specialisations@dbl_grhum  psc  ON psc.no_dossier_pers = a.no_dossier_pers
          AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(psc.spec_debut,SYSDATE) AND COALESCE(psc.spec_fin,SYSDATE)
		  LEFT JOIN grhum.CNU@dbl_grhum     cnu      ON psc.no_cnu = cnu.no_cnu
      -- //////////////// tout ça pke no_seq_carriere pas (ou mal) renseigné dans affectation : 
		  INNER JOIN mangue.element_CARRIERE@dbl_grhum  EC           ON     EC.NO_DOSSIER_PERS = a.no_dossier_pers
          INNER JOIN
     (
     select no_dossier_pers, max(d_effet_element) as maxeffet from mangue.element_CARRIERE@dbl_grhum 
     WHERE  ((d_fin_element IS NULL) OR (d_fin_element >= TO_DATE(TO_CHAR(sysdate,'DD/MM/YYYY'),'DD/MM/YYYY')))
     AND   d_effet_element <= TO_DATE(TO_CHAR(sysdate,'DD/MM/YYYY'),'DD/MM/YYYY')
     AND    tem_valide = 'O'
     group by NO_DOSSIER_PERS
     ) ec2 on ec.no_dossier_pers = ec2.no_dossier_pers and ec.d_effet_element=ec2.maxeffet
                       AND EC.TEM_PROVISOIRE='N'
                       AND EC.TEM_VALIDE='O'
                       AND CA.NO_SEQ_CARRIERE = EC.NO_SEQ_CARRIERE
                       AND CA.D_DEB_CARRIERE <= SYSDATE
                       AND (   CA.D_FIN_CARRIERE IS NULL
                            OR CA.D_FIN_CARRIERE + 1 >= SYSDATE)
                       AND ca.tem_valide = 'O'
        -- on ne tient compte que de l'affectation PRINCIPALE en cours
    AND ((((A.d_fin_affectation is null) AND (A.d_deb_affectation<=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy'))
                                         AND (A.tem_valide='O'))
            OR
            ((A.d_deb_affectation<=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy'))
                                         AND (A.d_fin_affectation>=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy'))
                                         AND (A.tem_valide='O'))
           )
            AND A.tem_principale = 'O'                            
         )
        -- \\\\\\\\\\\\\\\\\\\\\
        WHERE a.d_deb_affectation-184 <= SYSDATE 
    UNION ALL
 -- ==========================
 -- les HEBERGES en MAD entrante
 SELECT distinct cth.no_dossier_pers code,
		'conv_mad' as 						z_statut_id,  				
		'00'                                z_discipline_id_cnu,
        null as 							z_discipline_id_sous_cnu,
        null as         	                z_discipline_id_spe_cnu,
        null as             	            z_discipline_id_dis2deg,
		  --ch.no_individu || '-h-' || ch.no_seq_chercheur                       source_code, Caen-Harpege
		  cth.no_dossier_pers || '-h-' || cth.no_SEQ_AFFECTATION                 source_code,  --=> no_seq est null dans mangue !!!
		  COALESCE(CTH.D_DEB_CONTRAT_INV,to_date('01/01/1900', 'dd/mm/YYYY'))    validite_debut,
		  COALESCE(CTH.D_FIN_CONTRAT_INV,to_date('01/01/9999', 'dd/mm/YYYY'))    validite_fin
 	FROM mangue.CONTRAT_HEBERGES@dbl_grhum  CTH
	WHERE CTH.c_type_contrat_trav like 'CN112' -- contrat MAD entrante
                  AND cth.tem_valide = 'O'
                  AND CTH.D_DEB_CONTRAT_INV-184 <= SYSDATE
	UNION ALL
-- ===================
-- les VACATAIRES 
  SELECT
  vac.NO_DOSSIER_PERS 															code,
		  CASE -- lien entre la "profession" du vacataire et le statut d'intervenant OSE
            WHEN prof.pro_libelle IN ('TITU DE LA FCTION PUBL')            	THEN 'SALAR_PUBLIC'
            WHEN prof.pro_libelle IN ('AGT NON TITU DE LA FCTION PUBL')    	THEN 'SALAR_PUBLIC_CONT'
            WHEN prof.pro_libelle IN ('ACTIVITE SECT PRIVE')              	THEN 'SALAR_PRIVE'
			WHEN prof.pro_libelle IN ('ENSEIG DS ETABL PRIVE')           	THEN 'SALAR_PUBLIC_PRIV'
            WHEN prof.pro_libelle IN ('ACTIV NON SALARIE')    		    	THEN 'AUTO_LIBER_INDEP'
            WHEN prof.pro_libelle IN ('ETUDIANT 3EME CYCLE')   				THEN 'SS_ETUD'
            WHEN prof.pro_libelle IN ('RETRAITE')                    		THEN 'RETRAITE'
            WHEN prof.pro_libelle IN ('BENEVOLE')                    		THEN 'BENEVOLE'
            --WHEN prof.pro_libelle IN ('INTERMITTENT DU SPECTACLE')   then 'INTERMITTENT_SPECT' correction ULHN 18022021
            WHEN prof.pro_libelle IN ('PROFESSIONNELS DU SPECTACLE') 		THEN 'PROF_SPECT'
			WHEN prof.pro_libelle IN ('AUTEURS, INTERPRETES')        		THEN 'AUT_INTER'			 -- ULHN
			WHEN prof.pro_libelle IN ('CONFERENCIERS')               		THEN 'CONFERENCIER'		     -- ULHN
            WHEN prof.pro_libelle IN ('DIRECTION D''ENTREPRISE')     		THEN 'DIRIGEANT_ENT'
			WHEN prof.pro_libelle IN ('RESIDENT ETRANGER')           		THEN 'SALAR_ETRANGER'
            ELSE 'AUTRES' 
          END 										        					z_statut_id,				--statut_id_contrat_trav,
  -- ajout 20190129 : vacataires sans CNU
   nvl(cnu.c_section_cnu,'00')         											z_discipline_id_cnu,
   case when cnu.c_sous_section_cnu like '00' then null else cnu.c_sous_section_cnu end  z_discipline_id_sous_cnu, 
   '00' as                                  									z_discipline_id_spe_cnu,
   '000' as            															z_discipline_id_dis2deg,
   vac.no_dossier_pers || '-v-' || vac.vac_id                       			source_code, 
	COALESCE(vac.D_DEB_VACATION,to_date('01/01/1900', 'dd/mm/YYYY')) 			validite_debut,
	COALESCE(vac.D_FIN_VACATION,to_date('01/01/9999', 'dd/mm/YYYY')) 			validite_fin
FROM 
    MANGUE.VACATAIRES@dbl_grhum  vac
JOIN
    GRHUM.INDIVIDU_ULR@dbl_grhum  ind
    ON (ind.NO_INDIVIDU=vac.NO_DOSSIER_PERS)
    LEFT JOIN grhum.CNU@dbl_grhum     cnu                ON vac.no_cnu = cnu.no_cnu
	LEFT JOIN grhum.PROFESSION@dbl_grhum     prof        ON vac.pro_code = prof.PRO_CODE
WHERE
    ind.IND_ACTIVITE='VACATAIRE'
		AND vac.tem_valide = 'O'
        AND vac.D_DEB_VACATION-184 <= SYSDATE
-- fin vacataires
)t
), -- ICI fermeture de la parenthèse WITH i AS
-- ******************** POUR TOUS LES INTERVENANTS *******************
 --ATTENTION VU AVEC RH ULHN : on ne prend les infos IBAN QUE pour les vacataires
comptes (no_individu, rank_compte, nombre_comptes, IBAN, BIC) AS (
  SELECT no_dossier_pers,
    dense_rank() over(partition by no_dossier_pers order by d_creation) rank_compte,
    count(*) over(partition by no_dossier_pers)  nombre_comptes,
    decode(ind_activite, 'VACATAIRE', IBAN, null) IBAN,
    decode(ind_activite, 'VACATAIRE', BIC, null) BIC
  FROM GRHUM.V_ULH_INDIVIDU_BANQUE@dbl_grhum            
)
  SELECT DISTINCT
  /* Code de l'intervenant = numéro GRHUM */  
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             code,
  'Mangue'                                                    z_source_id,
  i.source_code												  source_code,
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             utilisateur_code, -- supannEmpID 
  /* Code affiché reprenant le numéro d'individu - Caen 2021 */
  to_char(individu.no_individu)                                 code_rh,
   /* Code structure Harpège (il sera plus tard transformé par la vue source en ID de strucutre OSE) Caen 2021 */
	--sc.c_structure_n2                                       z_structure_id,
			/* <=> */
  /* Code structure ULHN */
  uv_aff.struct_pere										  z_structure_id,
  /* Code statut */	
  i.z_statut_id                                  			  z_statut_id,
  /* Récupération du grade actuel - fonction ULHN  */
  grhum.ulh_ind_grade_en_cours@dbl_grhum (individu.no_individu) 		  z_grade_id,
    /* Données nécessaires pour calculer la discipline - caen 2021 */
  d.source_code                                                 z_discipline_id,
  /* Données identifiantes de base */
  CASE individu.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END 	z_civilite_id,
  initcap(individu.nom_usuel)                                 	nom_usuel,
  initcap(individu.prenom)                                    	prenom,
  individu.d_naissance                                        	date_naissance,
  /* Données identifiantes complémentaires */
  initcap(individu.nom_patronymique)                          	nom_patronymique,
  individu.ville_de_naissance                                 	commune_naissance,
  individu.c_pays_naissance                                   	z_pays_naissance_id,
  individu.c_dept_naissance                                   	z_departement_naissance_id,
  individu.c_pays_nationalite                                 	z_pays_nationalite_id,
  /* Coordonnées : TEL pour etre sur d avoir un TEL_PRO  on prend un des trois - ULHN */
  --tel_prf.no_telephone                                        		    tel_pro,
  COALESCE (tel_prf.no_telephone,tel_prv.no_telephone,tel_mob.no_telephone) tel_pro,
  tel_prv.no_telephone                                        	tel_perso,
  uldap.mail 												   	email_pro,
  CAST(NULL AS varchar2(255))                                 	email_perso,
  /* Adresse   ---revision pour nouv connecteur*/
	TRIM(UPPER(uv_ado.ADR_PRECISIONS))							adresse_precisions,
	CAST(NULL AS varchar2(10))									adresse_numero,	
    CAST(NULL AS varchar2(10))									z_adresse_numero_compl_id,
    CAST(NULL AS varchar2(10))									z_adresse_voirie_id,
    CAST(NULL AS varchar2(10)) 									adresse_voie, 
    CAST(NULL AS varchar2(10))									adresse_lieu_dit,
	uv_ado.CODE_POSTAL    				                        adresse_code_postal,
    TRIM(uv_ado.ADRESSE_COMMUNE)                                adresse_commune,
    uv_ado.PAYS                               				    z_adresse_pays_id,
  /* INSEE */
  TRIM(code_insee.no_insee) || TRIM(TO_CHAR(code_insee.cle_insee)) numero_insee,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END    numero_insee_provisoire,
  /* Banque */  
  comptes.iban                                                  iban,
  comptes.bic                                                   bic,
  0                                                             rib_hors_sepa,
  /* Données complémentaires */
  CAST(NULL AS varchar2(255))                                   autre_1,
  CAST(NULL AS varchar2(255))                                   autre_2,
  CAST(NULL AS varchar2(255))                                   autre_3,
  CAST(NULL AS varchar2(255))                                   autre_4,
  CAST(NULL AS varchar2(255))                                   autre_5,
  /* Employeur */
  CAST(NULL AS varchar2(255))                                   z_employeur_id,
  /* DATES VALIDITE */
  CASE WHEN i.validite_debut = to_date('01/01/1900', 'dd/mm/YYYY') THEN NULL ELSE i.validite_debut END validite_debut,
  CASE WHEN i.validite_fin = to_date('01/01/9999', 'dd/mm/YYYY') THEN NULL ELSE i.validite_fin END validite_fin,
  CAST(NULL AS varchar2(255))                                    affectation_fin
FROM
  i
  JOIN grhum.individu_ulr@dbl_grhum  			individu ON individu.no_individu        = i.code
  JOIN grhum.ULH_V_STRUCT_AFF_TOUS@dbl_grhum  	uv_aff 	 ON uv_aff.no_individu			= i.code 
  LEFT JOIN grhum.ulh_ldap@dbl_grhum			uldap 	 ON uldap.no_individu    		= i.code  -- TABLE ULH_LDAP ULHN
  LEFT JOIN grhum.personne_telephone@dbl_grhum  tel_prf  ON tel_prf.pers_id = individu.pers_id  AND tel_prf.type_no='TEL' AND tel_prf.type_tel='PRF' AND tel_prf.tel_principal='O' 
  LEFT JOIN grhum.personne_telephone@dbl_grhum  tel_prv  ON tel_prv.pers_id = individu.pers_id  AND tel_prv.type_no='TEL' AND tel_prv.type_tel='PRV' AND tel_prv.tel_principal='O'
  LEFT JOIN grhum.personne_telephone@dbl_grhum  tel_mob  ON tel_mob.pers_id = individu.pers_id  AND tel_mob.type_no='MOB' AND tel_mob.type_tel='PRV' AND tel_mob.tel_principal='O'
  LEFT JOIN grhum.code_insee@dbl_grhum       code_insee  ON code_insee.no_dossier_pers  = i.code
  LEFT JOIN comptes                      				 ON comptes.no_individu        = i.code AND comptes.rank_compte = comptes.nombre_comptes
   --**pour Adresses ***-
  LEFT JOIN grhum.ULH_V_ADR_CONN_OSE@dbl_grhum 	uv_ado 	 ON uv_ado.intervenant_id = i.code
  -- Caen 2021 Modifs pour Disciplines : 
  LEFT JOIN discipline d ON
    d.histo_destruction IS NULL
    AND 1 = CASE WHEN -- si rien n'a été défini
      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, i.z_discipline_id_spe_cnu, i.z_discipline_id_dis2deg ) IS NULL
      AND d.source_code = '00'
    THEN 1 WHEN -- si une CNU ou une spécialité a été définie...
      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, i.z_discipline_id_spe_cnu ) IS NOT NULL
    THEN CASE WHEN -- alors on teste par les sections CNU et spécialités
      (
           ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'') || ',%'
        OR ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'00') || ',%'
      )
      AND ',' || NVL(d.CODES_CORRESP_3,'000') || ',' LIKE  '%,' || NVL(CASE WHEN d.CODES_CORRESP_3 IS NOT NULL THEN i.z_discipline_id_spe_cnu ELSE NULL END,'000') || ',%'
    THEN 1 ELSE 0 END ELSE CASE WHEN -- sinon on teste par les disciplines du 2nd degré
      i.z_discipline_id_dis2deg IS NOT NULL
      AND ',' || NVL(d.CODES_CORRESP_4,'') || ',' LIKE  '%,' || i.z_discipline_id_dis2deg || ',%'
    THEN 1 ELSE 0 END END -- fin du test
  --
WHERE
  i.validite_fin+1 >= (SYSDATE - (365*2));
