 CREATE OR REPLACE FORCE EDITIONABLE VIEW "GRHUM"."ULH_V_STRUCT_AFF_TOUS" ("NO_INDIVIDU", "NOM_USUEL", "NOM_PATRONYMIQUE", "PRENOM", "NO_SEQ_AFFECTATION", "STRUCT_PERE") AS 
  (
    SELECT  --titu et contractuels ? :
    -- pour vue mv_intervenant
    no_individu,nom_usuel, nom_patronymique, prenom, 
    A.NO_SEQ_AFFECTATION,
	decode(Trouve_lc_structure_pere(s.c_structure),'UNIVERSITE LE HAVRE NORMANDIE',S.LC_STRUCTURE,Trouve_lc_structure_pere(s.c_structure)) AS struct_pere
    FROM individu_ulr I
      LEFT JOIN MANGUE.affectation A ON (I.no_individu=A.no_dossier_pers)
      LEFT JOIN structure_ulr S ON (A.c_structure=S.c_structure)
     -- on ne prend pas les individus d?c?d?s
        WHERE I.d_deces is null
    -- on ne tient compte que de l'affectation PRINCIPALE en cours
    AND A.tem_principale = 'O' 
	and A.tem_valide='O'
	and A.d_deb_affectation<=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy')
	and (A.d_fin_affectation is null
	OR A.d_fin_affectation >=to_date(to_char(sysdate,'dd/mm/yyyy'),'dd/mm/yyyy')+1)  -- pas -184 pour titu et contract
UNION
SELECT --vacataires 
	individu.no_individu, individu.nom_usuel, individu.nom_patronymique, individu.prenom,
    null as no_seq_affectation,
	nvl(ULH_CHERCHER_AFF_VACATAIRE(vac.vac_id),'UNIVERSITE LE HAVRE NORMANDIE') AS struct_pere
FROM 
    MANGUE.VACATAIRES vac
JOIN
    GRHUM.INDIVIDU_ULR individu
		ON (individu.NO_INDIVIDU=vac.NO_DOSSIER_PERS)
WHERE
    individu.ind_activite like 'VACATAIRE' and SYSDATE BETWEEN vac.D_DEB_VACATION AND vac.D_FIN_VACATION+1  --   fin+1
	UNION
  -- Prise en compte des h?berg?s <==== ?  !!!!!
SELECT distinct no_individu, nom_usuel, nom_patronymique, prenom,
        null as no_seq_affectation,
-- STRUCTURES
	decode(Trouve_lc_structure_pere(s.c_structure),'UNIVERSITE LE HAVRE NORMANDIE',S.LC_STRUCTURE,Trouve_lc_structure_pere(s.c_structure)) AS struct_pere
    FROM INDIVIDU_ULR ind
    INNER JOIN mangue.CONTRAT_HEBERGES CTH
                       ON     CTH.NO_DOSSIER_PERS = IND.NO_INDIVIDU
                          AND cth.tem_valide = 'O'
                          AND (to_date(CTH.D_FIN_CONTRAT_INV)+1 > SYSDATE OR cth.d_fin_contrat_inv IS NULL)  --date fin +1
	and CTH.c_type_contrat_trav like 'CN112' -- contrat MAD entrante					  
	inner JOIN structure_ulr S ON (CTH.c_structure=S.c_structure)
    );
