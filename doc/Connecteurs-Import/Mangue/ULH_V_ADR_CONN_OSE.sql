 CREATE OR REPLACE FORCE EDITIONABLE VIEW "GRHUM"."ULH_V_ADR_CONN_OSE" ("INTERVENANT_ID", "ADR_PRECISIONS", "ADRESSE_NUMERO", "ADRESSE_NUMERO_COMPL_ID", "ADRESSE_VOIE", "ADRESSE_VOIRIE", "ADRESSE_LIEU_DIT", "CODE_POSTAL", "ADRESSE_COMMUNE", "PAYS") AS 
  SELECT
 DISTINCT
 -- oct 2020
 --vue utilisee par OSE pour Vue materialisee MV_intervenant lors de la creation de OSE V15
    LTRIM (TO_CHAR (ind.no_individu, '99999999'))                      z_intervenant_id,                                 
    --vu avec Caen, Que ce champ adr_precision a remplir car pas assez infos detaillees  ds Mangue
    -- habitant_chez peut etre egal a adresse2 mais adresse_2 peut etre null et pas habitant_chez donc choix de habitant_chez 
    TRIM (UPPER (adr.habitant_chez)) || ' '|| adr.ADR_ADRESSE1  adr_precisions, 
     /*caen :
     --adresse.no_voie                            					 adresse_numero,
     --adresse.bis_ter                                               z_adresse_numero_compl_id,
     --adresse.c_voie                                                z_adresse_voirie_id,
     --TRIM(adresse.nom_voie)                                        adresse_voie,
     --CASE WHEN adresse.localite = adresse.ville THEN NULL ELSE adresse.localite END adresse_lieu_dit,*/
	 --
  -- ULHN null car tout est dans adr_precisions :
    null as  adresse_numero,
    null as z_adresse_numero_compl_id,
    null as adresse_voirie,
    null as  adresse_voie,
    null as adresse_lieu_dit,
    --
    COALESCE (adr.code_postal, adr.cp_etranger) adresse_code_postal, 
    TRIM (adr.ville)                            adresse_commune,
    P.C_pays             pays                  
   FROM GRHUM.individu_ulr ind,
        GRHUM.adresse adr ,
        GRHUM.repart_personne_adresse rep_adr ,
        GRHUM.PAYS P
        WHERE                        
                   (  
                   ind.pers_id = rep_adr.pers_id
                   AND rep_adr.adr_ordre = adr.adr_ordre)
                   AND (rep_adr.RPA_VALIDE LIKE 'O%' 
                   AND rep_adr.TADR_CODE LIKE 'PERSO%' --adresse personnelle
                   AND rep_adr.RPA_PRINCIPAL LIKE 'O%' -- sinon doublon entre PERSO et FACT si meme date de saisie
                   )
                   AND P.C_PAYS = adr.C_PAYS;
