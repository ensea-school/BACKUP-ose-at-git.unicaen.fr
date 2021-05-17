select anu.cod_anu annee_id
     , etp.libelle_long
	 , ltrim(rtrim(etp.code)) code
     , usr.nom
     , usr.prenom
     , usr.login
from PREV_ETAPE etp
   , PREV_USERS_ETAPES udi
   , USER usr
   , PREV_PROJET anu
where udi.prev_etape_id = etp.id
  and usr.login = udi.prev_user_login
  and anu.cod_anu = :v_annee
  and anu.temoin_actif = 1
order by etp.code, usr.nom, usr.prenom