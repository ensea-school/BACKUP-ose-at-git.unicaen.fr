SELECT
  tc.annee_id,
  i.id i_id,
  i.prenom || ' ' || i.nom_usuel i_nom,
  i.source_code i_code,
  
  tc.peut_avoir_contrat,
  s.id s_id,
  s.libelle_court structure,
  
  tc.nbvh,
  tc.edite,
  tc.signe
FROM 
  tbl_contrat tc
  JOIN intervenant i ON i.id = tc.intervenant_id
  JOIN structure s ON s.id = tc.structure_id
WHERE
  intervenant_id = 548
;



  SELECT 
  s.id,
    i.annee_id                                                                annee_id,
    i.id                                                                      intervenant_id,
    si.peut_avoir_contrat                                                     peut_avoir_contrat,
    NVL(ep.structure_id, i.structure_id)                                      structure_id,
    CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END edite,
    CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END signe
  FROM
              intervenant                 i
              
         JOIN statut_intervenant         si ON si.id = i.statut_id
         
         JOIN service                     s ON s.intervenant_id = i.id
                                           AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
         
         JOIN volume_horaire             vh ON vh.service_id = s.id
                                           AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
                                           AND vh.heures <> 0
    
         JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
         
         JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                           AND evh.code IN ('valide', 'contrat-edite', 'contrat-signe')
  
    LEFT JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id
    
  WHERE
    1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
    AND NOT (si.peut_avoir_contrat = 0 AND evh.code = 'valide')
    AND i.id = 548
    AND 8468 = NVL(ep.structure_id, i.structure_id);
    
    
select * from volume_horaire where service_id = 24374;