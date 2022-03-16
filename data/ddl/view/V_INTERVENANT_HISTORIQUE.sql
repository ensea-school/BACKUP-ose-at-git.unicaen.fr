CREATE
OR REPLACE FORCE VIEW V_INTERVENANT_HISTORIQUE AS
WITH historique AS(
--Initialisation des données personnelles
SELECT
  d.intervenant_id   intervenant_id,
  '1 - Données personnelles'                         categorie,
  'Début de la saisie des données personnelles'              label,
  d.histo_creation                          histo_date,
  d.histo_createur_id                              histo_createur_id,
  u.display_name                       histo_user,
  'glyphicon glyphicon-ok' icon,
  1                        ordre
FROM
  intervenant_dossier d
  JOIN utilisateur u ON u.id = d.histo_createur_id
  WHERE d.histo_destruction IS NULL

  UNION ALL
--Dernière modification des données personnelles
  SELECT
  d.intervenant_id   intervenant_id,
  '1 - Données personnelles'                         categorie,
  'Dernière modification des données personnelles'              label,
  d.histo_modification                          histo_date,
  d.histo_modificateur_id                              histo_createur_id,
  u.display_name                       histo_user,
  'glyphicon glyphicon-ok' icon,
  1                        ordre
FROM
  intervenant_dossier d
  JOIN utilisateur u ON u.id = d.histo_modificateur_id
  WHERE d.histo_destruction IS NULL AND d.histo_modification != d.histo_creation

  UNION ALL

--Validation des données personnelles
SELECT
  d.intervenant_id   intervenant_id,
  '1 - Données personnelles'                         categorie,
  'Validation des données personnelles'              label,
  v.histo_creation                     histo_date,
  v.histo_createur_id                       histo_createur_id,
  u.display_name                       histo_user,
  'glyphicon glyphicon-ok' icon,
  1                        ordre
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'DONNEES_PERSO_PAR_COMP'
  JOIN intervenant_dossier d ON d.histo_destruction IS NULL AND d.intervenant_id = v.intervenant_id

  UNION ALL
--Dépôt des pièces justificatives
SELECT pj.intervenant_id                                      intervenant_id,
	   '2 - Pièces justificatives'		                   categorie,
       'Dépôt pièce justificative : ' || tpj.libelle          label,
       pj.histo_creation                                      histo_date,
       pj.histo_createur_id                                   histo_createur_id,
       u.display_name                                         histo_user,
       'glyphicon glyphicon-ok'                               icon,
       2													  ordre
FROM piece_jointe pj
         JOIN type_piece_jointe tpj ON pj.type_piece_jointe_id = tpj.id
         JOIN utilisateur u ON u.id = pj.histo_createur_id
WHERE pj.histo_destruction IS NULL

    UNION ALL
--Validation des pièces justificatives
 SELECT
  pj.intervenant_id   intervenant_id,
  '2 - Pièces justificatives'		                   categorie,
  'Validation pièce justificative : ' || tpj.libelle               label,
  v.histo_creation           histo_date,
  v.histo_createur_id        histo_createur_id,
  u.display_name     histo_user,
  'glyphicon glyphicon-ok' icon,
  2                        ordre
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN piece_jointe pj ON pj.validation_id = v.id
  JOIN type_piece_jointe tpj ON pj.type_piece_jointe_id = tpj.id

UNION ALL
--Dernière modification du service prévisionnel par composante
SELECT s.intervenant_id                                                  intervenant_id,
	   '3 - Services prévisionnels'		                                     categorie,
       'Modification/Ajout du service prévisionnel pour la composante ' || MAX(st.libelle_court)                            label,
       MAX(vh.histo_modification)                                                 histo_date,
       MAX(vh.histo_modificateur_id) KEEP (DENSE_RANK FIRST ORDER BY vh.histo_modification DESC)   histo_createur_id,
       MAX(u.display_name) KEEP (DENSE_RANK FIRST ORDER BY vh.histo_modification DESC)    histo_user,
--       FIRST_VALUE(vh.histo_modificateur_id) OVER (partition by s.intervenant_id order by vh.histo_modification desc) histo_createur_id,
       --FIRST_VALUE(u.display_name) OVER (partition by s.intervenant_id order by vh.histo_modification desc) histo_user,
       'glyphicon glyphicon-calendar'                                    icon,
       3																 ordre
FROM volume_horaire vh
         JOIN service s ON s.id = vh.service_id
         JOIN element_pedagogique ep ON s.element_pedagogique_id = ep.id
		 JOIN structure st ON st.id = ep.structure_id
         JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = 'PREVU'
         JOIN periode p ON p.id = vh.periode_id
         JOIN type_intervention ti ON ti.id = vh.type_intervention_id
         JOIN utilisateur u ON u.id = vh.histo_modificateur_id
         LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id
         GROUP BY s.intervenant_id , ep.structure_id

UNION ALL
--Création du contrat par composante
SELECT
  C.intervenant_id              intervenant_id,
   '4 - Contractualisation'		                                     categorie,
       'Création ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' || C.id || ' pour la composante ' || s.libelle_court                             label,
       C.histo_creation                                                 histo_date,
       C.histo_createur_id                                            histo_createur_id,
       u.display_name											       histo_user,
       'fas fa-book' 				                                   icon,
       4																 ordre
FROM
  contrat C
  JOIN type_contrat tc ON tc.id = C.type_contrat_id
  JOIN structure s ON s.id = C.structure_id
  JOIN utilisateur u ON u.id = C.histo_createur_id
WHERE
	C.histo_destruction IS NULL

UNION ALL
--Validation du contrat par composante
SELECT
 C.intervenant_id              intervenant_id,
   '4 - Contractualisation'		                                     categorie,
       'Validation ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' || C.id || ' pour la composante ' || s.libelle_court                             label,
       v.histo_creation                                                 histo_date,
       v.histo_createur_id                                            histo_createur_id,
       u.display_name											       histo_user,
       'fas fa-book' 				                                   icon,
       4																 ordre
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN contrat C ON C.validation_id = v.id AND C.histo_destruction IS NULL
  JOIN type_contrat tc ON tc.id = C.type_contrat_id
  JOIN structure s ON s.id = C.structure_id
WHERE
  v.histo_destruction IS NULL

  UNION ALL
--Dépôt du contrat signé par composante
SELECT
 C.intervenant_id              intervenant_id,
   '4 - Contractualisation'		                                     categorie,
       'Dépôt ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' || C.id || ' signé pour la composante ' || s.libelle_court                             label,
       f.histo_creation                                                 histo_date,
       f.histo_createur_id                                            histo_createur_id,
       u.display_name											       histo_user,
       'fas fa-book' 				                                   icon,
       4																 ordre
FROM
  fichier f
  JOIN contrat_fichier cf ON cf.fichier_id  = f.id AND histo_destruction IS NULL
  JOIN contrat C ON C.id = cf.contrat_id
  JOIN type_contrat tc ON tc.id = C.type_contrat_id
  JOIN structure s ON s.id = C.structure_id
  JOIN utilisateur u ON u.id = f.histo_createur_id
WHERE
  f.histo_destruction IS NULL

    UNION ALL
--Date de retour signé du contrat par composante
SELECT
 C.intervenant_id              intervenant_id,
   '4 - Contractualisation'		                                     categorie,
       'Date de retour signée ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' || C.id || ' pour la composante ' || s.libelle_court                             label,
       C.histo_modification                                           histo_date,
       C.histo_modificateur_id                                            histo_createur_id,
       u.display_name											       histo_user,
       'fas fa-book' 				                                   icon,
       4																 ordre
FROM
  contrat C
  JOIN type_contrat tc ON tc.id = C.type_contrat_id
  JOIN structure s ON s.id = C.structure_id
  JOIN utilisateur u ON u.id = C.histo_modificateur_id
WHERE
  C.histo_destruction IS NULL AND C.date_retour_signe IS NOT NULL

  UNION ALL
-- Envoi du contrat par email par composante
SELECT
 C.intervenant_id              intervenant_id,
   '4 - Contractualisation'		                                     categorie,
       'Envoi par mail ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' || C.id || ' pour la composante ' || s.libelle_court                             label,
       C.histo_modification                                           histo_date,
       C.histo_modificateur_id                                            histo_createur_id,
       u.display_name											       histo_user,
       'fas fa-book' 				                                   icon,
       4																 ordre
FROM
  contrat C
  JOIN type_contrat tc ON tc.id = C.type_contrat_id
  JOIN structure s ON s.id = C.structure_id
  JOIN utilisateur u ON u.id = C.histo_modificateur_id
WHERE
  C.histo_destruction IS NULL AND C.date_envoi_email IS NOT NULL


  UNION ALL


--Dernière modification du service réalisé par composante
SELECT s.intervenant_id                                                  intervenant_id,
	   '5 - Services réalisés'		                                     categorie,
       'Modification/Ajout du service réalisé pour la composante ' || MAX(st.libelle_court)                 label,
       MAX(vh.histo_modification)                                                 histo_date,
       MAX(vh.histo_modificateur_id) KEEP (DENSE_RANK FIRST ORDER BY vh.histo_modification DESC)   histo_createur_id,
       MAX(u.display_name) KEEP (DENSE_RANK FIRST ORDER BY vh.histo_modification DESC)    histo_user,
       'glyphicon glyphicon-calendar'                                    icon,
       5																 ordre
FROM volume_horaire vh
         JOIN service s ON s.id = vh.service_id
         JOIN element_pedagogique ep ON s.element_pedagogique_id = ep.id
		 JOIN structure st ON st.id = ep.structure_id
         JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = 'REALISE'
         JOIN periode p ON p.id = vh.periode_id
         JOIN type_intervention ti ON ti.id = vh.type_intervention_id
         JOIN utilisateur u ON u.id = vh.histo_modificateur_id
         LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id
         GROUP BY s.intervenant_id, ep.structure_id


)
SELECT rownum id,
       categorie,
       ordre,
       intervenant_id,
       label,
       histo_date,
       histo_createur_id,
       histo_user,
       icon
FROM historique
ORDER BY intervenant_id, ordre ASC, histo_date ASC