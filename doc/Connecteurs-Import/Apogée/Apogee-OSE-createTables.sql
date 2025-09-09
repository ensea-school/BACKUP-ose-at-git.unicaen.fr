--
-- CREATION ET INITIALISATION DES TABLES POUR IMPORTATION DANS OSE
-- Auteur : Bruno Bernard bruno.bernard@unicaen.fr
--
--

--
-- Etapes
-- Une etape OSE est assimilable a une VET Apogee
--
create table ose_etape (
  annee_id                       varchar2(4)             not null,
  libelle                        varchar2(120)           not null,
  libelle_court                  varchar2(25)            not null,
  z_type_formation_id            varchar2(20)            not null,
  niveau                         number(2),
  z_structure_id                 varchar2(20)            not null,
  source_code                    varchar2(20)            not null,
  specifique_echanges            number(1)    default  0 not null, -- Cette etape est-elle dediee aux echanges?
  domaine_fonctionnel            varchar2(20)            not null,
  FI                             number(1)    default  1 not null,
  FA                             number(1)    default  0 not null,
  FC                             number(1)    default  0 not null,
  cod_etp                        varchar2(6)             not null,
  cod_vrs_vet                    number(3)               not null,
  id                             varchar2(20)            not null,
  check (specifique_echanges in (0, 1)),
  check (FI + FA + FC > 0),
  primary key (id),
  constraint ose_etp_fk_annee foreign key (annee_id) references annee_uni (cod_anu),
  constraint ose_etp_fk_type_formation foreign key (z_type_formation_id) references ose_type_formation (source_code)
  )
;
grant select on ose_etape to ose
;
create public synonym ose_etape for apogee.ose_etape
;
--
-- Effectifs par annee par etape et par regime d inscription
--
create table ose_etape_effectifs (
  z_etape_id                     varchar2(20)            not null,
  annee_id                       varchar2(4)             not null,
  effectif_FI                    number(5)    default  0 not null,
  effectif_FA                    number(5)    default  0 not null,
  effectif_FC                    number(5)    default  0 not null,
  primary key (z_etape_id, annee_id)
  )
;
grant select on ose_etape_effectifs to ose
;
create public synonym ose_etape_effectifs for apogee.ose_etape_effectifs
;
--
-- Chemins pedagogiques
-- Relations entre les etapes et les elements pedagogiques les plus fins
--
create table ose_chemin_pedagogique (
  annee_id                       varchar2(4)             not null,
  z_element_pedagogique_id       varchar2(20)            not null,
  z_etape_id                     varchar2(20)            not null,
  source_code                    varchar2(40)            not null,
  z_periode_id                   varchar2(20),
  taux_foad                      number(1)    default  0 not null,
  id                             varchar2(40)            not null,
  check (z_periode_id in ('S1', 'S2')),
  check (taux_foad in (0, 1)),
  primary key (id),
  constraint ose_chp_fk_annee foreign key (annee_id) references annee_uni (cod_anu)
  )
;
grant select on ose_chemin_pedagogique to ose
;
create public synonym ose_chemin_pedagogique for apogee.ose_chemin_pedagogique
;
--
-- Elements pedagogiques
-- Un element pedagogique OSE est assimilable a un element pedagogique feuille d une structure d enseignements Apogee
--
create table ose_element_pedagogique (
  annee_id                       varchar2(4)             not null,
  libelle                        varchar2(60)            not null,
  z_etape_id                     varchar2(20)            not null,
  z_structure_id                 varchar2(20)            not null,
  z_periode_id                   varchar2(20),
  taux_foad                      number(1)    default  0 not null,
  source_code                    varchar2(20)            not null,
  z_discipline_id                varchar2(4),
  FI                             number(1)    default  0 not null,
  FA                             number(1)    default  0 not null,
  FC                             number(1)    default  0 not null,
  id                             varchar2(20)            not null,
  check (z_periode_id in ('S1', 'S2')),
  check (taux_foad in (0, 1)),
  check (FI in (0, 1)),
  check (FA in (0, 1)),
  check (FC in (0, 1)),
  primary key (id),
  constraint ose_elp_fk_annee foreign key (annee_id) references annee_uni (cod_anu)
  )
;
grant select on ose_element_pedagogique to ose
;
create public synonym ose_element_pedagogique for apogee.ose_element_pedagogique
;
--
-- Effectifs par annee par element pedagogique et par regime d inscription
--
create table ose_element_effectifs (
  z_element_pedagogique_id       varchar2(20)            not null,
  annee_id                       varchar2(4)             not null,
  effectif_FI                    number(5)    default  0 not null,
  effectif_FA                    number(5)    default  0 not null,
  effectif_FC                    number(5)    default  0 not null,
  primary key (z_element_pedagogique_id, annee_id),
  constraint ose_eff_fk_annee foreign key (annee_id) references annee_uni (cod_anu)
  )
;
grant select on ose_element_effectifs to ose
;
create public synonym ose_element_effectifs for apogee.ose_element_effectifs
;
--
-- Table recursive de l offre de formation
--
create table ose_offre_de_formation (
  annee_id                       varchar2(4)             not null,
  noeud_sup_id                   varchar2(20),
  structure_sup_id               varchar2(20),
  choix_minimum                  number(2),
  choix_maximum                  number(2),
  liste_id                       varchar2(20),
  libelle_liste                  varchar2(120),
  libelle_court_liste            varchar2(25),
  noeud_inf_id                   varchar2(20)            not null,
  structure_inf_id               varchar2(20),
  libelle                        varchar2(120)           not null,
  libelle_court                  varchar2(25)            not null,
  nature                         varchar2(20),
  periode                        varchar2(20),
  taux_foad                      number(1)     default 0 not null,
  check (periode   in ('S1', 'S2')),
  check (taux_foad in (0, 1)),
  constraint ose_odf_uk       unique      (annee_id, noeud_inf_id, liste_id, noeud_sup_id),
  constraint ose_odf_fk_annee foreign key (annee_id) references annee_uni (cod_anu)
  )
;
grant select on ose_offre_de_formation to ose
;
create public synonym ose_offre_de_formation for apogee.ose_offre_de_formation
;
--
-- Noeuds de l arbre offre de formation
--
create view ose_noeud as
select
  annee_id,
  libelle,
  libelle_court,
  noeud_inf_id                                                       as code,
  0                                                                  as liste,
  case when nature = 'etape' then noeud_inf_id else null         end as z_etape_id,
  case when nature = 'etape' then null         else noeud_inf_id end as z_element_pedagogique_id,
  annee_id || '_' || noeud_inf_id                                    as z_source_code,
  structure_inf_id                                                   as z_structure_id
from ose_offre_de_formation
union
select
  annee_id,
  libelle_liste                                                      as libelle,
  libelle_court_liste                                                as libelle_court,
  liste_id                                                           as code,
  1                                                                  as liste,
  null                                                               as z_etape_id,
  null                                                               as z_element_pedagogique_id,
  annee_id || '_' || liste_id                                        as z_source_code,
  min ( structure_sup_id )                                           as z_structure_id
from ose_offre_de_formation
where liste_id is not null
group by
  annee_id,
  libelle_liste,
  libelle_court_liste,
  liste_id
;
grant select on ose_noeud to ose
;
create public synonym ose_noeud for apogee.ose_noeud
;
--
-- Liens entre les noeuds de l arbre offre de formation
--
create view ose_lien as
select
  annee_id,
  annee_id || '_' || noeud_sup_id                                    as noeud_sup_id,
  annee_id || '_' || liste_id                                        as noeud_inf_id,
  choix_minimum,
  choix_maximum,
  annee_id || '_' || noeud_sup_id || '_' || liste_id                 as z_source_code,
  structure_sup_id                                                   as z_structure_id
from ose_offre_de_formation
where liste_id is not null
union
select
  annee_id,
  annee_id || '_' || liste_id                                        as noeud_sup_id,
  annee_id || '_' || noeud_inf_id                                    as noeud_inf_id,
  null                                                               as choix_minimum,
  null                                                               as choix_maximum,
  annee_id || '_' || liste_id || '_' || noeud_inf_id                 as z_source_code,
  min ( structure_sup_id )                                           as z_structure_id
from ose_offre_de_formation
where liste_id is not null
group by
  annee_id,
  liste_id,
  noeud_inf_id
;
grant select on ose_lien to ose
;
create public synonym ose_lien for apogee.ose_lien
;
--
-- Volumes horaires et nombre de groupes ouverts pour enseignement, par type de groupe
--
create table ose_volume_horaire_ens (
  annee_id                       varchar2(4)             not null,
  z_element_pedagogique_id       varchar2(20)            not null,
  z_type_intervention_id         varchar2(20)            not null,
  heures                         number(6,2),
  source_code                    varchar2(20)            not null,
  id                             varchar2(20)            not null,
  groupes                        number(6),
  primary key (id),
  constraint ose_ovh_fk_annee foreign key (annee_id) references annee_uni (cod_anu)
  )
;
grant select on ose_volume_horaire_ens to ose
;
create public synonym ose_volume_horaire_ens for apogee.ose_volume_horaire_ens
;
