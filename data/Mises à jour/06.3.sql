-- Script de migration de la version 6.2.2 à 6.3

ALTER TABLE effectifs MODIFY (
  element_pedagogique_id NOT NULL
);

DROP VIEW V_TYPE_INTERVENTION_REGLE_EP;