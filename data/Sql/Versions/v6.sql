-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END;
/


INSERT INTO indicateur (
    id,
    type,
    ordre,
    enabled,
    numero,
    libelle_singulier,
    libelle_pluriel,
    route,
    tem_distinct,
    tem_not_structure,
    message
) VALUES (
    indicateur_id_seq.nextval,
    'Enseignements et référentiel <em>Permanents</em>',
    1210,
    1,
    1210,
    '%s intervenant a des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour la fonction correspondante',
    '%s intervenants ont des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour la fonction correspondante',
    'intervenant/services',
    1,
    0,
    null
);

INSERT INTO indicateur (
    id,
    type,
    ordre,
    enabled,
    numero,
    libelle_singulier,
    libelle_pluriel,
    route,
    tem_distinct,
    tem_not_structure,
    message
) VALUES (
    indicateur_id_seq.nextval,
    'Enseignements et référentiel <em>Permanents</em>',
    1220,
    1,
    1220,
    '%s intervenant a des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour la fonction correspondante',
    '%s intervenants ont des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour la fonction correspondante',
    'intervenant/services',
    1,
    0,
    null
);

INSERT INTO indicateur (
    id,
    type,
    ordre,
    enabled,
    numero,
    libelle_singulier,
    libelle_pluriel,
    route,
    tem_distinct,
    tem_not_structure,
    message
) VALUES (
    indicateur_id_seq.nextval,
    'Enseignements et référentiel',
    560,
    1,
    560,
    '%s intervenant a des heures équivalent TD (HETD) <i>prévisionnelles</i> au-delà du plafond autorisé de part son statut',
    '%s intervenants ont des heures équivalent TD (HETD) <i>prévisionnelles</i> au-delà du plafond autorisé de part leur statut',
    'intervenant/services',
    1,
    0,
    null
);

INSERT INTO indicateur (
    id,
    type,
    ordre,
    enabled,
    numero,
    libelle_singulier,
    libelle_pluriel,
    route,
    tem_distinct,
    tem_not_structure,
    message
) VALUES (
    indicateur_id_seq.nextval,
    'Enseignements et référentiel',
    570,
    1,
    570,
    '%s intervenant a des heures équivalent TD (HETD) <i>réalisées</i> au-delà du plafond autorisé de part son statut',
    '%s intervenants ont des heures équivalent TD (HETD) <i>réalisées</i> au-delà du plafond autorisé de part leur statut',
    'intervenant/services',
    1,
    0,
    null
);

INSERT INTO indicateur (
    id,
    type,
    ordre,
    enabled,
    numero,
    libelle_singulier,
    libelle_pluriel,
    route,
    tem_distinct,
    tem_not_structure,
    message
) VALUES (
    indicateur_id_seq.nextval,
    'Pièces justificatives',
    1011,
    1,
    1011,
    '%s vacataire n''a pas fourni toutes les pièces justificatives obligatoires',
    '%s vacataires n''ont pas fourni toutes les pièces justificatives obligatoires',
    'piece-jointe/intervenant',
    1,
    0,
    null
);

INSERT INTO indicateur (
    id,
    type,
    ordre,
    enabled,
    numero,
    libelle_singulier,
    libelle_pluriel,
    route,
    tem_distinct,
    tem_not_structure,
    message
) VALUES (
    indicateur_id_seq.nextval,
    'Pièces justificatives',
    1011,
    1,
    1011,
    '%s permanent n''a pas fourni toutes les pièces justificatives obligatoires',
    '%s permanents n''ont pas fourni toutes les pièces justificatives obligatoires',
    'piece-jointe/intervenant',
    1,
    0,
    null
);

INSERT INTO indicateur (
    id,
    type,
    ordre,
    enabled,
    numero,
    libelle_singulier,
    libelle_pluriel,
    route,
    tem_distinct,
    tem_not_structure,
    message
) VALUES (
    indicateur_id_seq.nextval,
    'Pièces justificatives',
    1021,
    1,
    1021,
    '%s permanent est en attente de validation de ses pièces justificatives obligatoires',
    '%s permanents sont en attente de validation de leurs pièces justificatives obligatoires',
    'piece-jointe/intervenant',
    1,
    0,
    null
);

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
