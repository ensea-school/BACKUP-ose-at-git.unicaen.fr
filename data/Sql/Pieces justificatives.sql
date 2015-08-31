/**************************************************************************************************
    Liste des PJ attendues pour un intervenant.
  
    pj.obligatoire = 1 <=> PJ obligatoire 
    pj.obligatoire = 0 <=> PJ facultative

    pj.force = 1 <=> Forçage, nécessaire pour rendre obligatoire la PJ RIB lorsque le numéro saisi 
                     dans le dossier diffère de celui importé.

 *******************************************************************************************************/

select pj.id pj_id, d.id dossier_id, d.intervenant_id, d.nom_usuel, 
    tpj.id tpj_id, tpj.code, tpj.libelle, pj.obligatoire, pj.force, pj.histo_modification, pj.histo_destruction
from piece_jointe pj
join type_piece_jointe tpj on tpj.id = pj.type_piece_jointe_id
join dossier d on d.id = pj.dossier_id
join intervenant i on i.id = d.intervenant_id
where i.source_code = '38272'
--and pj.histo_destruction is null
order by pj.histo_modification, pj.histo_destruction;


/*******************************************************************************************************
    Ajout d'une PJ à fournir par un intervenant.

    NB: le fichier pourra ensuite être déposé via l'appli.

 *******************************************************************************************************/

Insert into PIECE_JOINTE (ID,TYPE_PIECE_JOINTE_ID,DOSSIER_ID,HISTO_CREATEUR_ID,HISTO_MODIFICATEUR_ID,VALIDATION_ID,FORCE,OBLIGATOIRE)
values (
  PIECE_JOINTE_id_seq.nextval,
  (select id from type_piece_jointe where code = 'RIB'),
  (select id from dossier where INTERVENANT_ID = (select id from intervenant where source_code = '96492' and annee_id = 2014)),
  1,
  1,
  null,
  0,
  1
);