
CREATE MATERIALIZED VIEW MV_INTERVENANT AS
WITH
i AS (
  SELECT -- permet de fusionner les données pour ne conserver qu'une des tuples (code,statut) sans doublons
    code,
    statut,
    MAX(z_discipline_id_cnu)      z_discipline_id_cnu,
    MAX(z_discipline_id_sous_cnu) z_discipline_id_sous_cnu,
    MAX(z_discipline_id_spe_cnu)  z_discipline_id_spe_cnu,
    MAX(z_discipline_id_dis2deg)  z_discipline_id_dis2deg,
    MAX(date_fin) date_fin
  FROM
  (
    SELECT
      i.*, -- permet de ne sélectionner que les données (contrats, etc) se terminant le plus tard possible ou bien sans date de fin
      CASE WHEN COUNT(*) OVER (PARTITION BY code,statut) > 1 THEN
        CASE WHEN COALESCE(date_fin,SYSDATE) = MAX(COALESCE(date_fin,SYSDATE)) OVER (PARTITION BY code,statut) THEN 1 ELSE 0 END
      ELSE 1 END ok2,
      COUNT(*) OVER (PARTITION BY code,statut,date_fin) dc
    FROM
    (
      SELECT
        i.*,
        CASE -- permet de supprimer les données obsolètes ou futures s'il y en a des actuelles (contrat en cours, etc)
          WHEN
            COUNT(*) OVER (PARTITION BY i.code) > 1
            AND MAX(i.actuel) OVER (PARTITION BY i.code) = 1
            AND i.actuel = 0
          THEN 0 ELSE 1 END ok
      FROM
      (
-- les CONTRACTUELS  
       SELECT
          ct.no_dossier_pers                                 code,
          CASE -- lien entre le contrat de travail Mangue et le statut d'intervenant OSE
            WHEN ct.c_type_contrat_trav IN ('MC','MA')                          THEN 'ASS_MI_TPS'
            WHEN ct.c_type_contrat_trav IN ('C3030','C3031','C3032','C3033','C3034','C3035','C3036')    THEN 'ATER'
            WHEN ct.c_type_contrat_trav IN ('C3037','C3038','C3039','C3040','C3041','C3042','C3043')    THEN 'ATER_MI_TPS'
            WHEN ct.c_type_contrat_trav IN ('DO','CN322','C0322','CN109','COMDOC')       THEN 'DOCTOR'
            WHEN ct.c_type_contrat_trav IN ('CDI02')                    THEN 'ENS_CONTRACT'
            -- P8 
            WHEN (ct.c_type_contrat_trav IN ('C3094','PN') and cav.num_quot_recrutement=100)                 THEN 'ENS_CONTRACT'
            WHEN (ct.c_type_contrat_trav IN ('C3094','PN') and cav.num_quot_recrutement<100)                 THEN 'ENS_CONTRACT_50'
            WHEN ct.c_type_contrat_trav IN ('LT','LB','C2046')                   THEN 'LECTEUR'
            WHEN ct.c_type_contrat_trav IN ('MB','MP','C2043')                   THEN 'MAITRE_LANG'
            WHEN ct.c_type_contrat_trav IN ('CDI01','C3066','C3097','C6')       THEN 'BIATSS'
           -- WHEN (ct.c_type_contrat_trav like 'COMU%' or ct.c_type_contrat_trav IN ('COMCDI','C0701')) THEN 'NON_AUTORISE'
           -- à faire confirmer par Véronique pour le C0104 en BIATSS ou NON AUTORISE
		   WHEN (ct.c_type_contrat_trav like 'COMU%' or ct.c_type_contrat_trav IN ('CA','COMCDI','C0701')) THEN 'NON_AUTORISE'   
		   --ELSE 'AUTRES' (vu avec IL le 31/10/2018)
			ELSE 'BIATSS'
          END                                                statut,
          cnu.c_section_cnu                                  z_discipline_id_cnu,
          case when cnu.c_sous_section_cnu like '00' then null else cnu.c_sous_section_cnu end  z_discipline_id_sous_cnu, 
          --cnu.c_sous_section_cnu                             z_discipline_id_sous_cnu,
          null as                                z_discipline_id_spe_cnu,
          CAV.c_disc_second_degre                            z_discipline_id_dis2deg,
          CAV.d_fin_contrat_av                               date_fin,
          CASE WHEN
            SYSDATE BETWEEN CAV.d_deb_contrat_av-1 AND COALESCE(CAV.d_fin_contrat_av,SYSDATE)+1
          THEN 1 ELSE 0 END                                  actuel
        FROM
          mangue.contrat_avenant@COCKTAIL CAV
          JOIN mangue.contrat@COCKTAIL ct ON ct.NO_SEQ_CONTRAT = CAV.NO_SEQ_CONTRAT
          --
          JOIN mangue.affectation@COCKTAIL a ON a.no_dossier_pers = ct.no_dossier_pers
          --ct.no_dossier_pers = CAV.no_dossier_pers AND ct.no_contrat_travail = CAV.no_contrat_travail
          LEFT JOIN grhum.CNU@COCKTAIL    cnu                ON CAV.no_cnu = cnu.no_cnu
        WHERE -- on sélectionne les données même 6 mois avant et 6 mois après
         -- SYSDATE BETWEEN CAV.d_deb_contrat_av-184 AND COALESCE(CAV.d_fin_contrat_av,SYSDATE)+184
         SYSDATE BETWEEN CAV.d_deb_contrat_av-184 AND COALESCE(CAV.d_fin_contrat_av+184,SYSDATE)
              -- !!!! seulement si on ne veut que les avenants en cours (contradictoire avec ci-dessus)
                          AND CAV.D_DEB_CONTRAT_AV <= SYSDATE
                          AND (   CAV.D_FIN_CONTRAT_AV IS NULL
                            --Benouah le 11/07/2019 
                            -- on ajoute 6 mois apres la fin du contrat
                            --OR CAV.D_FIN_CONTRAT_AV + 1 >= SYSDATE)
                               OR CAV.D_FIN_CONTRAT_AV + 545 >= SYSDATE)
              -- !!!!                
          AND CAV.TEM_ANNULATION <> 'O'    
-- on ne tient compte que de l'affectation PRINCIPALE en cours
    AND ((((A.d_fin_affectation is null) AND (A.d_deb_affectation<=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy'))
                                           AND (A.tem_valide='O'))
            OR
            ((A.d_deb_affectation<=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy'))
                                           --Benouah le 11/07/2019 
                                          -- on ajoute 6 mois apres la fin du contrat
                                          --AND (A.d_fin_affectation>=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy'))
                                          AND (A.d_fin_affectation+545>=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy'))
                                          AND (A.tem_valide='O'))
           )
            AND A.tem_principale = 'O'                            
         )
UNION
-- les TITULAIRES    = OK pas de doublons à ce niveau OK
        SELECT
          a.no_dossier_pers                                  code,
          CASE -- lien entre le type de population MANGUE et le statut d'intervenant OSE
            WHEN CA.c_type_population IN ('DA','OA','DC')              THEN 'ENS_2ND_DEG'
            WHEN CA.c_type_population IN ('SA')                        THEN 'ENS_CH'
            WHEN CA.c_type_population IN ('AE','BA','IA','MA')           THEN 'BIATSS'
            --ELSE 'AUTRES' (vu avec IL le 31/10/2018)
			ELSE 'BIATSS'
          END                                                      statut,
          cnu.c_section_cnu                                  z_discipline_id_cnu,
          case when cnu.c_sous_section_cnu like '00' then null else cnu.c_sous_section_cnu end                             z_discipline_id_sous_cnu, 
          --cnu.c_sous_section_cnu                             z_discipline_id_sous_cnu,
          null as                                  z_discipline_id_spe_cnu,
          psc.c_disc_sd_degre                                 z_discipline_id_dis2deg,
          a.d_fin_affectation                                date_fin,
          CASE WHEN
            SYSDATE BETWEEN a.d_deb_affectation-1 AND COALESCE(a.d_fin_affectation,SYSDATE)+1
          THEN 1 ELSE 0 END                                  actuel
        FROM
          MANGUE.affectation@COCKTAIL a
          LEFT
          JOIN  MANGUE.carriere@COCKTAIL CA ON CA.no_dossier_pers = a.no_dossier_pers --AND CA.no_seq_carriere = a.no_seq_carriere (190 individus dont le CA.no_seq_carriere est différent du a.no_seq_carriere !!!)
          LEFT JOIN  MANGUE.carriere_specialisations@COCKTAIL psc  ON psc.no_dossier_pers = a.no_dossier_pers
          --AND psc.no_seq_carriere = a.no_seq_carriere
          AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(psc.spec_debut,SYSDATE) AND COALESCE(psc.spec_fin,SYSDATE)
--
        LEFT JOIN grhum.CNU@COCKTAIL    cnu                ON psc.no_cnu = cnu.no_cnu
      -- //////////////// tout ça pke no_seq_carriere pas (ou mal) renseigné dans affectation
      INNER JOIN mangue.element_CARRIERE@COCKTAIL EC
                       ON     EC.NO_DOSSIER_PERS = a.no_dossier_pers
                       inner join
     (
     select no_dossier_pers, max(d_effet_element) as maxeffet from MANGUE.element_CARRIERE@COCKTAIL
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
        WHERE -- on sélectionne les données même 6 mois avant et 6 mois après
          SYSDATE BETWEEN a.d_deb_affectation-184 AND COALESCE(a.d_fin_affectation,SYSDATE)+184

    UNION
 -- ==========================
 -- les HEBERGES en MAD entrante
 SELECT distinct cth.no_dossier_pers code,
		'conv_mad' as statut,
		'00'                                  z_discipline_id_cnu,
          null as 							z_discipline_id_sous_cnu,
          null as                                  z_discipline_id_spe_cnu,
          null as                                  z_discipline_id_dis2deg,
          CTH.D_FIN_CONTRAT_INV                                date_fin,
          CASE WHEN
            SYSDATE BETWEEN CTH.D_DEB_CONTRAT_INV-1 AND COALESCE(CTH.D_FIN_CONTRAT_INV,SYSDATE)+160
          THEN 1 ELSE 0 END                                  actuel
 	FROM mangue.CONTRAT_HEBERGES@COCKTAIL CTH
			where  cth.tem_valide = 'O'
                          --AND (to_date(CTH.D_FIN_CONTRAT_INV)+1 > SYSDATE OR cth.d_fin_contrat_inv IS NULL)
	UNION
-- ===================
 -- les VACATAIRES -==================
  SELECT
  vac.NO_DOSSIER_PERS code,
  --'AUTRES'            statut, -- pas de statut de défini ici
  CASE -- lien entre la "profession" du vacataire et le statut d'intervenant OSE
            WHEN prof.pro_libelle IN ('TITU DE LA FCTION PUBL')            THEN 'SALAR_PUBLIC'
            WHEN prof.pro_libelle IN ('AGT NON TITU DE LA FCTION PUBL')    THEN 'SALAR_PUBLIC_CONT'
            WHEN prof.pro_libelle IN ('ACTIVITE SECT PRIVE')               THEN 'SALAR_PRIVE'
			WHEN prof.pro_libelle IN ('ENSEIG DS ETABL PRIVE')             THEN 'SALAR_PUBLIC_PRIV'
            WHEN prof.pro_libelle IN ('ACTIV NON SALARIE')           THEN 'AUTO_LIBER_INDEP'
            WHEN prof.pro_libelle IN ('ETUDIANT 3EME CYCLE')         then 'SS_ETUD'
            WHEN prof.pro_libelle IN ('RETRAITE')                    then 'RETRAITE'
            WHEN prof.pro_libelle IN ('BENEVOLE')                    then 'BENEVOLE'
            WHEN prof.pro_libelle IN ('INTERMITTENT DU SPECTACLE')   then 'INTERMITTENT_SPECT'
            WHEN prof.pro_libelle IN ('DIRECTION D''ENTREPRISE')     then 'DIRIGEANT_ENT'
			WHEN prof.pro_libelle IN ('RESIDENT ETRANGER')     then 'SALAR_ETRANGER'
            WHEN prof.pro_libelle IN ('TRAVAILLEUR INDEPENDANT')     then 'TRAV_INDE'
            ELSE 'AUTRES' 
          END 
          statut,
   cnu.c_section_cnu         z_discipline_id_cnu,
   case when cnu.c_sous_section_cnu like '00' then null else cnu.c_sous_section_cnu end                             z_discipline_id_sous_cnu, 
   '00' as                                  z_discipline_id_spe_cnu,
   '000' as            z_discipline_id_dis2deg,
   vac.D_FIN_VACATION date_fin,
   CASE WHEN
      --SYSDATE BETWEEN vac.D_DEB_VACATION AND vac.D_FIN_VACATION
      --benouah le 15/07/2019
       SYSDATE BETWEEN vac.D_DEB_VACATION AND vac.D_FIN_VACATION+545
   THEN 1 ELSE 0 END  actuel
FROM 
    MANGUE.VACATAIRES@COCKTAIL vac
JOIN
    GRHUM.INDIVIDU_ULR@COCKTAIL ind
    ON (ind.NO_INDIVIDU=vac.NO_DOSSIER_PERS)
    LEFT JOIN grhum.CNU@COCKTAIL    cnu                ON vac.no_cnu = cnu.no_cnu
	LEFT JOIN grhum.PROFESSION@COCKTAIL    prof        ON vac.pro_code = prof.PRO_CODE
WHERE
    ind.IND_ACTIVITE='VACATAIRE'
	-- ************** on ne prend que les vacataires en cours
		AND vac.tem_valide = 'O'
    --benouah le 15/07/2019
   AND SYSDATE BETWEEN vac.D_DEB_VACATION AND vac.D_FIN_VACATION+545
    --    AND SYSDATE BETWEEN vac.D_DEB_VACATION AND vac.D_FIN_VACATION
	-- **************
      ) i
    ) i WHERE ok = 1
  )i WHERE ok2 = 1 GROUP BY code,statut
),
comptes (no_individu, rank_compte, nombre_comptes, IBAN, BIC) AS (
  SELECT no_dossier_pers,
    dense_rank() over(partition by no_dossier_pers order by d_creation) rank_compte,
    count(*) over(partition by no_dossier_pers)  nombre_comptes,
    decode(ind_activite, 'VACATAIRE', IBAN, null) IBAN,
    decode(ind_activite, 'VACATAIRE', BIC, null) BIC
  FROM GRHUM.V_ULH_INDIVIDU_BANQUE@COCKTAIL           
)
	-- DEMANDE IL 15/10/2018 PAS INSEE NI ADRESSES POPULATION PERSONNELS ULH ////
	-- tous les personnels hors vacataires et hébergés (puisque appel à table affectation)
	-- NB. l'affectation des vacataires est indiquée dans la table VACATAIRES_AFFECTATION
	-- NB. la structure d'affectation des hébergés est trouvée dans la table CONTRAT_HEBERGES
  SELECT  
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             code,
  CASE individu.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END z_civilite_id,
  initcap(individu.nom_usuel)                                 nom_usuel,
  initcap(individu.prenom)                                    prenom,
  initcap(individu.nom_patronymique)                          nom_patronymique,
  individu.d_naissance                                        date_naissance,
  individu.c_pays_naissance                                   z_pays_naissance_id,
  
  individu.c_dept_naissance                                   z_dep_naissance_id,
  'ZZZZ' as ville_naissance_code_insee,
  individu.ville_de_naissance                                 ville_naissance_libelle,
  individu.c_pays_nationalite                                 z_pays_nationalite_id,
  tel_prf.no_telephone                                            tel_pro,
  --tel_prv.no_telephone                                            tel_mobile,
  '000' as                         tel_mobile,
  
  -- ////
 /* null as                                   z_dep_naissance_id,
  null as  ville_naissance_code_insee,
  null as                               ville_naissance_libelle,
  null as                                 z_pays_nationalite_id,
  tel_prf.no_telephone                                            tel_pro,
  null as                                            tel_mobile,
  */
  -- ////
 -- ULH_UCBN_LDAP.hid2mail(individu.no_individu)  email,
  mail.cem_email||'@'||cem_domaine email,
  --
    i.statut                                                    z_statut_id,
  decode(grhum.Trouve_lc_structure_pere@COCKTAIL(s.c_structure),'UP8',S.LC_STRUCTURE,grhum.Trouve_lc_structure_pere@COCKTAIL(s.c_structure)) AS z_structure_id,  
  --s.LC_structure AS z_structure_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             source_code,
  
  code_insee.no_insee                                         numero_insee,
  TO_CHAR(code_insee.cle_insee)                               numero_insee_cle,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END  numero_insee_provisoire,
  comptes.iban                                                iban,
  comptes.bic                                                 bic,
  
  -- ////
  /*null as                                        numero_insee,
  null as                               numero_insee_cle,
  null as  numero_insee_provisoire,
  null as                                              iban,
  null as                                               bic,
  */
  -- ////
  GRHUM.ULH_IND_GRADE_EN_COURS@COCKTAIL(individu.no_individu)     as               z_grade_id,
  i.z_discipline_id_cnu                                       z_discipline_id_cnu,
  i.z_discipline_id_sous_cnu                                  z_discipline_id_sous_cnu,
  i.z_discipline_id_spe_cnu                                   z_discipline_id_spe_cnu,
  i.z_discipline_id_dis2deg                                   z_discipline_id_dis2deg,
 utl_raw.cast_to_varchar2((nlssort(to_char(individu.nom_usuel || ' ' || individu.nom_patronymique || ' ' || individu.prenom), 'nls_sort=binary_ai'))) critere_recherche,
  i.date_fin
FROM
  i
  JOIN grhum.individu_ulr@COCKTAIL         individu           ON individu.no_individu           = i.code
   LEFT JOIN grhum.personne_telephone@COCKTAIL tel_prf  ON tel_prf.pers_id = individu.pers_id  AND tel_prf.type_no='TEL' AND tel_prf.type_tel='PRF' AND tel_prf.tel_principal='O' 
  LEFT JOIN grhum.personne_telephone@COCKTAIL tel_prv  ON tel_prv.pers_id = individu.pers_id  AND tel_prv.type_no='MOB' AND tel_prv.type_tel='PRV' AND tel_prv.tel_principal='O'
  LEFT JOIN grhum.code_insee@COCKTAIL      code_insee         ON code_insee.no_dossier_pers     = i.code
   LEFT JOIN grhum.compte@COCKTAIL compte ON  compte.pers_id = individu.pers_id 
  LEFT JOIN grhum.compte_email@COCKTAIL mail ON mail.cpt_ordre = compte.cpt_ordre
  LEFT JOIN                             comptes            ON comptes.no_individu            = i.code AND comptes.rank_compte = comptes.nombre_comptes
  -- AJOUT !!!!!!
  -- pour récup structure_pere
  inner JOIN MANGUE.affectation@COCKTAIL A ON (i.code=A.no_dossier_pers)
      inner JOIN grhum.structure_ulr@COCKTAIL S ON (A.c_structure=S.c_structure)
    INNER join grhum.personnel_ulr@COCKTAIL P on (i.code=P.no_dossier_pers)
     where
         -- on ne tient compte que de l'affectation PRINCIPALE en cours
    A.tem_principale = 'O'
    and A.tem_valide='O'
    and A.d_deb_affectation<=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy')
    --and (A.d_fin_affectation is null OR A.d_fin_affectation+184 >=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy'))
    --Benouahle 15/07/2019
    --and (A.d_fin_affectation is null OR A.d_fin_affectation >=to_date(to_char(sysdate+184, 'dd/mm/yyyy'),'dd/mm/yyyy'))
    and (A.d_fin_affectation is null OR A.d_fin_affectation +545>=to_date(to_char(sysdate, 'dd/mm/yyyy'),'dd/mm/yyyy'))
UNION
	        -- les HEBERGES en MAD entrante
SELECT
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             code,
  CASE individu.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END z_civilite_id,
  initcap(individu.nom_usuel)                                 nom_usuel,
  initcap(individu.prenom)                                    prenom,
  initcap(individu.nom_patronymique)                          nom_patronymique,
  individu.d_naissance                                        date_naissance,
  individu.c_pays_naissance                                   z_pays_naissance_id,
  individu.c_dept_naissance                                   z_dep_naissance_id,
  'ZZZZ' as ville_naissance_code_insee,
  individu.ville_de_naissance                                 ville_naissance_libelle,
  individu.c_pays_nationalite                                 z_pays_nationalite_id,
  tel_prf.no_telephone                                            tel_pro,
  '000' as tel_mobile,
  --tel_prv.no_telephone                                            tel_mobile,
  -- !!! attention !!! prendre la 1ère ligne ci-dessous lors du passage en PROD OSE
  --ULH_UCBN_LDAP.hid2mail(individu.no_individu)  email,
  mail.cem_email||'@'||cem_domaine email,
  --mel.no_e_mail email,
  --CASE individu.ind_activite WHEN 'VACATAIRE' THEN 'ingrid.laignel@univ-lehavre.fr' ELSE ULH_UCBN_LDAP.hid2mail(individu.no_individu) END  email,
  --
    i.statut                                                    z_statut_id,
  -- appel à une fonction renvoyant l'affectation principale du vacataire
  decode(grhum.Trouve_lc_structure_pere@COCKTAIL(s.c_structure),'UP8',S.LC_STRUCTURE,grhum.Trouve_lc_structure_pere@COCKTAIL(s.c_structure)) AS z_structure_id,  
  --S.LC_STRUCTURE AS z_structure_id, 
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             source_code,
  individu.ind_no_insee                                         numero_insee,
  TO_CHAR(individu.ind_cle_insee)                               numero_insee_cle,
  CASE WHEN individu.ind_no_insee IS NULL THEN NULL ELSE 0 END  numero_insee_provisoire,
  comptes.iban                                                iban,
  comptes.bic                                                 bic,
  null     as               z_grade_id,
  i.z_discipline_id_cnu                                       z_discipline_id_cnu,
  i.z_discipline_id_sous_cnu                                  z_discipline_id_sous_cnu,
  i.z_discipline_id_spe_cnu                                   z_discipline_id_spe_cnu,
  i.z_discipline_id_dis2deg                                   z_discipline_id_dis2deg,
 utl_raw.cast_to_varchar2((nlssort(to_char(individu.nom_usuel || ' ' || individu.nom_patronymique || ' ' || individu.prenom), 'nls_sort=binary_ai'))) critere_recherche,
  i.date_fin
FROM
  i
  JOIN grhum.individu_ulr@COCKTAIL         individu           ON individu.no_individu           = i.code
  --LEFT JOIN grhum.individu_e_mail@COCKTAIL individu_e_mail    ON individu_e_mail.no_individu    = i.code
  -- pour les vacataires : règle de saisie donnée aux RH (par IL) : TEL + PRV
  JOIN mangue.CONTRAT_HEBERGES@COCKTAIL CTH ON CTH.no_dossier_pers = i.code
  inner JOIN grhum.structure_ulr@COCKTAIL S ON (CTH.c_structure=S.c_structure)
  LEFT JOIN grhum.personne_telephone@COCKTAIL tel_prf  ON tel_prf.pers_id = individu.pers_id  AND tel_prf.type_no='TEL' AND tel_prf.type_tel='PRF' AND tel_prf.tel_principal='O' 
  LEFT JOIN grhum.personne_telephone@COCKTAIL tel_prv  ON tel_prv.pers_id = individu.pers_id  AND tel_prv.type_no='MOB' AND tel_prv.type_tel='PRV' AND tel_prv.tel_principal='O'
  --LEFT JOIN grhum.ulh_v_individu_e_mail@COCKTAIL  mel  ON mel.no_individu = i.code AND tadr_code='PERSO' AND rpa_valide='O' AND RPA_PRINCIPAL='O'
  --LEFT JOIN grhum.code_insee@COCKTAIL      code_insee         ON code_insee.no_dossier_pers     = i.code
   LEFT JOIN grhum.compte@COCKTAIL compte ON  compte.pers_id = individu.pers_id 
  LEFT JOIN grhum.compte_email@COCKTAIL mail ON mail.cpt_ordre = compte.cpt_ordre
  LEFT JOIN comptes ON comptes.no_individu = i.code AND comptes.rank_compte = comptes.nombre_comptes
    --where CTH.c_type_contrat_trav like 'CN112'     -- on ne prend QUE les contrats de MAD entrante
--
  UNION
        -- les VACATAIRES
SELECT
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             code,
  CASE individu.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END z_civilite_id,
  initcap(individu.nom_usuel)                                 nom_usuel,
  initcap(individu.prenom)                                    prenom,
  initcap(individu.nom_patronymique)                          nom_patronymique,
  individu.d_naissance                                        date_naissance,
  individu.c_pays_naissance                                   z_pays_naissance_id,
  individu.c_dept_naissance                                   z_dep_naissance_id,
  -- !!!! à mettre en char
  -- en fait il y a une erreur dans la fonction ++++ on s'en moque de cette donnée !!
  -- CAST (nvl(grhum.ULH_INSEE_COMMUNE@COCKTAIL(ville_de_naissance), '00000') AS VARCHAR2 (10))         ville_naissance_code_insee,
  'ZZZZ' as ville_naissance_code_insee,
  individu.ville_de_naissance                                 ville_naissance_libelle,
  individu.c_pays_nationalite                                 z_pays_nationalite_id,
  tel_prf.no_telephone                                            tel_pro,
 -- tel_prv.no_telephone                                            tel_mobile,
  '000' as                         tel_mobile,
  -- !!! attention !!! prendre la 1ère ligne ci-dessous lors du passage en PROD OSE
  -- ULH_UCBN_LDAP.hid2mail(individu.no_individu)  email,
  mail.cem_email||'@'||cem_domaine email,
      i.statut                                                    z_statut_id,
  -- appel à une fonction renvoyant l'affectation principale du vacataire
  nvl(ULH_AFF_PRINCIPALE_VACATAIRE@COCKTAIL(vac.vac_id),'UNIVERSITE PARIS 8')   z_structure_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             source_code,
  code_insee.no_insee                                         numero_insee,
  TO_CHAR(code_insee.cle_insee)                               numero_insee_cle,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END  numero_insee_provisoire,
  comptes.iban                                                iban,
  comptes.bic                                                 bic,
  null     as               z_grade_id,
  i.z_discipline_id_cnu                                       z_discipline_id_cnu,
  i.z_discipline_id_sous_cnu                                  z_discipline_id_sous_cnu,
  i.z_discipline_id_spe_cnu                                   z_discipline_id_spe_cnu,
  i.z_discipline_id_dis2deg                                   z_discipline_id_dis2deg,
 utl_raw.cast_to_varchar2((nlssort(to_char(individu.nom_usuel || ' ' || individu.nom_patronymique || ' ' || individu.prenom), 'nls_sort=binary_ai'))) critere_recherche,
  i.date_fin
FROM
  i
  JOIN grhum.individu_ulr@COCKTAIL         individu           ON individu.no_individu           = i.code
    -- pour les vacataires : règle de saisie donnée aux RH (par IL) : TEL + PRV
	-- 22/10/2018 ! on met dans Tél privé !!!
  LEFT JOIN grhum.personne_telephone@COCKTAIL tel_prf  ON tel_prf.pers_id = individu.pers_id  AND tel_prf.type_no='TEL' AND tel_prf.type_tel='PRF' AND tel_prf.tel_principal='O' 
  LEFT JOIN grhum.personne_telephone@COCKTAIL tel_prv  ON tel_prv.pers_id = individu.pers_id  AND tel_prv.type_no='TEL' AND tel_prv.type_tel='PRV' AND tel_prv.tel_principal='O'
  --LEFT JOIN grhum.ulh_v_individu_e_mail@COCKTAIL  mel  ON mel.no_individu = i.code AND tadr_code='PERSO' AND rpa_valide='O' AND RPA_PRINCIPAL='O'
  LEFT JOIN grhum.compte@COCKTAIL compte ON  compte.pers_id = individu.pers_id 
  LEFT JOIN grhum.compte_email@COCKTAIL mail ON mail.cpt_ordre = compte.cpt_ordre
  LEFT JOIN grhum.code_insee@COCKTAIL      code_insee         ON code_insee.no_dossier_pers     = i.code
  LEFT JOIN comptes ON comptes.no_individu = i.code AND comptes.rank_compte = comptes.nombre_comptes
  LEFT JOIN mangue.vacataires@COCKTAIL VAC ON VAC.no_dossier_pers = i.code
     -- on ne prend QUE les VACATAIRES
  where   individu.ind_activite like 'VACATAIRE'
  --****************
  AND vac.tem_valide = 'O'
        --Benouah le 15/07/2019
        --AND SYSDATE BETWEEN vac.D_DEB_VACATION AND vac.D_FIN_VACATION
        AND SYSDATE BETWEEN vac.D_DEB_VACATION AND vac.D_FIN_VACATION+545
