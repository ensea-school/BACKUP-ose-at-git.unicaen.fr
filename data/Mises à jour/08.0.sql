-- Script de migration de la version 7.x à la 8.0

ALTER TABLE affectation_recherche ADD (labo_libelle   VARCHAR2(300 CHAR));