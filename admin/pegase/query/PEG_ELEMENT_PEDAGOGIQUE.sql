WITH REG_TO_FIAC AS(
	select
	ref_reg.code REG,
	ref_reg.libelle_court LIB_COURT,
	ref_reg.libelle_long LIB_LONG,
	(ref_reg.code = 'REG001'
	OR ref_reg.code = 'REG032'
	OR ref_reg.code = 'REG034'
	OR ref_reg.code = 'REG035'
	OR ref_reg.code = 'REG002' ) FI,
	(ref_reg.code = 'REG003'
	OR ref_reg.code = 'REG005' ) FA,
	(ref_reg.code = 'REG004'
	OR ref_reg.code = 'REG017' ) FC
	FROM schema_ref.regime_inscription ref_reg
),
APPRENANT_CODEREG AS(
	SELECT gest_apprenant.code_apprenant CODE_APPRENANT, gest_ins.regime_inscription_code REG_CODE, gest_cible.code_periode CODE_PERIODE, gest_cible.code_chemin CHEMIN
	FROM schema_gestion.inscription gest_ins
	JOIN schema_gestion.cible gest_cible ON gest_ins.id_cible = gest_cible.id
	JOIN schema_gestion.apprenant gest_apprenant ON gest_ins.id_apprenant = gest_apprenant.id
),
APPRENANT_BY_FIAC AS(
	SELECT
		APPRENANT_CODEREG.CODE_APPRENANT CODE_APPRENANT,
		APPRENANT_CODEREG.CODE_PERIODE CODE_PERIODE,
		APPRENANT_CODEREG.CHEMIN CHEMIN,
		(CASE WHEN REG_TO_FIAC.FI THEN 1 ELSE 0 END) FI,
		(CASE WHEN REG_TO_FIAC.FA THEN 1 ELSE 0 END) FA,
		(CASE WHEN REG_TO_FIAC.FC THEN 1 ELSE 0 END) FC
	FROM APPRENANT_CODEREG
	JOIN REG_TO_FIAC ON REG_TO_FIAC.REG = APPRENANT_CODEREG.REG_CODE
),
CHC_PATH_BY_FIAC AS(
	SELECT
		chc_periode.code code_periode,
		chc_choix.code_chemin chemin_chc,
		COUNT(chc_apprenant.code) EFFECTIF,
		chc_cursus.code_chemin_racine chemin_etape,
		APPRENANT_BY_FIAC.FI FI,
		APPRENANT_BY_FIAC.FA FA,
		APPRENANT_BY_FIAC.FC FC
	FROM schema_chc.choix_pedagogique chc_choix
	JOIN schema_chc.cursus chc_cursus on chc_cursus.uuid = chc_choix.uuid_cursus
	JOIN schema_chc.apprenant chc_apprenant on chc_apprenant.uuid = chc_cursus.uuid_apprenant
	JOIN schema_chc.periode chc_periode on chc_periode.uuid = chc_cursus.uuid_periode
	JOIN schema_chc.inscription chc_inscription on chc_inscription.uuid = chc_cursus.uuid_inscription
	JOIN schema_chc.chemin_objet_maquette chc_chemin_obj on chc_chemin_obj.uuid = chc_inscription.uuid_chemin_objet_maquette
	JOIN schema_chc.formation chc_formation on chc_formation.uuid = chc_chemin_obj.uuid_formation
	JOIN APPRENANT_BY_FIAC ON ( APPRENANT_BY_FIAC.CODE_APPRENANT = chc_apprenant.code AND APPRENANT_BY_FIAC.CODE_PERIODE = chc_periode.code AND APPRENANT_BY_FIAC.CHEMIN = chc_cursus.code_chemin_racine )
	WHERE type_choix_pedagogique != 'PAS_DE_CHC'
	AND (APPRENANT_BY_FIAC.FI + APPRENANT_BY_FIAC.FA + APPRENANT_BY_FIAC.FC) > 0
	GROUP BY chc_periode.code, chc_choix.code_chemin, chc_cursus.code_chemin_racine, APPRENANT_BY_FIAC.FI, APPRENANT_BY_FIAC.FA, APPRENANT_BY_FIAC.FC
),
CHC_PATH_BY_EFFECTIF_FIAC AS(
	SELECT
		CHC_PATH_BY_FIAC.code_periode,
		CHC_PATH_BY_FIAC.chemin_chc,
		CHC_PATH_BY_FIAC.chemin_etape,
		(CASE WHEN CHC_PATH_BY_FIAC.FI = 1 THEN CHC_PATH_BY_FIAC.EFFECTIF ELSE 0 END) EFFECTIF_FI,
		(CASE WHEN CHC_PATH_BY_FIAC.FA = 1 THEN CHC_PATH_BY_FIAC.EFFECTIF ELSE 0 END) EFFECTIF_FA,
		(CASE WHEN CHC_PATH_BY_FIAC.FC = 1 THEN CHC_PATH_BY_FIAC.EFFECTIF ELSE 0 END) EFFECTIF_FC
	FROM CHC_PATH_BY_FIAC
),
CHC_PATH_BY_EFFECTIF_FIAC_MERGED AS(
	SELECT
		CHC_PATH_BY_EFFECTIF_FIAC.code_periode code_periode,
		CHC_PATH_BY_EFFECTIF_FIAC.chemin_chc chemin_chc,
		CHC_PATH_BY_EFFECTIF_FIAC.chemin_etape chemin_etape,
		SUM(CHC_PATH_BY_EFFECTIF_FIAC.EFFECTIF_FI) EFFECTIF_FI,
		SUM(CHC_PATH_BY_EFFECTIF_FIAC.EFFECTIF_FA) EFFECTIF_FA,
		SUM(CHC_PATH_BY_EFFECTIF_FIAC.EFFECTIF_FC) EFFECTIF_FC
	FROM CHC_PATH_BY_EFFECTIF_FIAC
	GROUP BY CHC_PATH_BY_EFFECTIF_FIAC.code_periode, CHC_PATH_BY_EFFECTIF_FIAC.chemin_chc, CHC_PATH_BY_EFFECTIF_FIAC.chemin_etape
)
SELECT
odf_porteur.uid_odf Z_ID_ODF,
odf_porteur.id_maquette Z_ID_MAQUETTE,
odf_porteur.id_objet Z_ID_ELP,
odf_porteur.code_objet ELP_CODE,
mof_periode.annee_universitaire ANNEE_ID,
mof_periode.date_debut ANNEE_DEBUT,
mof_periode.date_fin ANNEE_FIN,
tmp_maquette_elp.libelle_court ELP_LIBELLE_COURT,
tmp_maquette_elp.libelle_long ELP_LIBELLE_LONG,
tmp_maquette_elp.type_code ELP_CODE_NATURE,
odf_porteur.lib_semestre LIB_SEMESTRE,
etp.ETAPE_CODE ETAPE_CODE,
etp.Z_ID_ETAPE Z_ID_ETAPE,
etp.STRUCTURE_ID STRUCTURE_ID,
null Z_PERIODE_ID,
null Z_DISCIPLINE_ID,
1 TAUX_FOAD,
etp.FI FI,
etp.FA FA,
etp.FC FC,
(CASE WHEN chc_info.EFFECTIF_FI IS NULL THEN 0 ELSE (chc_info.EFFECTIF_FI / (chc_info.EFFECTIF_FI + chc_info.EFFECTIF_FA + chc_info.EFFECTIF_FC)) END) TAUX_FI,
(CASE WHEN chc_info.EFFECTIF_FI IS NULL THEN 0 ELSE (chc_info.EFFECTIF_FA / (chc_info.EFFECTIF_FI + chc_info.EFFECTIF_FA + chc_info.EFFECTIF_FC)) END) TAUX_FA,
(CASE WHEN chc_info.EFFECTIF_FI IS NULL THEN 0 ELSE (chc_info.EFFECTIF_FC / (chc_info.EFFECTIF_FI + chc_info.EFFECTIF_FA + chc_info.EFFECTIF_FC)) END) TAUX_FC,
(CASE WHEN chc_info.EFFECTIF_FI IS NULL THEN 0 ELSE chc_info.EFFECTIF_FI END) EFFECTIF_FI,
(CASE WHEN chc_info.EFFECTIF_FI IS NULL THEN 0 ELSE chc_info.EFFECTIF_FA END) EFFECTIF_FA,
(CASE WHEN chc_info.EFFECTIF_FI IS NULL THEN 0 ELSE chc_info.EFFECTIF_FC END) EFFECTIF_FC
FROM PEGASE_ODF_PORTEUR odf_porteur
JOIN PEGASE_ODF odf ON (odf_porteur.uid_odf = odf.uid_odf)
JOIN PEGASE_ETAPE etp ON etp.Z_ID_ETAPE = odf_porteur.id_etape
JOIN PEGASE_MAQUETTE_OBJET_FORMATION_TMP tmp_maquette_elp ON tmp_maquette_elp.id = odf_porteur.id_objet
JOIN schema_mof.periode mof_periode ON odf_porteur.id_periode = mof_periode.id
LEFT JOIN CHC_PATH_BY_EFFECTIF_FIAC_MERGED chc_info ON (	chc_info.code_periode = mof_periode.code
														AND chc_info.chemin_chc = odf.chemin_origine
														AND chc_info.chemin_etape = odf.chemin_etape
														AND odf.chemin_fictif = FALSE)
WHERE odf_porteur.is_leaf = 1
AND odf_porteur.id_objet IS NOT NULL
AND odf_porteur.id_etape IS NOT NULL
ORDER BY odf_porteur.id_maquette

