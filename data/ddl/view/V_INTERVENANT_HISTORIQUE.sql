CREATE
OR REPLACE FORCE VIEW V_INTERVENANT_HISTORIQUE AS
WITH historique AS (
--Initialisation des données personnelles
    SELECT d.intervenant_id                              intervenant_id,
           '1 - Données personnelles'                    categorie,
           'Début de la saisie des données personnelles' label,
           d.histo_creation                              histo_date,
           d.histo_createur_id                           histo_createur_id,
           u.display_name                                histo_user,
           'glyphicon glyphicon-ok'                      icon,
           1                                             ordre
    FROM intervenant_dossier d
             JOIN utilisateur u ON u.id = d.histo_createur_id
    WHERE d.histo_destruction IS NULL

    UNION ALL
--Dernière modification des données personnelles
    SELECT d.intervenant_id                                 intervenant_id,
           '1 - Données personnelles'                       categorie,
           'Dernière modification des données personnelles' label,
           d.histo_modification                             histo_date,
           d.histo_modificateur_id                          histo_createur_id,
           u.display_name                                   histo_user,
           'glyphicon glyphicon-ok'                         icon,
           1                                                ordre
    FROM intervenant_dossier d
             JOIN utilisateur u ON u.id = d.histo_modificateur_id
    WHERE d.histo_destruction IS NULL
      AND d.histo_modification != d.histo_creation

UNION ALL

--Validation des données personnelles
SELECT d.intervenant_id                      intervenant_id,
       '1 - Données personnelles'            categorie,
       'Validation des données personnelles' label,
       v.histo_creation                      histo_date,
       v.histo_createur_id                   histo_createur_id,
       u.display_name                        histo_user,
       'glyphicon glyphicon-ok'              icon,
       1                                     ordre
FROM validation v
         JOIN utilisateur u ON u.id = v.histo_createur_id
         JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'DONNEES_PERSO_PAR_COMP'
         JOIN intervenant_dossier d ON d.histo_destruction IS NULL AND d.intervenant_id = v.intervenant_id

UNION ALL
--Dépôt des pièces justificatives
SELECT pj.intervenant_id                             intervenant_id,
       '2 - Pièces justificatives'                   categorie,
       'Dépôt pièce justificative : ' || tpj.libelle label,
       pj.histo_creation                             histo_date,
       pj.histo_createur_id                          histo_createur_id,
       u.display_name                                histo_user,
       'glyphicon glyphicon-ok'                      icon,
       2                                             ordre
FROM piece_jointe pj
         JOIN type_piece_jointe tpj ON pj.type_piece_jointe_id = tpj.id
         JOIN utilisateur u ON u.id = pj.histo_createur_id

UNION ALL

--Suppression pièces justificatives
SELECT pj.intervenant_id                             intervenant_id,
       '2 - Pièces justificatives'                   categorie,
       'Suppression pièce justificative : ' || tpj.libelle label,
       pj.histo_destruction                             histo_date,
       pj.histo_destructeur_id                          histo_createur_id,
       u.display_name                                histo_user,
       'glyphicon glyphicon-ok'                      icon,
       2                                             ordre
FROM piece_jointe pj
         JOIN type_piece_jointe tpj ON pj.type_piece_jointe_id = tpj.id
         JOIN utilisateur u ON u.id = pj.histo_destructeur_id
WHERE pj.histo_destruction IS NOT NULL

UNION ALL
--Validation des pièces justificatives
SELECT pj.intervenant_id                                  intervenant_id,
       '2 - Pièces justificatives'                        categorie,
       'Validation pièce justificative : ' || tpj.libelle label,
       v.histo_creation                                   histo_date,
       v.histo_createur_id                                histo_createur_id,
       u.display_name                                     histo_user,
       'glyphicon glyphicon-ok'                           icon,
       2                                                  ordre
FROM validation v
         JOIN utilisateur u ON u.id = v.histo_createur_id
         JOIN piece_jointe pj ON pj.validation_id = v.id
         JOIN type_piece_jointe tpj ON pj.type_piece_jointe_id = tpj.id

UNION ALL
--Dernière modification du service prévisionnel par composante
SELECT s.intervenant_id                                                                          intervenant_id,
       '3 - Service prévisionnel et/ou service référentiel'                                                              categorie,
       'Modification/Ajout du service prévisionnel pour la composante ' || MAX(st.libelle_court) label,
       MAX(vh.histo_creation)                                                                histo_date,
       MAX(vh.histo_createur_id)                                                             KEEP (dense_rank FIRST ORDER BY vh.histo_modification DESC)   histo_createur_id, MAX(u.display_name) KEEP (dense_rank FIRST ORDER BY vh.histo_modification DESC)    histo_user,
       'glyphicon glyphicon-ok'                           icon,
       3                                                                                         ordre
FROM volume_horaire vh
         JOIN service s ON s.id = vh.service_id
         JOIN element_pedagogique ep ON s.element_pedagogique_id = ep.id
         JOIN STRUCTURE st ON st.id = ep.structure_id
         JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = 'PREVU'
         JOIN periode p ON p.id = vh.periode_id
         JOIN type_intervention ti ON ti.id = vh.type_intervention_id
         JOIN utilisateur u ON u.id = vh.histo_modificateur_id
         LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id
GROUP BY s.intervenant_id, ep.structure_id

UNION ALL
--Service référentiel uniquement pour les permanents
SELECT  s.intervenant_id                                                                     intervenant_id,
       '3 - Service prévisionnel et/ou service référentiel'                                 categorie,
        'Ajout service référentiel : ' || fr.libelle_court || ' pour la composante ' || str.libelle_court                      label,
       s.histo_modification                                                                histo_date,
       s.histo_modificateur_id                                                             histo_createur_id,
       u.display_name                                                        histo_user,
       'glyphicon glyphicon-ok'                                              icon,
       3                                ordre
FROM
  service_referentiel s
  JOIN fonction_referentiel fr ON fr.id = s.fonction_id
  JOIN utilisateur u ON u.id = s.histo_modificateur_id
  LEFT JOIN STRUCTURE str ON str.id = s.structure_id

 UNION ALL
--Suppression service référentiel uniquement pour les permanents
SELECT  s.intervenant_id                                                                     intervenant_id,
       '3 - Service prévisionnel et/ou service référentiel'                                 categorie,
        'Suppression service référentiel : ' || fr.libelle_court || ' pour la composante ' || str.libelle_court                      label,
       s.histo_destruction                                                                histo_date,
       s.histo_destructeur_id                                                             histo_createur_id,
       u.display_name                                                        histo_user,
       'glyphicon glyphicon-ok'                                              icon,
       3                                ordre
FROM
  service_referentiel s
  JOIN fonction_referentiel fr ON fr.id = s.fonction_id
  JOIN utilisateur u ON u.id = s.histo_destructeur_id
  LEFT JOIN STRUCTURE str ON str.id = s.structure_id
WHERE
 s.histo_destruction IS NOT NULL

 UNION ALL
--Validation du service prévisionnel
SELECT s.intervenant_id                                                                          intervenant_id,
       '3 - Service prévisionnel et/ou service référentiel'                                                              categorie,
       'Validation du service prévisionnel pour la composante ' || MAX(st.libelle_court) label,
       MAX(v.histo_modification)                                                                histo_date,
       MAX(v.histo_modificateur_id)                                                             KEEP (dense_rank FIRST ORDER BY v.histo_modification DESC)   histo_createur_id, MAX(u.display_name) KEEP (dense_rank FIRST ORDER BY v.histo_modification DESC)    histo_user,
       'glyphicon glyphicon-ok'                           icon,
       3                                                                                         ordre
FROM volume_horaire vh
         JOIN service s ON s.id = vh.service_id
         JOIN element_pedagogique ep ON s.element_pedagogique_id = ep.id
         JOIN STRUCTURE st ON st.id = ep.structure_id
         JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = 'PREVU'
         JOIN periode p ON p.id = vh.periode_id
         JOIN type_intervention ti ON ti.id = vh.type_intervention_id
         JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
         JOIN validation v ON v.id = vvh.validation_id
         JOIN utilisateur u ON u.id = v.histo_modificateur_id
         GROUP BY s.intervenant_id, ep.structure_id

         UNION ALL
         --validation du service référentiel
SELECT  s.intervenant_id                                                                     intervenant_id,
       '3 - Service prévisionnel et/ou service référentiel'                                 categorie,
        'Validation du service référentiel : ' || fr.libelle_court || ' pour la composante ' || str.libelle_court                      label,
       v.histo_modification                                                                histo_date,
       v.histo_modificateur_id                                                             histo_createur_id,
       u.display_name                                                        histo_user,
       'glyphicon glyphicon-ok'                                              icon,
       3                                ordre
FROM
  service_referentiel s
  JOIN validation_vol_horaire_ref vvhr ON s.id = vvhr.volume_horaire_ref_id
  JOIN validation v ON v.id = vvhr.validation_id
  JOIN fonction_referentiel fr ON fr.id = s.fonction_id
  JOIN utilisateur u ON u.id = v.histo_modificateur_id
  LEFT JOIN STRUCTURE str ON str.id = s.structure_id
WHERE
 s.histo_destruction IS NULL

 UNION ALL
 --Modification de service dû
  SELECT
     msd.intervenant_id                                                                     intervenant_id,
       '3 - Service prévisionnel et/ou service référentiel'                                 categorie,
        'Modification de service dû : ' || mss.libelle                              label,
       msd.histo_modification                                                                histo_date,
       msd.histo_modificateur_id                                                             histo_createur_id,
       u.display_name                                                        histo_user,
       'glyphicon glyphicon-ok'                                              icon,
       3                                ordre
FROM
  modification_service_du msd
  JOIN motif_modification_service mss ON mss.id = msd.motif_id
  JOIN utilisateur u ON u.id = msd.histo_modificateur_id
WHERE
  msd.histo_destruction IS NULL


UNION ALL
--Agrément conseil restreint et conseil académique
SELECT tbla.intervenant_id         intervenant_id,
       '4 - Agréments'          categorie,
       ta.libelle || CASE
                         WHEN s.id IS NULL THEN ''
                         ELSE ' ' ||
                              s.libelle_court END ||
       CASE
           WHEN a.date_decision IS NULL THEN ''
           ELSE ' (décision du ' ||
                to_char(a.date_decision, 'dd/mm/YYYY')
               || ')' END       label,
       a.histo_modification     histo_date,
       a.histo_modificateur_id  histo_createur_id,
       u.display_name           histo_user,
       'glyphicon glyphicon-ok' icon,
       4                        ordre
FROM tbl_agrement tbla
JOIN agrement a ON tbla.agrement_id = a.id
JOIN type_agrement ta ON ta.id = a.type_agrement_id
         JOIN utilisateur u ON u.id = a.histo_modificateur_id
         LEFT JOIN STRUCTURE s ON s.id = a.structure_id
WHERE a.histo_destruction IS NULL

UNION ALL
--Création du contrat par composante
SELECT C.intervenant_id                          intervenant_id,
       '5 - Contractualisation'                  categorie,
       'Création ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' || C.id ||
       ' pour la composante ' || s.libelle_court label,
       C.histo_creation                          histo_date,
       C.histo_createur_id                       histo_createur_id,
       u.display_name                            histo_user,
       'fas fa-book'                             icon,
       5                                         ordre
FROM contrat C
         JOIN type_contrat tc ON tc.id = C.type_contrat_id
         JOIN STRUCTURE s ON s.id = C.structure_id
         JOIN utilisateur u ON u.id = C.histo_createur_id
WHERE C.histo_destruction IS NULL

UNION ALL
--Validation du contrat par composante
SELECT C.intervenant_id                          intervenant_id,
       '5 - Contractualisation'                  categorie,
       'Validation ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' || C.id ||
       ' pour la composante ' || s.libelle_court label,
       v.histo_creation                          histo_date,
       v.histo_createur_id                       histo_createur_id,
       u.display_name                            histo_user,
       'fas fa-book'                             icon,
       5                                         ordre
FROM validation v
         JOIN utilisateur u ON u.id = v.histo_createur_id
         JOIN contrat C ON C.validation_id = v.id AND C.histo_destruction IS NULL
         JOIN type_contrat tc ON tc.id = C.type_contrat_id
         JOIN STRUCTURE s ON s.id = C.structure_id
WHERE v.histo_destruction IS NULL

UNION ALL
--Dépôt du contrat signé par composante
SELECT C.intervenant_id                                intervenant_id,
       '5 - Contractualisation'                        categorie,
       'Dépôt ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' || C.id ||
       ' signé pour la composante ' || s.libelle_court || ' (' || f.nom ||  ')' label,
       f.histo_creation                                histo_date,
       f.histo_createur_id                             histo_createur_id,
       u.display_name                                  histo_user,
       'fas fa-book'                                   icon,
       5                                               ordre
FROM fichier f
         JOIN contrat_fichier cf ON cf.fichier_id = f.id AND histo_destruction IS NULL
         JOIN fichier f ON f.id = cf.fichier_id AND f.histo_destruction IS NULL
         JOIN contrat C ON C.id = cf.contrat_id
         JOIN type_contrat tc ON tc.id = C.type_contrat_id
         JOIN STRUCTURE s ON s.id = C.structure_id
         JOIN utilisateur u ON u.id = f.histo_createur_id
WHERE f.histo_destruction IS NULL

UNION ALL
--Date de retour signé du contrat par composante
SELECT C.intervenant_id                                  intervenant_id,
       '5 - Contractualisation'                          categorie,
       'Date de retour signée ' || CASE WHEN tc.code = 'CONTRAT' THEN 'du contrat' ELSE 'de l''avenant' END || ' N° ' ||
       C.id || ' pour la composante ' || s.libelle_court label,
       C.histo_modification                              histo_date,
       C.histo_modificateur_id                           histo_createur_id,
       u.display_name                                    histo_user,
       'fas fa-book'                                     icon,
       5                                                 ordre
FROM contrat C
         JOIN type_contrat tc ON tc.id = C.type_contrat_id
         JOIN STRUCTURE s ON s.id = C.structure_id
         JOIN utilisateur u ON u.id = C.histo_modificateur_id
WHERE C.histo_destruction IS NULL
  AND C.date_retour_signe IS NOT NULL

UNION ALL

--Dernière modification du service réalisé par composante
SELECT s.intervenant_id                                                                     intervenant_id,
       '6 - Services réalisés'                                                              categorie,
       'Modification/Ajout du service réalisé pour la composante ' || MAX(st.libelle_court) label,
       MAX(vh.histo_modification)                                                           histo_date,
       MAX(vh.histo_modificateur_id)                                                        KEEP (dense_rank FIRST ORDER BY vh.histo_modification DESC)   histo_createur_id, MAX(u.display_name) KEEP (dense_rank FIRST ORDER BY vh.histo_modification DESC)    histo_user, 'glyphicon glyphicon-calendar' icon,
       5                                                                                    ordre
FROM volume_horaire vh
         JOIN service s ON s.id = vh.service_id
         JOIN element_pedagogique ep ON s.element_pedagogique_id = ep.id
         JOIN STRUCTURE st ON st.id = ep.structure_id
         JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = 'REALISE'
         JOIN periode p ON p.id = vh.periode_id
         JOIN type_intervention ti ON ti.id = vh.type_intervention_id
         JOIN utilisateur u ON u.id = vh.histo_modificateur_id
         LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id
GROUP BY s.intervenant_id, ep.structure_id

UNION ALL

--Validation du service réalisé
SELECT s.intervenant_id                                                                     intervenant_id,
       '6 - Services réalisés'                                                              categorie,
       'Validation du service réalisé pour la composante ' || MAX(st.libelle_court) label,
       MAX(v.histo_modification)                                                           histo_date,
       MAX(v.histo_modificateur_id)                                                        KEEP (dense_rank FIRST ORDER BY v.histo_modification DESC)   histo_createur_id, MAX(u.display_name) KEEP (dense_rank FIRST ORDER BY v.histo_modification DESC)    histo_user, 'glyphicon glyphicon-calendar' icon,
       5                                                                                    ordre
FROM volume_horaire vh
         JOIN service s ON s.id = vh.service_id
         JOIN element_pedagogique ep ON s.element_pedagogique_id = ep.id
         JOIN STRUCTURE st ON st.id = ep.structure_id
         JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = 'REALISE'
         JOIN periode p ON p.id = vh.periode_id
         JOIN type_intervention ti ON ti.id = vh.type_intervention_id
         JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
         JOIN validation v ON v.id = vvh.validation_id
         JOIN utilisateur u ON u.id = v.histo_modificateur_id
         LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id
GROUP BY s.intervenant_id, ep.structure_id

UNION ALL
--Mise en paiement
SELECT
   s.intervenant_id                                                                     intervenant_id,
       '7 - Mise en paiement'                                 categorie,
       mep.heures || 'h ' || th.libelle_court || CASE WHEN p.id IS NULL THEN '' ELSE ' (paiement en ' || p.libelle_court || ')' END                      label,
       mep.histo_modification                                                                histo_date,
       mep.histo_modificateur_id                                                             histo_createur_id,
       u.display_name                                                        histo_user,
       'glyphicon glyphicon-ok'                                              icon,
       6                                ordre
FROM
  mise_en_paiement mep
  JOIN formule_resultat_service frs ON frs.id = mep.formule_res_service_id
  JOIN service s ON s.id = frs.service_id
  JOIN type_heures th ON th.id = mep.type_heures_id
  JOIN utilisateur u ON u.id = mep.histo_modificateur_id
  LEFT JOIN periode p ON p.id = mep.periode_paiement_id
  WHERE
  mep.histo_destruction IS NULL



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