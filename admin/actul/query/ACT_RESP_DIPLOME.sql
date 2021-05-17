select anu.cod_anu annee_id
     , dip.libelle_long
	 , ltrim(rtrim(dip.code)) code
     , usr.nom
     , usr.prenom
     , usr.login
from PREV_DIPLOME dip
   , PREV_USERS_DIPLOMES udi
   , USER usr
   , PREV_PROJET anu
where udi.prev_diplome_id = dip.id
  and usr.login = udi.prev_user_login
  and anu.cod_anu = :v_annee
  and anu.temoin_actif = 1
order by dip.code, usr.nom, usr.prenom