/**************************************************************************************************
    Liste des PJ attendues pour un intervenant.
  
    pj.obligatoire = 1 <=> PJ obligatoire 
    pj.obligatoire = 0 <=> PJ facultative

    pj.force = 1 <=> Forçage, nécessaire pour rendre obligatoire la PJ RIB lorsque le numéro saisi 
                     dans le dossier diffère de celui importé.

 *******************************************************************************************************/

SELECT
  i.prenom || ' ' || i.nom_usuel i_nom,
  i.annee_id annee,
  pj.histo_creation creation,
  --tpj.code, 
  tpj.libelle, pj.obligatoire, pj.force,
  CASE
    WHEN f.id IS NULL THEN 'non fourni'
    WHEN f.histo_destruction IS NOT NULL THEN 'histo ' || to_char(f.histo_destruction,'dd/mm/yyyy')
    ELSE 'fourni le ' || to_char(v.histo_creation,'dd/mm/yyyy')
  END fichier,
  CASE
    WHEN v.id IS NULL THEN 'non validée'
    WHEN v.histo_destruction IS NOT NULL THEN 'histo ' || to_char(v.histo_destruction,'dd/mm/yyyy')
    ELSE 'validée le ' || to_char(v.histo_creation,'dd/mm/yyyy')
  END validation,
  
  pj.histo_destruction pj_del,
  i.id i_id,
  d.id d_id,
  pj.id pj_id,
  f.id f_id,
  v.id v_id
FROM
  piece_jointe pj
  JOIN dossier d ON d.id = pj.dossier_id AND 1 = ose_divers.comprise_entre( d.histo_creation, d.histo_destruction )
  JOIN intervenant i ON i.id = d.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  JOIN type_piece_jointe tpj ON tpj.id = pj.TYPE_PIECE_JOINTE_ID AND 1 = ose_divers.comprise_entre( tpj.histo_creation, tpj.histo_destruction )
  LEFT JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
  LEFT JOIN fichier f ON f.id = pjf.fichier_id
  LEFT JOIN validation v ON v.id = pj.validation_id
WHERE
    
  i.id = 5573;
  
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