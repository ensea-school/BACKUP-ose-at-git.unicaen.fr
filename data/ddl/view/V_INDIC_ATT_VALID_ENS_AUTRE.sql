CREATE OR REPLACE FORCE VIEW V_INDIC_ATT_VALID_ENS_AUTRE AS
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
        SELECT s2.ID
        FROM SERVICE s2
        INNER JOIN ELEMENT_PEDAGOGIQUE ep2      ON s2.ELEMENT_PEDAGOGIQUE_ID  = ep2.ID  AND ep2.HISTO_DESTRUCTION IS NULL
        INNER JOIN VOLUME_HORAIRE vh2           ON s2.ID = vh2.SERVICE_ID               AND vh2.HISTO_DESTRUCTION IS NULL
        LEFT JOIN VALIDATION_VOL_HORAIRE vvh2   ON vh2.ID = vvh2.VOLUME_HORAIRE_ID
        LEFT JOIN VALIDATION val2               ON val2.ID = vvh2.VALIDATION_ID         AND val2.HISTO_DESTRUCTION IS NULL
        WHERE
            s2.INTERVENANT_ID = v.intervenant_id
            AND s2.HISTO_DESTRUCTION IS NULL
            AND VH2.TYPE_VOLUME_HORAIRE_ID = V.TYPE_VOLUME_HORAIRE_ID
            AND ep2.structure_id <> v.structure_id -- autre composante d'intervention que celle qui a tout validé
            AND val2.ID IS NULL -- au moins un VH non validé
    )