CREATE OR REPLACE FORCE VIEW V_INDIC_ATT_VALID_REF_AUTRE AS
SELECT distinct
    to_char(v.intervenant_id)||to_char(v.structure_id) as id,
    v.intervenant_id,
    v.structure_id
FROM v_indic_tous_services_valides v
INNER JOIN intervenant i                    ON V.INTERVENANT_ID = i.id              AND i.HISTO_DESTRUCTION IS NULL
INNER JOIN TYPE_VOLUME_HORAIRE tvh          ON v.type_volume_horaire_id = tvh.id    AND tvh.code = 'REALISE'
INNER JOIN VALIDATION clot                  ON clot.intervenant_id = i.id           AND clot.HISTO_DESTRUCTION IS NULL
INNER JOIN TYPE_VALIDATION tv               ON tv.id = clot.type_validation_id      AND tv.code = 'CLOTURE_REALISE'
WHERE EXISTS
    (
        SELECT s.ID
        FROM SERVICE_REFERENTIEL s
        INNER JOIN FONCTION_REFERENTIEL f       ON S.FONCTION_ID = f.ID                 AND f.HISTO_DESTRUCTION IS NULL
        INNER JOIN VOLUME_HORAIRE_REF vh        ON s.ID = VH.SERVICE_REFERENTIEL_ID     AND vh.HISTO_DESTRUCTION IS NULL
        LEFT JOIN VALIDATION_VOL_HORAIRE vvh    ON vh.ID = vvh.VOLUME_HORAIRE_ID
        LEFT JOIN VALIDATION val                ON val.ID = vvh.VALIDATION_ID           AND val.HISTO_DESTRUCTION IS NULL
        WHERE
            s.INTERVENANT_ID = v.intervenant_id
            AND s.HISTO_DESTRUCTION IS NULL
            AND VH.TYPE_VOLUME_HORAIRE_ID = V.TYPE_VOLUME_HORAIRE_ID
            AND s.structure_id <> v.structure_id -- autre composante d'intervention que celle qui a tout validé
            AND val.ID IS NULL -- au moins un VH non validé
    )