select anu.cod_anu annee_id
     , dip.libelle_long libelle
	 , ltrim(rtrim(dip.code)) source_code
     , usr.nom usr_nom
     , usr.prenom usr_prenom
     , usr.login usr_login
from PREV_DIPLOME dip
   , PREV_USERS_DIPLOMES udi
   , USER usr
   , PREV_PROJET anu
where udi.prev_diplome_id = dip.id
  and usr.login = udi.prev_user_login
  and anu.cod_anu = :v_annee
  and anu.temoin_actif = 1
order by dip.code, usr.nom, usr.prenom