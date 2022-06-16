CREATE
OR REPLACE FORCE VIEW V_HETD_PREV_VAL_STRUCT AS
SELECT annee_id,
       structure_id,
       SUM(heures) heures

FROM (SELECT i.annee_id,
             nvl(ep.structure_id, i.structure_id)                                                           structure_id,
             frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees heures

      FROM formule_resultat_service frs
               JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
               JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
               JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
               JOIN intervenant i ON i.id = fr.intervenant_id
               JOIN service s ON s.id = frs.service_id
               LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id

      WHERE tvh.code = 'PREVU'
        AND evh.code = 'valide') t1
GROUP BY annee_id, structure_id