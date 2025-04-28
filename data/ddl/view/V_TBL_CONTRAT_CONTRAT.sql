CREATE OR REPLACE FORCE VIEW V_TBL_CONTRAT_CONTRAT AS
SELECT
    si.contrat actif,
    v.id validation_id,
    c.id contrat_id,
    i.id intervenant_id,
    a.id annee_id,
    c.structure_id,
    c.contrat_id parent_id,
    c.numero_avenant,
    c.debut_validite,
    c.fin_validite,
    c.histo_creation,
    c.histo_destruction,
    si.taux_remu_id,

    CASE WHEN v.id IS NULL THEN 0 ELSE 1 END edite,
    CASE WHEN c.date_envoi_email IS NULL THEN 0 ELSE 1 END envoye,
    CASE WHEN f.contrat_id IS NULL THEN 0 ELSE 1 END retourne,
    CASE WHEN c.date_retour_signe IS NULL THEN 0 ELSE 1 END signe
FROM
    intervenant i
    JOIN statut si ON si.id = i.statut_id
    JOIN annee a ON a.id = i.annee_id
    LEFT JOIN contrat c ON c.intervenant_id = i.id
    LEFT JOIN (
      SELECT DISTINCT cf.contrat_id
      FROM contrat_fichier cf JOIN fichier f ON f.id = cf.fichier_id AND f.histo_destruction IS NULL
    ) f ON f.contrat_id = c.id
    LEFT JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS NULL
WHERE
    1=1
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/
    /*@STATUT_ID=i.statut_id*/