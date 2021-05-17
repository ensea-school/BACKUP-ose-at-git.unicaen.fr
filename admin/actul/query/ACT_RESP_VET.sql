select anu.cod_anu annee_id
     , vet.libelle
	 , concat(ltrim(rtrim(etp.code)),'_',vty.code)   code_vet
     , usr.nom
     , usr.prenom
     , usr.login
from PREV_VERSION_ETAPE vet
   , PREV_ETAPE etp
   , PREV_VET_TYPINS vty
   , PREV_USERS_VERSION_ETAPES udi
   , USER usr
   , PREV_PROJET anu
where udi.prev_version_etape_id = vet.id
  and vet.prev_etape_id = etp.id
  and vty.prev_vet_id = vet.id
  and usr.login = udi.prev_user_login
  and anu.cod_anu= :v_annee
  and anu.temoin_actif = 1
order by vty.code, usr.nom, usr.prenom