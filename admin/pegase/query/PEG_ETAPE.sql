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
NB_INSCRIT_BY_REG AS(
	SELECT COUNT(gest_ins.id_apprenant) NB_INSCRIT, gest_ins.regime_inscription_code REG_CODE, gest_cible.id CIBLE_ID, gest_cible.code_periode CODE_PERIODE, gest_cible.code_chemin CHEMIN
	FROM schema_gestion.inscription gest_ins
	JOIN schema_gestion.cible gest_cible ON gest_ins.id_cible = gest_cible.id
	GROUP BY gest_ins.regime_inscription_code, gest_cible.id, gest_cible.code_periode, gest_cible.code_chemin
),
NB_INSCRIT_BY_FIAC AS(
	SELECT
		NB_INSCRIT_BY_REG.CIBLE_ID CIBLE_ID, 
		NB_INSCRIT_BY_REG.CODE_PERIODE CODE_PERIODE, 
		NB_INSCRIT_BY_REG.CHEMIN CHEMIN,
		(CASE WHEN REG_TO_FIAC.FI THEN NB_INSCRIT_BY_REG.NB_INSCRIT ELSE 0 END) NB_INSCRIT_FI,
		(CASE WHEN REG_TO_FIAC.FA THEN NB_INSCRIT_BY_REG.NB_INSCRIT ELSE 0 END) NB_INSCRIT_FA,
		(CASE WHEN REG_TO_FIAC.FC THEN NB_INSCRIT_BY_REG.NB_INSCRIT ELSE 0 END) NB_INSCRIT_FC
	FROM NB_INSCRIT_BY_REG
	JOIN REG_TO_FIAC ON REG_TO_FIAC.REG = NB_INSCRIT_BY_REG.REG_CODE
),
NB_INSCRIT_BY_FIAC_MERGED AS(
	SELECT
		NB_INSCRIT_BY_FIAC.CIBLE_ID, 
		NB_INSCRIT_BY_FIAC.CODE_PERIODE, 
		NB_INSCRIT_BY_FIAC.CHEMIN,
		SUM(NB_INSCRIT_BY_FIAC.NB_INSCRIT_FI) NB_INSCRIT_FI,
		SUM(NB_INSCRIT_BY_FIAC.NB_INSCRIT_FA) NB_INSCRIT_FA,
		SUM(NB_INSCRIT_BY_FIAC.NB_INSCRIT_FC) NB_INSCRIT_FC
	FROM NB_INSCRIT_BY_FIAC
	GROUP BY NB_INSCRIT_BY_FIAC.CIBLE_ID, NB_INSCRIT_BY_FIAC.CODE_PERIODE, NB_INSCRIT_BY_FIAC.CHEMIN
),
FORM_FI_FA_FC AS (
	SELECT 
	mof_form.id formation_id, 
	REG_TO_FIAC.FI,
	REG_TO_FIAC.FA,
	REG_TO_FIAC.FC
	FROM schema_mof.formation_regime_inscription mof_form_regime
	JOIN schema_mof.formation mof_form ON mof_form.id = mof_form_regime.id_formation
	JOIN REG_TO_FIAC ON REG_TO_FIAC.REG = mof_form_regime.code_regime_inscription
),
FORM_FI_FA_FC_AGREGATE AS (
	SELECT FORM_FI_FA_FC.formation_id, BOOL_OR(FORM_FI_FA_FC.FI) FI, BOOL_OR(FORM_FI_FA_FC.FA) FA, BOOL_OR(FORM_FI_FA_FC.FC) FC
	FROM FORM_FI_FA_FC
	GROUP BY FORM_FI_FA_FC.formation_id
)
SELECT DISTINCT
concat('PEG_',mof_maquette.id , '_' , mof_of.code) Z_SOURCE_ID,
mof_maquette.libelle_long FORMATION_LIBELLE,
mof_periode.date_debut ANNEE_DEBUT,
mof_periode.date_fin ANNEE_FIN, 
mof_maquette.code_type_diplome Z_TYPE_FORMATION_ID,
mof_maquette.id_etablissement Z_STRUCTURE_ID,
0 SPECIFIQUE_ECHANGES,
concat(mof_maquette.id , '_' , mof_of.code) SOURCE_CODE,
'Pegase' SOURCE_ID,
mof_maquette.code FORMATION_CODE,
mof_maquette.version FORMATION_VERSION, 
(CASE WHEN FORM_FI_FA_FC_AGREGATE.FI IS NOT NULL THEN FORM_FI_FA_FC_AGREGATE.FI ELSE false END) FI,
(CASE WHEN FORM_FI_FA_FC_AGREGATE.FA IS NOT NULL THEN FORM_FI_FA_FC_AGREGATE.FA ELSE false END) FA,
(CASE WHEN FORM_FI_FA_FC_AGREGATE.FC IS NOT NULL THEN FORM_FI_FA_FC_AGREGATE.FC ELSE false END) FC,
(CASE WHEN NB_INSCRIT_BY_FIAC_MERGED.NB_INSCRIT_FI IS NOT NULL THEN NB_INSCRIT_BY_FIAC_MERGED.NB_INSCRIT_FI ELSE 0 END) EFFECTIF_FI,
(CASE WHEN NB_INSCRIT_BY_FIAC_MERGED.NB_INSCRIT_FA IS NOT NULL THEN NB_INSCRIT_BY_FIAC_MERGED.NB_INSCRIT_FA ELSE 0 END) EFFECTIF_FA,
(CASE WHEN NB_INSCRIT_BY_FIAC_MERGED.NB_INSCRIT_FC IS NOT NULL THEN NB_INSCRIT_BY_FIAC_MERGED.NB_INSCRIT_FC ELSE 0 END) EFFECTIF_FC
From schema_mof.calendrier mof_cal
Join schema_mof.objet_formation_chemin_calendrier_asso mof_ofcca 	ON mof_ofcca.id_calendrier = mof_cal.id
Join schema_mof.objet_formation_chemin mof_ofc 						ON mof_ofc.id = mof_ofcca.id_objet_formation_chemin
Join schema_mof.objet_formation mof_of 								ON mof_of.id = mof_ofc.id_objet_formation
Join schema_mof.formation mof_formation 							ON mof_formation.id = mof_ofc.id_formation
Join schema_mof.maquette mof_maquette								ON mof_formation.id_maquette = mof_maquette.id
Join schema_mof.periode mof_periode									ON mof_of.id_periode = mof_periode.id
LEFT JOIN FORM_FI_FA_FC_AGREGATE 									ON FORM_FI_FA_FC_AGREGATE.formation_id = mof_formation.id
LEFT JOIN NB_INSCRIT_BY_FIAC_MERGED									ON (NB_INSCRIT_BY_FIAC_MERGED.CODE_PERIODE = mof_periode.code AND NB_INSCRIT_BY_FIAC_MERGED.CHEMIN = mof_ofc.chemin)