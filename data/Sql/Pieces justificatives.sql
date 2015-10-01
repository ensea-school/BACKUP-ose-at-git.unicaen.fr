/**************************************************************************************************
    Liste des PJ attendues pour un intervenant.
  
    pj.obligatoire = 1 <=> PJ obligatoire 
    pj.obligatoire = 0 <=> PJ facultative

    pj.force = 1 <=> Forçage, nécessaire pour rendre obligatoire la PJ RIB lorsque le numéro saisi 
                     dans le dossier diffère de celui importé.

 *******************************************************************************************************/

select
  i.id i_id,
  d.id d_id,
  pj.id pj_id,
  pj.histo_destruction pj_del,
  i.prenom || ' ' || i.nom_usuel i_nom,
  i.annee_id annee,
  tpj.code, tpj.libelle, pj.obligatoire, pj.force
from
  piece_jointe pj
  JOIN dossier d ON d.id = pj.dossier_id AND 1 = ose_divers.comprise_entre( d.histo_creation, d.histo_destruction )
  JOIN intervenant i ON i.id = d.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  JOIN type_piece_jointe tpj ON tpj.id = pj.TYPE_PIECE_JOINTE_ID AND 1 = ose_divers.comprise_entre( tpj.histo_creation, tpj.histo_destruction )
where
  
  
  AND i.source_code = 20259;
  
SELECT * FROM intervenant WHERE source_code = '20259';
  
/

BEGIN
  OSE_PJ.UPDATE_INTERVENANT( 1481 ); -- nécessité de MAJ des PJ!!
END;

/

SELECT
  tpjs.id tpjs_id,
  tpj.code tpj_code,
  tpj.libelle tpj_libelle,
  si.source_code si_code,
  si.libelle si_libelle,
  tpjs.obligatoire,
  tpjs.seuil_hetd,
  tpjs.premier_recrutement
FROM
  type_piece_jointe tpj
  JOIN type_piece_jointe_statut tpjs ON tpjs.type_piece_jointe_id = tpj.id AND 1 = ose_divers.comprise_entre( tpjs.histo_creation, tpjs.histo_destruction )
  JOIN statut_intervenant si ON si.id = tpjs.statut_intervenant_id AND 1 = ose_divers.comprise_entre( si.histo_creation, si.histo_destruction )
WHERE
  1 = ose_divers.comprise_entre( tpj.histo_creation, tpj.histo_destruction )
ORDER BY
  tpj_code, si_code