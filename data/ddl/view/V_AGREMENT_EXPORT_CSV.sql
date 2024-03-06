CREATE OR REPLACE FORCE VIEW V_AGREMENT_EXPORT_CSV AS
WITH heures_s AS (
  SELECT
    i.id                                      intervenant_id,
    COALESCE(ep.structure_id,i.structure_id)  structure_id,
    SUM(frvh.heures_service_fi)               service_fi,
    SUM(frvh.heures_service_fa)                service_fa,
    SUM(frvh.heures_service_fc)                service_fc,
    SUM(frvh.heures_compl_fi)                  heures_compl_fi,
    SUM(frvh.heures_compl_fa)                  heures_compl_fa,
    SUM(frvh.heures_compl_fc)                  heures_compl_fc,
    SUM(frvh.heures_primes)                    heures_primes,
    SUM(frvh.total)                            total
  FROM
              formule_resultat_volume_horaire frvh
         JOIN type_volume_horaire              tvh ON tvh.code = 'PREVU'
         JOIN etat_volume_horaire              evh ON evh.code = 'valide'
         JOIN formule_resultat_intervenant      fr ON fr.id = frvh.formule_resultat_intervenant_id
                                                  AND fr.type_volume_horaire_id = tvh.id
                                                  AND fr.etat_volume_horaire_id = evh.id
         JOIN intervenant                        i ON i.id = fr.intervenant_id
         JOIN service                            s ON s.id = frvh.service_id
    LEFT JOIN element_pedagogique               ep ON ep.id = s.element_pedagogique_id
  GROUP BY
    i.id,
    ep.structure_id,
    i.structure_id
)
SELECT a.libelle                                                 annee,
       a.id                                                      annee_id,
       i.id                                                      intervenant_id,
       s2.id                                                     intervenant_structure_id,
       s2.libelle_court                                          intervenant_structure_libelle,
       s.id                                                      structure_id,
       s.ids                                                     structure_ids,

       s.libelle_court                                           structure_libelle,
       i.code                                                    intervenant_code,
       i.nom_usuel                                               intervenant_nom_usuel,
       i.nom_patronymique                                        intervenant_nom_patronymique,
       i.prenom                                                  intervenant_prenom,

       si.libelle                                                intervenant_statut_libelle,
       d.libelle_court                                           discipline,

       COALESCE(heures_s.service_fi, fr.heures_service_fi)
           + COALESCE(heures_s.heures_compl_fi, fr.heures_compl_fi)
                                                                 hetd_fi,
       COALESCE(heures_s.service_fa, fr.heures_service_fa)
           + COALESCE(heures_s.heures_compl_fa, fr.heures_compl_fa)
                                                                 hetd_fa,
       COALESCE(heures_s.service_fc, fr.heures_service_fc)
           + COALESCE(heures_s.heures_compl_fc, fr.heures_compl_fc)
           + COALESCE(heures_s.heuresprimes, fr.heures_primes)
                                                                 hetd_fc,
       COALESCE(heures_s.total, fr.total)                        hetd_total,


       tagr.libelle                                              type_agrement,
       CASE WHEN agr.id IS NULL THEN 0 ELSE 1 END                agree,
       CASE WHEN agr.id IS NULL THEN 'En attente' ELSE 'Oui' END agree_txt,
       agr.date_decision                                         date_decision,
       CASE WHEN ta.annee_agrement IS NOT NULL
       	    THEN '31/08/' || to_char(ta.annee_agrement+ta.duree_vie)
       	    ELSE NULL END	 									 date_expiration,
       u.display_name                                            modificateur,
       agr.histo_modification                                    date_modification
FROM tbl_agrement ta
         JOIN intervenant i ON i.id = ta.intervenant_id
         JOIN statut si ON si.id = i.statut_id
         JOIN annee a ON a.id = ta.annee_id
         JOIN type_agrement tagr ON tagr.id = ta.type_agrement_id
         JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
         JOIN etat_volume_horaire evh ON evh.code = 'valide'


         LEFT JOIN structure s ON s.id = ta.structure_id
         LEFT JOIN structure s2 ON s2.id = i.structure_id
         LEFT JOIN agrement agr ON agr.id = ta.agrement_id
         LEFT JOIN utilisateur u ON u.id = agr.histo_modificateur_id
         LEFT JOIN discipline d ON d.id = i.discipline_id

         LEFT JOIN formule_resultat_intervenant fr ON fr.intervenant_id = i.id
    AND fr.type_volume_horaire_id = tvh.id
    AND fr.etat_volume_horaire_id = evh.id

         LEFT JOIN heures_s ON heures_s.intervenant_id = i.id
    AND heures_s.structure_id = s.id