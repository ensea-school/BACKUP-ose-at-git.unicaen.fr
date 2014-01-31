--
-- Vue matérialisée des enseignants
--

drop MATERIALIZED view MV_ENSEIGNANT;
   
CREATE MATERIALIZED VIEW MV_ENSEIGNANT REFRESH NEXT SYSDATE + 10/24/60 AS 
select distinct
  tmp.no_individu id,
  tmp.type,
	tmp.nom_usuel,
	tmp.prenom,
	tmp.nom_patronymique,
	tmp.no_individu,
  nvl(str_level2_code@harpprod(tmp.c_structure), tmp.c_structure) cmp,
  tmp.c_structure,
	min(tmp.date_deb) date_deb,
	max(tmp.date_fin) date_fin,
	tmp.no_e_mail,
	tmp.no_telephone,
  SYSTIMESTAMP
from (
  SELECT
    'Permanent' type,
    i.nom_usuel,
    initcap(i.prenom) prenom,
    i.nom_patronymique,
    ltrim(TO_CHAR(i.no_individu,'99999999')) no_individu,
    a.c_structure,
    a.d_deb_affectation date_deb,
    a.d_fin_affectation date_fin,
    a.c_structure c_structure2,
    im.no_e_mail,
    it.no_telephone
  FROM affectation@harpprod a,
    carriere@harpprod c,
    individu@harpprod i,
    type_population@harpprod tp,
    individu_e_mail@harpprod im,
    individu_telephone@harpprod it
  WHERE a.no_dossier_pers  = c.no_dossier_pers
  AND a.no_seq_carriere    = c.no_seq_carriere
  AND tp.c_type_population = c.c_type_population
  AND a.no_dossier_pers    = i.no_individu
  AND tp.tem_enseignant    = 'O' 
  AND (SYSDATE BETWEEN a.d_deb_affectation AND NVL (a.d_fin_affectation,SYSDATE))
  AND im.NO_INDIVIDU(+)       = i.NO_INDIVIDU
  AND it.no_individu(+)       = i.NO_INDIVIDU
  AND it.tem_tel_principal(+) = 'O'
  AND it.tem_tel(+)           = 'O'
  
  UNION
  
  SELECT
    'Extérieur' type,
    i.nom_usuel,
    initcap(i.prenom) prenom,
    i.nom_patronymique,
    ltrim(TO_CHAR(i.no_individu,'99999999')) no_individu,
    a.c_structure,
    a.d_deb_affectation date_deb,
    (a.d_fin_affectation+30*6) date_fin,
    a.c_structure,
    im.no_e_mail,
    it.no_telephone    
  FROM affectation@harpprod a,
    contrat_travail@harpprod ct,
    individu@harpprod i,
    type_contrat_travail@harpprod tct,
    individu_e_mail@harpprod im,
    individu_telephone@harpprod it
  WHERE a.no_dossier_pers     = ct.no_dossier_pers
  AND tct.c_type_contrat_trav = ct.c_type_contrat_trav
  AND a.no_contrat_travail    = ct.no_contrat_travail
  AND a.no_dossier_pers       = i.no_individu
  AND tct.tem_enseignant      = 'O' 
  AND (SYSDATE BETWEEN a.d_deb_affectation AND NVL (a.d_fin_affectation,SYSDATE))
  AND im.NO_INDIVIDU(+)       = i.NO_INDIVIDU
  AND it.no_individu(+)       = i.NO_INDIVIDU
  AND it.tem_tel_principal(+) = 'O'
  AND it.tem_tel(+)           = 'O'
  
  UNION
  
  SELECT
    'Autre' type,
    i.nom_usuel,
    initcap(i.prenom) prenom,
    i.nom_patronymique,
    ltrim(TO_CHAR(i.no_individu,'99999999')) no_individu,
    ch.c_structure,
    ch.d_deb_str_trav date_deb,
    (ch.d_fin_str_trav+30*6) date_fin,
    ch.c_structure,
    im.no_e_mail,
    it.no_telephone
  FROM chercheur@harpprod ch,
    individu@harpprod i,
    individu_e_mail@harpprod im,
    individu_telephone@harpprod it
  WHERE ch.no_individu = i.no_individu
  AND (SYSDATE BETWEEN ch.d_deb_str_trav AND NVL (ch.d_fin_str_trav,SYSDATE))
  AND im.NO_INDIVIDU(+)       = i.NO_INDIVIDU
  AND it.no_individu(+)       = i.NO_INDIVIDU
  AND it.tem_tel_principal(+) = 'O'
  AND it.tem_tel(+)           = 'O'
  
  UNION	
  
  SELECT 
    'Biatss' type,
    i.NOM_USUEL,
    initcap(i.PRENOM) prenom,
    i.NOM_PATRONYMIQUE,
    ltrim(TO_CHAR(i.no_individu,'99999999')) no_individu,
    fe.CMP,
    fe.DEBUT,
    fe.FIN,
    fe.CMP c_structure,
    im.no_e_mail no_e_mail,
    it.no_telephone no_telephone
  FROM 
    ucbn_flag_enseignant@harpprod fe,
    INDIVIDU@harpprod i,
    INDIVIDU_E_MAIL@harpprod im,
    individu_telephone@harpprod it
  WHERE fe.NO_INDIVIDU = i.NO_INDIVIDU
  AND im.NO_INDIVIDU(+) = i.NO_INDIVIDU
  AND it.NO_INDIVIDU(+) = i.NO_INDIVIDU
  AND it.tem_tel_principal(+) = 'O'
  AND it.tem_tel(+)           = 'O'
	) tmp
group by
  tmp.type,
	tmp.nom_usuel,
	tmp.prenom,
	tmp.nom_patronymique,
	tmp.no_individu,
  nvl(str_level2_code@harpprod(tmp.c_structure), tmp.c_structure),
  tmp.c_structure,
	tmp.no_e_mail,
	tmp.no_telephone;
  
  
--
-- Vue standard des enseignants
--

CREATE OR REPLACE VIEW V_ENSEIGNANT AS 
select distinct
  tmp.no_individu id,
  tmp.type,
	tmp.nom_usuel,
	tmp.prenom,
	tmp.nom_patronymique,
	tmp.no_individu,
  nvl(str_level2_code@harpprod(tmp.c_structure), tmp.c_structure) cmp,
  tmp.c_structure,
	min(tmp.date_deb) date_deb,
	max(tmp.date_fin) date_fin,
	tmp.no_e_mail,
	tmp.no_telephone
from (
  SELECT
    'Permanent' type,
    i.nom_usuel,
    initcap(i.prenom) prenom,
    i.nom_patronymique,
    ltrim(TO_CHAR(i.no_individu,'99999999')) no_individu,
    a.c_structure,
    a.d_deb_affectation date_deb,
    a.d_fin_affectation date_fin,
    a.c_structure c_structure2,
    im.no_e_mail,
    it.no_telephone
  FROM affectation@harpprod a,
    carriere@harpprod c,
    individu@harpprod i,
    type_population@harpprod tp,
    individu_e_mail@harpprod im,
    individu_telephone@harpprod it
  WHERE a.no_dossier_pers  = c.no_dossier_pers
  AND a.no_seq_carriere    = c.no_seq_carriere
  AND tp.c_type_population = c.c_type_population
  AND a.no_dossier_pers    = i.no_individu
  AND tp.tem_enseignant    = 'O' 
  AND (SYSDATE BETWEEN a.d_deb_affectation AND NVL (a.d_fin_affectation,SYSDATE))
  AND im.NO_INDIVIDU(+)       = i.NO_INDIVIDU
  AND it.no_individu(+)       = i.NO_INDIVIDU
  AND it.tem_tel_principal(+) = 'O'
  AND it.tem_tel(+)           = 'O'
  
  UNION
  
  SELECT
    'Extérieur' type,
    i.nom_usuel,
    initcap(i.prenom) prenom,
    i.nom_patronymique,
    ltrim(TO_CHAR(i.no_individu,'99999999')) no_individu,
    a.c_structure,
    a.d_deb_affectation date_deb,
    (a.d_fin_affectation+30*6) date_fin,
    a.c_structure,
    im.no_e_mail,
    it.no_telephone    
  FROM affectation@harpprod a,
    contrat_travail@harpprod ct,
    individu@harpprod i,
    type_contrat_travail@harpprod tct,
    individu_e_mail@harpprod im,
    individu_telephone@harpprod it
  WHERE a.no_dossier_pers     = ct.no_dossier_pers
  AND tct.c_type_contrat_trav = ct.c_type_contrat_trav
  AND a.no_contrat_travail    = ct.no_contrat_travail
  AND a.no_dossier_pers       = i.no_individu
  AND tct.tem_enseignant      = 'O' 
  AND (SYSDATE BETWEEN a.d_deb_affectation AND NVL (a.d_fin_affectation,SYSDATE))
  AND im.NO_INDIVIDU(+)       = i.NO_INDIVIDU
  AND it.no_individu(+)       = i.NO_INDIVIDU
  AND it.tem_tel_principal(+) = 'O'
  AND it.tem_tel(+)           = 'O'
  
  UNION
  
  SELECT
    'Autre' type,
    i.nom_usuel,
    initcap(i.prenom) prenom,
    i.nom_patronymique,
    ltrim(TO_CHAR(i.no_individu,'99999999')) no_individu,
    ch.c_structure,
    ch.d_deb_str_trav date_deb,
    (ch.d_fin_str_trav+30*6) date_fin,
    ch.c_structure,
    im.no_e_mail,
    it.no_telephone
  FROM chercheur@harpprod ch,
    individu@harpprod i,
    individu_e_mail@harpprod im,
    individu_telephone@harpprod it
  WHERE ch.no_individu = i.no_individu
  AND (SYSDATE BETWEEN ch.d_deb_str_trav AND NVL (ch.d_fin_str_trav,SYSDATE))
  AND im.NO_INDIVIDU(+)       = i.NO_INDIVIDU
  AND it.no_individu(+)       = i.NO_INDIVIDU
  AND it.tem_tel_principal(+) = 'O'
  AND it.tem_tel(+)           = 'O'
  
  UNION	
  
  SELECT 
    'Biatss' type,
    i.NOM_USUEL,
    initcap(i.PRENOM) prenom,
    i.NOM_PATRONYMIQUE,
    ltrim(TO_CHAR(i.no_individu,'99999999')) no_individu,
    fe.CMP,
    fe.DEBUT,
    fe.FIN,
    fe.CMP c_structure,
    im.no_e_mail no_e_mail,
    it.no_telephone no_telephone
  FROM 
    ucbn_flag_enseignant@harpprod fe,
    INDIVIDU@harpprod i,
    INDIVIDU_E_MAIL@harpprod im,
    individu_telephone@harpprod it
  WHERE fe.NO_INDIVIDU = i.NO_INDIVIDU
  AND im.NO_INDIVIDU(+) = i.NO_INDIVIDU
  AND it.NO_INDIVIDU(+) = i.NO_INDIVIDU
  AND it.tem_tel_principal(+) = 'O'
  AND it.tem_tel(+)           = 'O'
	) tmp
group by
  tmp.type,
	tmp.nom_usuel,
	tmp.prenom,
	tmp.nom_patronymique,
	tmp.no_individu,
  nvl(str_level2_code@harpprod(tmp.c_structure), tmp.c_structure),
  tmp.c_structure,
	tmp.no_e_mail,
	tmp.no_telephone;