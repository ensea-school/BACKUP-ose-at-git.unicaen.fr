/* ====================================================================================================
	A_2_F_OSE_function.sql
	# Detail du connecteur PARTIE A/ SIHAM_REF : des tables de référentiel - Avec user OSE
   
	PHASE1 : EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>
	
	FONCTIONS UTILISES POUR LA SYNCHRO DU REFERENTIEL
			
	--- fonctions -------------- 
	OSE.UM_EXISTE_PAYS
	OSE.UM_EXISTE_DEPARTEMENT
	OSE.UM_UO_TYPE_ENS
	OSE.UM_CODE_UO_NIVEAU_DESSUS
	OSE.UM_EST_STRUCT_MANU
	OSE.UM_NIVEAU_UO
	OSE.UM_AFFICH_UO_SUP
	OSE.UM_UO_NUDOSS
	
	OSE.UM_EXISTE_STRUCTURE
	OSE.UM_EXISTE_ADR_STRUCTURE
	OSE.UM_EXISTE_CORPS
	OSE.UM_EXISTE_GRADE
	OSE.UM_EST_CTR_PERMANENT
	OSE.UM_EST_CTR_PERM_OU_VAC
	OSE.UM_EXISTE_VOIRIE
	OSE.UM_EXISTE_VOIRIE_LIB
	OSE.UM_EXISTE_ADR_NUM_COMPL
	----------------------------
	
	-- v2.0b- 24/01/20 - MYP - var v_stat_transfert : augmentation taille variable
	-- v2.1 - 07/07/20 - MYP - modif regle  affectation statut Mapping_STATUT_SIHAM-OSE_v12.xlsx
	-- v2.2 - 30/11/20 - MYP - V15 + UM_EST_CTR_PERMANENT + UM_EST_CTR_PERM_OU_VAC + OSE.UM_EXISTE_VOIRIE
	-- v2.3 - 20/09/21 - MYP - V15 : pour Prose : besoin fonction  UM_AFFICH_UO_INF
	-- v2.3b- 04/02/22 - MYP - dblink .world
=====================================================================================================*/


CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_PAYS(p_code_pays IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_PAYS
===============================================================*/
v_id_pays	 NUMBER(9) := 0;

CURSOR cur_pays IS
	 select id
	 from OSE.UM_PAYS
	 where trim(source_code) = p_code_pays;

BEGIN
	OPEN cur_pays;
	FETCH cur_pays INTO v_id_pays;

	return v_id_pays;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_DEPARTEMENT(p_code_dept IN VARCHAR2) RETURN VARCHAR IS
/* =============================================================
	UM_EXISTE_DEPARTEMENT
===============================================================*/
v_code_dept	 varchar2(5) := '';

CURSOR cur_dept IS
	 select code
	 from OSE.UM_DEPARTEMENT
	 where trim(source_code) = p_code_dept;

BEGIN
	OPEN cur_dept;
	FETCH cur_dept INTO v_code_dept;

	return v_code_dept;
END;
/

create or replace FUNCTION OSE.UM_UO_TYPE_ENS(p_uo  VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_UO_TYPE_ENS
===============================================================*/
-- retourne 1 si uo avec une sous-uo de type ens
 CURSOR cu_uo_ens IS
	select distinct 1
    from hr.ze00@SIHAM.WORLD
    where tyou00 = 'ENS' 
       and substr(trim(idou00),1,3) = substr(trim(p_uo),1,3);  --v1.9
v_type_ens	 NUMBER;
   
BEGIN
   OPEN cu_uo_ens;
   FETCH cu_uo_ens INTO v_type_ens ;
   IF cu_uo_ens%NOTFOUND THEN
       CLOSE cu_uo_ens;
       Return (NULL);
   ELSE
       CLOSE cu_uo_ens;
       Return (v_type_ens);
   END IF;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_CODE_UO_NIVEAU_DESSUS(p_uo IN varchar2, p_niveau_dessus IN number) RETURN VARCHAR2 IS
/* =============================================================
	UM_CODE_UO_NIVEAU_DESSUS
===============================================================*/
v_c_uo_niveau_hie         varchar2(10);

-- Retourne la structure de x niveaux au dessus dans l'arborescence HIE
-- Conseil : a appeler avec p_uo de l'affectation fine
CURSOR c_uo_niveau_hie IS
      select idou01
      from hr.ze2a@SIHAM.WORLD
      where trim(idou00) = trim(p_uo) --v1.9
		and trim(tytrst) = 'HIE'
		and nbordr = p_niveau_dessus;
BEGIN
    OPEN c_uo_niveau_hie;
	if p_niveau_dessus <= 0 then
		-- si niveau au dessous demandé retourne la meme uo
		v_c_uo_niveau_hie := p_uo;
	else
		FETCH c_uo_niveau_hie INTO v_c_uo_niveau_hie;
	end if;

    return v_c_uo_niveau_hie;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_EST_STRUCT_MANU(p_uo IN VARCHAR2) RETURN VARCHAR2 IS
/* =============================================================
	UM_EST_STRUCT_MANU - retourne vrai si structure manuelle hors Siham -- v1.12
===============================================================*/
v_est_manu 			varchar2(1) := 'N';  
v_trouve 			number(9) 	:= 0;

CURSOR cur_struct_manu IS
	 select 1
	 from OSE.UM_STRUCTURE
	 where tem_struct_manu = 'O' and trim(source_code) = trim(p_uo);

BEGIN
   OPEN cur_struct_manu;
    FETCH cur_struct_manu INTO v_trouve;
	if v_trouve = 1 then
		v_est_manu := 'O';
	else
		v_est_manu := 'N';
	end if;
    return v_est_manu;
END;


create or replace FUNCTION OSE.UM_NIVEAU_UO(p_uo  VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_NIVEAU_UO ----------------- OSEPROD v1.12 - 12/09/2019 - MYP - ajout structure EDTTSD - spéciale OREC car n existe pas dans Siham
===============================================================*/
-- retourne pour une UO le niveau réel de l'UO dans l'arbre 
 CURSOR cu_str IS
            select max(nbordr)+1 as niveau
			from hr.ze2a@SIHAM.WORLD
			where tytrst = 'HIE'
			and trim(idou00) = trim(p_uo); --v1.9
v_c_uo	 NUMBER;
   
BEGIN
   OPEN cu_str;
   FETCH cu_str INTO v_c_uo ;
   IF cu_str%NOTFOUND THEN
       CLOSE cu_str;
       Return (NULL);
   ELSE
       CLOSE cu_str;
       Return (v_c_uo);
   END IF;
END;
/


CREATE OR REPLACE FUNCTION OSE.UM_AFFICH_UO_SUP(p_uo IN varchar2) RETURN VARCHAR2 IS
/* =============================================================
	UM_AFFICH_UO_SUP  -- v1.8
===============================================================*/
v_c_uo 	         varchar2(10);
v_temp_uo		 varchar2(10);

-- UM_AFFICH_UO_SUP : pour remonter les structures de niveau 3 composantes / directions que pour les composantes devant exister dans OSE
-- Récupere le code uo de la structure de x niveaux au dessus
-- conseil : a appeller avec p_uo de l'affectation fine

CURSOR cur_infos_uo_niveau_hie IS
	select 
	/* Renseigner ce select, se baser sur le niveau de hierarchie voulu suivant si recherche, direction, compo péda...
	   pour cela se baser sur l'exemple de tableau excel pour le choix du niveau d'UO /branches : choix_niveaux_UO_pour_OSE.xlsx
	*/
	case 
		-- ##A_PERSONNALISER_CHOIX_SIHAM## : suivant codage codes UO de Siham
		when UM_EST_STRUCT_MANU(p_uo) = 'O' then trim(p_uo) -- v1.12 - 12/09/2019 - MYP - ajout structure EDTTSD - spéciale OREC car n existe pas dans Siham
		-- recherche depart scient
		when v_temp_uo like 'HR1%' then ''
		-- Recherche autres
		when v_temp_uo like 'HRO%05'  then UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-4) -- Ecoles doctorales --v1.9
		when v_temp_uo like 'HR%'  then ''
		-- directions
		when v_temp_uo like 'HDE%' then UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-3)  -- DFE
		when v_temp_uo like 'HDI%' then UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-3)  -- DRI
		when v_temp_uo like 'HDR%' then UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-3)  -- DRED
		when v_temp_uo like 'HD%'  then ''
		-- composantes pedagogisques
		when v_temp_uo like 'HEY%' then ''		-- OSU fausse composante					
		when v_temp_uo like 'HE%'  then UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-3)
			--and 
		-- Autres - Fondations UM
		when v_temp_uo like 'HF%'  then ''
		-- Autres -PrésidenceUM
		when v_temp_uo like 'HG%'  then ''
		-- Autres -Services Communs UM
		when v_temp_uo like 'HS1%'  then UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-3) -- SCFC
		when v_temp_uo like 'HS2%'  then UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-3) -- SCUIO-IP
		when v_temp_uo like 'HS5%'  then UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-3) -- SCAPS
		when v_temp_uo like 'HS%'  	then ''
		when v_temp_uo like 'HXC0BB%'  then 'HXC0BB0006'  -- v1.8 ASUM (ASC ENT HEB dans siham)
		-- Autres - Partenaires exterieurs UM pour AMUE et CINES
		when v_temp_uo like 'HX%'  then ''
		else
			''
	end as c_uo_niveau_voulu
	from dual;
BEGIN
	--- ##A_PERSONNALISER_CHOIX_SIHAM##   composantes ex-aes ou ex-isem qui ont fusionné en moma
	IF (p_uo like 'HER%' or p_uo like 'HEG%') then 
		v_temp_uo := 'HEL0000003';
	ELSE 
		v_temp_uo := p_uo;
	END IF;

   OPEN cur_infos_uo_niveau_hie;
    FETCH cur_infos_uo_niveau_hie  INTO v_c_uo;

    return trim(v_c_uo);
END;
/


create or replace FUNCTION OSE.UM_UO_NUDOSS(p_uo  VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_UO_NUDOSS
===============================================================*/
-- retourne le nudoss de l'uo passée en param
 CURSOR cu_uo IS
		select trim(uo.nudoss)
		from hr.ze00@SIHAM.WORLD uo
		where trim(uo.idou00) = trim(p_uo);  --v1.9
v_c_nudoss	 NUMBER;
   
BEGIN
   OPEN cu_uo;
   FETCH cu_uo INTO v_c_nudoss ;
   IF cu_uo%NOTFOUND THEN
       CLOSE cu_uo;
       Return (NULL);
   ELSE
       CLOSE cu_uo;
       Return (v_c_nudoss);
   END IF;
END;
/


CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_STRUCTURE(p_c_uo IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_STRUCTURE
===============================================================*/
v_id_structure	 NUMBER(9) := 0;

CURSOR cur_structure IS
	 select id
	 from OSE.UM_STRUCTURE
	 where trim(source_code) = p_c_uo;

BEGIN
	OPEN cur_structure;
	FETCH cur_structure INTO v_id_structure;

	return v_id_structure;
END;
/


CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_ADR_STRUCTURE(p_id_structure IN NUMBER) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_ADR_STRUCTURE
===============================================================*/
v_existe 	number(9) := 0;  

CURSOR cur_adr_structure IS
	 select 1
	 from OSE.UM_ADRESSE_STRUCTURE
	 where structure_id = p_id_structure
	;

BEGIN

   OPEN cur_adr_structure;
    FETCH cur_adr_structure INTO v_existe;

    return v_existe;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_CORPS(p_code_corps IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_CORPS
===============================================================*/
v_id_corps 	number(9) := 0;  

CURSOR cur_corps IS
	 select id
	 from OSE.UM_CORPS
	 where trim(source_code) = p_code_corps;

BEGIN
   OPEN cur_corps;
    FETCH cur_corps INTO v_id_corps;


    return v_id_corps;

END;
/

CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_GRADE(p_code_grade IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_GRADE
===============================================================*/
v_id_grade 	number(9) := 0;  

CURSOR cur_grade IS
	 select id
	 from OSE.UM_GRADE
	 where trim(source_code) = p_code_grade;

BEGIN
   OPEN cur_grade;
    FETCH cur_grade INTO v_id_grade;

    return v_id_grade;
END;
/


CREATE OR REPLACE FUNCTION OSE.UM_EST_CTR_PERMANENT(p_statut IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
    UM_EST_CTR_PERMANENT
===============================================================*/
-- Retourne 1 si le statut CONTRACTUEL SIHAM NE PEUT ËTRE QUE PERMANENT DANS OSE
v_est_perm  number(1) := 0; 

BEGIN
	-- ##A_PERSONNALISER_CHOIX_SIHAM##
    if p_statut in ('C0301','C2001','C2001','C2042','C2043','C2047','C2049','C2051')  then
        v_est_perm := 1;
    else    
        if p_statut between 'C2006' and 'C2029' then v_est_perm := 1;
            else v_est_perm := 0;
        end if;
    end if;
    return v_est_perm;

END;
/


CREATE OR REPLACE FUNCTION OSE.UM_EST_CTR_PERM_OU_VAC(p_statut IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
    UM_EST_CTR_PERM_OU_VAC
===============================================================*/
-- Retourne true si le statut CONTRACTUEL SIHAM PEUT ËTRE PERMANENT OU VACATAIRE DANS OSE
v_est_perm_ou_vac     number(1) := 0;

BEGIN
	-- ##A_PERSONNALISER_CHOIX_SIHAM##
    if p_statut in ('C0102','C0322') then
        v_est_perm_ou_vac := 1;
    else    
        v_est_perm_ou_vac := 0;
    end if;
    return v_est_perm_ou_vac;

END;
/

CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_VOIRIE(p_code_voirie IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_VOIRIE -- v2.2
===============================================================*/
v_id_voirie	 NUMBER(9) := 0;

CURSOR cur_voirie IS
	 select id
	 from OSE.UM_VOIRIE
	 where code = p_code_voirie;

BEGIN
	OPEN cur_voirie;
	FETCH cur_voirie INTO v_id_voirie;

	return v_id_voirie;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_VOIRIE_LIB(p_lib_voirie IN VARCHAR2) RETURN VARCHAR2 IS
/* =============================================================
	UM_EXISTE_VOIRIE_LIB -- v2.2
===============================================================*/
v_code_voirie	 VARCHAR2(5) := '';

CURSOR cur_voirie IS
	select code_voirie from
	(
		select distinct min(code) as code_voirie
		 from OSE.UM_VOIRIE
		 where libelle = trim(p_lib_voirie)
		 union
		 select min(code) as code_voirie
		 from OSE.UM_VOIRIE
		 where source_code = trim(p_lib_voirie)
	) v_voirie
	where code_voirie is not null
	;

BEGIN
	OPEN cur_voirie;
	FETCH cur_voirie INTO v_code_voirie;

	return v_code_voirie;
END;
/


CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_ADR_NUM_COMPL(p_lettre_num_compl IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_ADR_NUM_COMPL -- v2.2
===============================================================*/
v_id_num_compl	 NUMBER(9) := 0;

CURSOR cur_num_compl IS
	 select id
	 from OSE.ADRESSE_NUMERO_COMPL
	 where trim(code) = p_lettre_num_compl;

BEGIN
	OPEN cur_num_compl;
	FETCH cur_num_compl INTO v_id_num_compl;

	return v_id_num_compl;
END;
/


CREATE OR REPLACE FUNCTION OSE.UM_AFFICH_UO_INF(p_uo IN varchar2) RETURN VARCHAR2 IS
/* =============================================================
	UM_AFFICH_UO_INF
===============================================================*/
v_c_uo 	         varchar2(10);
v_lc_uo          varchar2(25);
v_c_uo_ll_uo     varchar2(36);
v_temp_uo		 varchar2(10);

-- !! OSE : via DBLINK sur SIHAM
-- UM_AFFICH_UO_INF: pour remonter le département quand HE% (dont IUT FDS)
-- Récupere le code uo + '#' + ll_uo de la structure de x niveaux au dessus
-- conseil : a appeller avec p_uo de l'affectation fine

CURSOR cur_infos_uo_niveau_hie IS
	select 
	-- Renseigner ce select, se baser sur le niveau de hierarchie voulu suivant si recherche, direction, compo péda...
	-- pour cela se baser sur le tableau excel pour le choix du niveau d'UO /branches : choix_niveaux_UO_pour_OSE.xlsx
	
	case 
		-- ##A_PERSONNALISER_CHOIX_SIHAM## 
		when UM_EST_STRUCT_MANU(p_uo) = 'O' then trim(p_uo) -- v1.12 - 12/09/2019 - MYP - ajout structure EDTTSD - spéciale OREC car n existe pas dans Siham
		-- recherchedepart scient
		when v_temp_uo like 'HR1%' then ''
		-- Recherche autres
		when v_temp_uo like 'HR%'  then ''
		-- directions
		when v_temp_uo like 'HD%'  then ''
		-- composantes pedagogisques
		when v_temp_uo like 'HE%'  then trim(UM_CODE_UO_NIVEAU_DESSUS(v_temp_uo, UM_NIVEAU_UO(v_temp_uo)-6))
		-- Autres - Fondations UM
		when v_temp_uo like 'HF%'  then ''
		-- Autres -PrésidenceUM
		when v_temp_uo like 'HG%'  then ''
		-- Autres -Services Communs UM
		when v_temp_uo like 'HS%'  then ''
		-- Autres - Partenaires exterieurs UM pour AMUE et CINES
		when v_temp_uo like 'HX%'  then ''
		else
			''
	end as c_uo_niveau_voulu
	from dual;	

BEGIN
	--- ##A_PERSONNALISER_CHOIX_SIHAM##   composantes ex-aes ou ex-isem qui ont fusionné en moma
	IF (p_uo like 'HER%' or p_uo like 'HEG%') then 
		v_temp_uo := '';  -- Pour MOMA pas de departement d'ens
	ELSE 
		v_temp_uo := p_uo;
	END IF;
			
	-- recup code uo niveau defini pour extraction badge principal vers ARD
   OPEN cur_infos_uo_niveau_hie;
    FETCH cur_infos_uo_niveau_hie  INTO v_c_uo;

	-- recup lib court correspondant au departement
	-- MYP corrigé le 12/06/2018 : sortir de la boucle sinon up n'a pas le flag type ENS et pas renseigné
	select trim(b.lboush) INTO v_lc_uo
	from 
		hr.ze00@SIHAM.WORLD a,
		hr.ze01@SIHAM.WORLD b
	where trim(a.idou00) = v_c_uo --v1.9
	and trim(a.idos00) = 'HIE'
	and a.nudoss = b.nudoss
	and a.tyou00 = 'ENS'   -- uo de type enseignement TYPE ENS
	and b.cdlang = 'F' 
	;
	
	IF v_lc_uo is null then 
		v_c_uo := '';  -- Pas de departement d'ens : tout à blanc
	END IF;
	
	
	IF v_lc_uo is null then 
		v_c_uo := '';  -- Pas de departement d'ens : tout à blanc
	END IF;
	
	-- retourne le code et le libelle court
	v_c_uo_ll_uo := v_c_uo||'#'||v_lc_uo;
	
    return trim(v_c_uo_ll_uo);

END;