select anu.cod_anu annee_id
     , vdi.libelle_long
	 , concat(ltrim(rtrim(dip.code)),'_',vdi.code)   code_vdi
     , usr.nom
     , usr.prenom
     , usr.login
from PREV_VERSION_DIPLOME vdi
   , PREV_DIPLOME dip
   , PREV_USERS_VERSION_DIPLOMES udi
   , USER usr
   , PREV_PROJET anu
where udi.prev_version_diplome_id = vdi.id
  and vdi.prev_diplome_id = dip.id
  and usr.login = udi.prev_user_login
  and anu.cod_anu = :v_annee
  and anu.temoin_actif = 1
order by vdi.code, usr.nom, usr.prenom