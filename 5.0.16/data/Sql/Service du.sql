select
  i.id,
  si.service_statutaire,
  sd.heures
from
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  LEFT JOIN service_du sd ON sd.intervenant_id = i.id 
    AND sd.histo_destruction is null
    --AND 1 = ose_divers.comprise_entre( sd.histo_creation, sd.histo_destruction ) and sd.annee_id = 2014
WHERE
  si.service_statutaire > 0
  AND si.service_statutaire <> nvl(sd.heures,0);


-- mise à niveau de tous les services dus
UPDATE service_du SET heures = (

  select si.service_statutaire FROM statut_intervenant si
  join intervenant i ON i.statut_id = si.id
  where i.id = service_du.intervenant_id

)WHERE annee_id = 2014;


INSERT INTO OSE.SERVICE_DU (
  id,
  intervenant_id,
  annee_id,
  heures,
  histo_createur_id,
  histo_modificateur_id
)VALUES(
  service_du_id_seq.nextval,
  596,
  2014,
  192,
  OSE_PARAMETRE.GET_OSE_USER(),
  OSE_PARAMETRE.GET_OSE_USER()
);