# Version stable

[OSE 20.3](#ose-204-05062023)


# OSE 21 (juin 2023)

## Nouveautés

* Nouvelle notion de mission, permettant de [gérer les contrats étudiants](https://redmine.unicaen.fr/Etablissement/dmsf/files/71233/view)
  * Référentiel de missions avec par défaut 8 types de mission proposés et personnalisables via une interface d'administration
  * Gestion des offres d'emploi & des candidatures
  * Nouvelle interface de gestion des missions
  * Nouvelle interface de saisie des suivis de missions
  * Adaptation de la partie paiement pour gérer les heures nocturnes/dimanches/jours fériés
  * Plafonds applicables aux missions avec un nouveau périmètre par type de mission
  
  
* Gestion renforcée des taux de paiement
  * Possibilité de gérer de nouveaux taux différents du taux légal de 42,86€
  * Nouvelle interface d'administration des taux de paiement
  * Les taux peuvent être indexés sur d'autres taux (le SMIC par exemple)
  * Les taux peuvent être appliqués globalement, par mission, par statut, par élément pédagogique, selon le contexte
 
* Pièces justificatives
  * Nouveau filtre permettant de ne demander des pièces que pour les étrangers 

* Contrats de travail
  * Possibilité de contractualiser des heures de référentiel
  * Possibilité de contractualiser des heures de mission
  * Possibilité d'avoir des états de sortie distincts pour les contrats et pour les avenants, par statut


## Améliorations

* Modification de libellé dans l'affichage de l'offre de formation (#49763)
* Possibilité de modifier manuellement l'email expéditeur pour l'envoi d'email via les indicateurs (#50725)

## Corrections de bugs

* Il est possible de rentrer une date de retour sur un contrat après avoir téléversé le contrat sans avoir besoin de recharger la page
 


# OSE 20.4 (05/06/2023)

## Corrections de bugs

* Erreur d'affichage du contrat unique avec des heures multi-composantes (#50889 et #50980)
* Problème de saisie de services hors établissement (#50990)
* Formulaire d'édition des états de sortie réparé au niveau de l'affichage des sous-requêtes
* Correction de la formule de Picardie
* Correction de la formule de calcul de Lyon 2
* Correction de la formule de Paris 1



# OSE 20.3 (23/05/2023)

## Nouveautés

* Possibilité de spécifier le mode de saisie des heures (calendaire ou semestriel) par statut d'intervenant
* La Réunion : nouvelle formule appliquée à partir de 2023/2024 uniquement
* Paris 1 Panthéon Sorbonne : nouvelle formule de calcul
* Rennes 2 : nouvelle formule de calcul

## Corrections de bugs

* Meilleure gestion dans l'expérience utilisateur de la saisie des dates de début et de fin dans la saisie de service en mode calendaire (#50508)
* Masquer de la liste de choix d'une étape, les formations historisées lors de l'ajout d'un élément pédagogique à une formation (#48878)
* Possibilité de choisir quel type d'affectation (HIE ou FUN) est remontée/testée lors d'une PEC ou REN (#49954)
* Dans la partie notes intervenant, différenciation entre les demandes de mise en paiement et les mises en paiement (#50081)
* Affichage de la fiche intervenant lorsque la civilité n'est pas renseignée (#50813)
* La saisie de nouvelles heures sur des services existants en passant par le bouton "ajout" fonctionne de nouveau (#50814)
* La structure est obligatoire dans le formulaire de saisie du service référentiel et ne fait plus planter l'application lorsqu'elle n'est pas saisie
* Mauvais affichage des HETD dans l'export des services pour une ligne comportant un Tag (#50091)

## Notes de migration

Si vous créez des intervenants locaux sans leur remplir de données personnelles, de services ou de PJ, OSE les historise. Afin d'éviter cela, il vous faut modifier le filtre de synchronisation des intervenants.

Vous trouverez plus d'indications ainsi que le filtre en question ici :
https://git.unicaen.fr/open-source/OSE/-/blob/master/doc/Connecteurs-Import/Connecteurs-IMPORT.md#utilisation-pour-contr%C3%B4ler-la-synchronisation-des-intervenants



# OSE 20.2 (28/04/2023)

## Nouveautés

* Nouvelle formule de calcul de Rouen

## Améliorations

* Possibilité d'entrer des dérogations aux plafonds avant d'avoir des heures à plafonner (#46387) 

## Corrections de bugs

* Les motifs de modification de service dû supprimés ne peuvent plus être sélectionnés (#50328)
* Messages d'erreur corrigés lors de l'exécution du script de migration de la V20.
* Le bouton prévu=>réalisé s'affiche de nouveau lorsqu'il n'y a pas de contrat (#45643)
* Formule de Poitiers : rétablissement du test pour appliquer l'ancienne formule avant 2022.
* Formule de Picardie : prise en compte des heures négatives (#50471)
* L'interface d'administration des types de formation est de nouveau opérationnelle (#50360)



# OSE 20.1 (04/04/2023)

## Nouveautés

* Ajout de la date de clôture dans la page historique de l'intervenant 
* Possibilité de saisir une modification de service dû avec 0 heure (#49764)

## Améliorations

* Passage à 200 caractères max. pour les libellés longs des structures

## Corrections de bugs

* Correction d'une régression de la V20 sur le module Export Siham
* Suppression d'un message d'erreur sur l'envoi de mail via les indicateurs (#49873)
* Formule Paris 8 : correction d'un problème de code de composante
* Formule Poitiers : (#46805)
* Formule La Réunion : (#24229)
* Formule de Picardie:  (#47224)

## Notes de mise à jour

Si vous rencontrez les deux messages d'erreurs suivants, merci de ne pas en tenir compte, ces erreurs n'occasionneront pas de dysfonctionnezmenet de l'application.

1. Suppression de l'index TYPE_INTERVENTION_CODE_UN
ORA-02429: cannot drop index used for enforcement of unique/primary key (offset 11
DROP INDEX TYPE_INTERVENTION_CODE_UN

2. Transformation des modèles de contrats en états de sortie ... Convertion des contrats de travail en états de sortie
Erreur : ORA-00001: unique constraint (OSE.ETAT_SORTIE_CODE_UN) violated


# OSE 20 (28/02/2023)

## Nouveautés

* Les modèles de contrats de travail sont maintenant gérés comme n'importe quel autre état de sortie
* Reconduction de l'offre de formation pour les éléments de OSE portés par un élément synchronisé
* Possibilité de rentrer un taux de charge par statut d'intervenant
* Ajout d'un choix par statut pour "contrat de travail et avenants" pour laisser la possibilité à l'intervenant de télécharger son contrat en pdf
* Migration technnique vers le framework Bootstrap 5 et modernisation de l'identité visuelle
* Réorganisation du menu "Administration" pour plus de lisibilité
* Filtrage des caractères interdits lors de l'export RH SIHAM (#47267)
* Associer des tags aux services afin de flécher certains financements (#42451)
* Envoyer un email via un indicateur à la fois sur l'email perso et l'email pro de l'intervenant (#48687)
* Nouveaux indicateurs 500 et 505 listant les permanents sans service ni référentiel

## Corrections de bugs

* Sur la page "Services", la sélection d'un élément après selection d'une composante et d'une formation est désormais fonctionnel
* Correction des indicateurs 910 et 920 qui étaient non fonctionnels dans le cas d'une autovalidation ou d'une absence de contrat
* Correction de la suppression d'un role dans la page d'administration des roles.
* Correction mineure sur les notes des intervenants au niveau de l'historique (#46303)
* La durée de vie attendue des pièces justificatives est maintenant celle de l'année en cours et plus celle de l'année de dépôt de la pièce
* Les annulations de mises en paiement sont désormais bien prises en compte dès la première annulation
* Il est désormais possible de clôturer le service réalisé même si aucune heure n'est saisie
* Les indicateurs 530 et 540 ne renvoient plus de vacataires
* Dans les diagrammes du module Charges, la boite de dialogue s'affiche correctement, même avec beaucoup de types d'intervention
* Le plafond relatif aux charges / services saisis est maintenant opérationnel
* Lors de la saisie d'enseignement, les elements pedagogique sur lesquels il est impossible de saisir des heures seront surlignés en rouge
* Il est de nouveau possible de saisir des taux de charge TTC et des taux de charge patronale a virgule
* Modification du filtre des status séléctionnables dans les données personnelles (#48151)
* Lors de la demande de mise en paiement, pouvoir choisir un EOTP même si son centre de coût parent n'est pas de l'activité attendue (pilotage / enseignement) (#48286)
* Utilisation prioritaire de l'email personnel des données personnelles pour l'envoi d'email via les indicateurs (#48393)
* Meilleure gestion de la casse lors de la recherche d'un employeur (#48543)
* Ajout d'une contrainte d'unicité sur la colonne code de la table type_intervention (#48727)
* Correction formule d'UVSQ (#47149)
* Et beaucoup d'autres ...

## Notes de mise à jour

* Supprimer la ligne faisant référence à TBL_NOEUD dans Administration/Synchronisation/Tables, table NOEUD, champ "Traitements postérieurs : à exécuter après la synchro".
* La génération des contrats de travail ayant été remaniée, veuillez vérifier que vous pouvez générer correctement de nouveaux contrats de travail


Avec l'ajout de la notion de tag sur les services d'enseignement et référentiel, les champs 'TAG' et 'TAG_ID' ont été ajouté dans la V_EXPORT_SERVICE, si vous avez créé votre propre V_EXPORT_SERVICE, il vous faudra la modifier vous même en vous appuyant sur la V_EXPORT_SERVICE par défaut de OSE (https://git.unicaen.fr/open-source/OSE/-/blob/master/data/ddl/view/V_EXPORT_SERVICE.sql)

Ensuite si vous souhaitez faire apparaître les tags dans l'export des services, il vous faudra modifier vous même l'état de sortie 'Export des services', dans l'onglet 'Export CSV' : 

A la **ligne 56** ajouter TAG_ID à la variable $sid : 

    $sid .= '_' . $d['TAG_ID'];

A la **ligne 102** ajouter la colonne TAG dans le tableau $ds: 

    tag' => $d['TAG'],

A la **ligne 206** Ajouter le titre de colonne TAG dans le tableau $head : 
  
    'tag' => 'Tags',

Le système de mise à jour peut - dans certaines circonstances - vous afficher quelques erreurs qui sont sans impact 
sur le bon fonctionnement de l'application.
Je vais modifier les scripts de mise à jour pour éviter qu'elles ne se produisent.
Mais en attendant, si vous y êtes confrontés, vous en trouverez les explications sur le ticket suivant :
https://redmine.unicaen.fr/Etablissement/issues/49445?issue_count=42&issue_position=1&next_issue_id=48972



# OSE 19.7 (16/12/2022)

## Corrections de bugs

* Correction régression 19.5 : le workflow fonctionne à nouveau (#47982)
* Correction formule de calcul du Havre (#48024)



# OSE 19.6 (14/12/2022)

### Attention : il est déconseillé d'utiliser les 19.5 et 19.6, des régressions ont été constatées à plusieurs niveaux.

## Corrections de bugs

* Correction régression 19.5 : les indicateurs 910 et 920 fonctionnent de nouveau
* Correction régression 19.5 : les formules de calcul fonctionnent de nouveau
* Correction régression 19.5 : la page d'administration des statuts fonctionne de nouveau (#47976)
* Correction sur l'état de sortie préliquidation SIHAM (#47678)
* Prise dans compte des modulateurs pour la formule de Rennes 2 (#47753)


# OSE 19.5 (12/12/2022)

### Attention : il est déconseillé d'utiliser les 19.5 et 19.6, des régressions ont été constatées à plusieurs niveaux.

## Corrections de bugs

* Dans le module Charges, la saisie de seuils par défaut refonctionne normalement (#47451)
* Les plafonds de périmètre "volume horaire" sont de nouveau activables (#47340,#45225)
* Filtre des pays avec dates de validité périmées dans les listing des données personnelles (#47492)
* Correction sur le script de mise à jour des employeurs
* Correction sur les notes de l'intervenant au niveau de l'historique (#46303)
* Vue V_IMPORT_DEPUIS_DOSSIERS permettant de réinjecter les données personnelles dans les fiches corrigée (pb de filtre année) (#46769)
* Formule de calcul du Havre mise à jour
* Correction de bug dans la formule de Picardie
* Correction de bug de l'envoi du contrat par email lorsque la civilité est absente
* Correction du bouton "Prévu->Réalisé" absent pour le service réalisé
* Lors de la saisie de référentiel, le tri se fait correctement sur les fonction référentielles et sur les types de fonction
* Les caractères spéciaux sont bien pris en compte dans les types d'intervention (exemple : CM/TD)
* Le tableau des services d'enseignement n'affiche plus les colonnes inutiles
* Les étapes d'ODF complémentaire peuvent de nouveau être modifiées (#46922)
* Ajout du libelle du statut (champ STATUT_LIBELLE) pour affichage dans les états de paiement si nécessaire (#47762)
* Correction pour prise en compte des départements de naissance dans les DOM TOM dans la PEC Siham.
* Le workflow se calcule correctement lorsqu'il n'y a qu'un seul contrat par intervenant
* Correction sur la gestion des pièces jointes demandées uniquement dans le cadre de la formation continue
* Formule de calcul de ROUEN corrigée (#47876)

# OSE 19.4 (21/10/2022)

## Corrections de bugs/petites évolutions

* Les types d'intervention personnalisés par statut peuvent de nouveau être saisis (#46930)
* Modification de la formule de calcul de Poitiers
* Modification de la formule de calcul de Rennes 2
* Modification de la formule de calcul de Lyon 2
* Modification de la formule de calcul de Nice Cote d'Azur



# OSE 19.3 (08/09/2022)

## Corrections de bugs

* Pb lié à la 19.2 : la vue V_ETAT_PAIEMENT n'était pas mise à jour correctement. 



# OSE 19.2 (06/09/2022)

## Nouveautés

* Prise en compte du nouveau point d'indice valable à partir du 1er juillet 2022



# OSE 19.1 (21/07/2022)

## nouveautés

* Formule de calcul de Picardie
* Nouvel état de sortie pour les écarts des heures complémentaires, maintenant personnalisable (#45807)
* Possibilité de faire une PEC ou REN (SIHAM) l'année universitaire N-1
* Nouvel état de sortie pour télécharger une synthèse des privilèges par rôle (#45629)
* Nouveau paramètre du module export RH (SIHAM) permettant de synchroniser le code intervenant avec le matricule SIHAM lors d'une PEC ou d'un renouvellement

## Corrections de bugs

* La synchronisation via la ligne de commande ne fonctionnait plus. C'est rétabli



# OSE 19.0 (12/07/2022)

## Nouveautés

* Ajout d'un paramètre général qui permet de choisir si un contrat peut avoir une date de retour signé ou non s'il n'y a
  pas de fichier
* Ajout d'un paramètre général qui permet de choisir pour l'intervenant sur l'année universitaire entre : avoir autant
  d'avenants que nécessaire, avoir un
  contrat/avenant par structure, avoir un contrat unique toutes composantes confondues
* Ajout d'un paramètre général pour permettre de créer les contrats sans passer par un projet de contrat
* Ajout de date de dernière modification des données dans les indicateurs notifiant d'une validation en attente
* Nouvel état de sortie pour l'extraction des paiements dans le cadre de la pré-liquidation SIHAM
* Ajout d'un bouton de refus de pièce justificative avec envoi d'email à l'intervenant
* Changement du bouton de cloture de service pour un libellé plus parlant et un style de bouton plus prononcé
* Formules nouvelles ou mises à jour : Rennes 2, Paris Saclay, Guyane, Côte d'Azur, La Réunion, Poitiers, Brest, Rouen
* Possibilité de créer un nouveau test de formule en téléversant une feuille de calcul au format tableur
* Ajout d'un champs cci pour l'envoi de mail aux intervenants et le refus de pièces jointes (#45083)

## Corrections de bugs

* Données personnelles : pouvoir pré-remplir le champs statut avec un statut non sélectionnable dans la liste. (#45216)
* Budget/Liquidation : afficher le nombre de HETD uniquement des HCO et non les HETD des HCO + Heures de service
* Notes : Afficher le bon utilisateur pour la validation de service (#45413).
* Forcer l'activiation de l'étape pièces justificatives même si il n'y a pas de service prévisionnel de renseigné.
* Choix du bon modèle de contrat dans le cas de plusieurs modèles de contrat (par structure et/ou par statut) (#45520)
* Bouton Prévu->réalisé Apparait correctement pour le service réalisé.
* Correction sur la reconduction des centres de coût et modulateurs (#45746)


## Notes de mise à jour

* Si vous êtes en version 17.x, se référer à toutes les notes de migration de la version [18.0](#ose-18-23052022)
Une fois la migration réalisée et quelques tests effectués, vous devrez supprimer manuellement les tables de sauvegarde listées ci-dessous.
Si vous ne le faites pas, le risque est que les scripts de migration de la version 17 à la version 18 soient rejoués sans qu'il n'en soit nécessaire, avec en sus un *risque de perte de données* pour des intervenants ayant changé de statut entre temps.


* Si vous êtes déjà en version 18.x et si ce n'est déjà fait, il vous faudra supprimer les tables de sauvegardes liées à la migration 17 --> 18 et
la table STATUT_INTERVENANT
**avant** de migrer en 19.0.

#### Liste des tables de sauvegardes de migration 17=>18 concernées :
```sql
DROP TABLE save_v18_dossier_autre_statut;
DROP TABLE save_v18_plafond;
DROP TABLE save_v18_plafond_app;
DROP TABLE save_v18_referentiel;
DROP TABLE save_v18_statut;
DROP TABLE save_v18_statut_privilege;
DROP TABLE save_v18_structure;
DROP TABLE save_v18_ta_statut;
DROP TABLE save_v18_tis;
DROP TABLE save_v18_tpjs;
DROP TABLE save_v18_dossier;
DROP TABLE save_v18_intervenant;
DROP TABLE save_v18_privilege;
DROP TABLE save_v18_role_privilege;
DROP TABLE statut_intervenant;

```





# OSE 18.2 (15/06/2022)

## Corrections de bugs

* Utilisation du mail expéditeur des paramétres par défaut pour l'envoi de mail via les notes et les refus de pièces
  jointes et correction dans le cas où l'
  intervenant n'a pas encore de dossier (#45083)
* Correction du bouton reporter les données de cet intervenant dans l'interface de test de formule (#45140)
* Les demandes de mise en paiement faites pour des services historisés s'affichent en rouge plutôt que de provoquer une
  erreur
* Les modifications sur les types d'intervention ne recalculent plus automatiquement toutes les formules, ce qui
  bloquait l'application
* Les modifications sur les types d'intervention ne retournent plus d'erreur de type sur le "Taux Hetd Complémentaire"
* L'ajout d'une structure est de nouveau possible depuis la page d'administration des structures.
* Les statuts sont de nouveau filtrés correctement dans l'interface d'administration des types d'intervention (#45141)
* Détection du type "LONG" dans la base de données pour permettre les mises à jour (#45174)
* Un nouveau paramètre de configuration : cas.exclusif a été ajouté. Il permet de n'offrir que le CAS comme possibilité
  de connexion (#44824)
* Correction du lien vers la fiche intervenant des indicateurs de dépassement de charges

## Notes de mise à jour

Si vous êtes en version 17, se référer à toutes les notes de migration de la version 18.0

Si vous êtes déjà en version 18.x et si ce n'est déjà fait, il vous faudra supprimer les tables de sauvegardes liées à la migration 17 --> 18 et
la table STATUT_INTERVENANT
**avant** de migrer en 18.1.

```sql
DROP TABLE save_v18_dossier_autre_statut;
DROP TABLE save_v18_plafond;
DROP TABLE save_v18_plafond_app;
DROP TABLE save_v18_referentiel;
DROP TABLE save_v18_statut;
DROP TABLE save_v18_statut_privilege;
DROP TABLE save_v18_structure;
DROP TABLE save_v18_ta_statut;
DROP TABLE save_v18_tis;
DROP TABLE save_v18_tpjs;
DROP TABLE save_v18_dossier;
DROP TABLE save_v18_intervenant;
DROP TABLE save_v18_privilege;
DROP TABLE save_v18_role_privilege;
DROP TABLE statut_intervenant;

```

# OSE 18.1 (31/05/2022)

## Corrections de bugs

* On peut maintenant se connecteur en CAS avec le login LDAP désativé (#44824)
* **IMPORTANT** Dans la 18.0, les données personnelles ne pouvaient pas s'enregistrer
* Pour certains intervenants multi-statuts, les agréments ne sont plus affichés en double
* Les intervenants multi-statuts peuvent maintenant agir sur toutes leurs fiches sans avoir d'erreur de saisie

## Notes de mise à jour

Si vous êtes en version 17, se référer à toutes les notes de migration de la version 18.0

Si vous êtes déjà en version 18.0, il vous faudra supprimer les tables de sauvegardes liées à la migration 17 --> 18 et
la table STATUT_INTERVENANT
**avant** de migrer en 18.1.

```sql
DROP TABLE save_v18_dossier_autre_statut;
DROP TABLE save_v18_plafond;
DROP TABLE save_v18_plafond_app;
DROP TABLE save_v18_referentiel;
DROP TABLE save_v18_statut;
DROP TABLE save_v18_statut_privilege;
DROP TABLE save_v18_structure;
DROP TABLE save_v18_ta_statut;
DROP TABLE save_v18_tis;
DROP TABLE save_v18_tpjs;
DROP TABLE save_v18_dossier;
DROP TABLE save_v18_intervenant;
DROP TABLE save_v18_privilege;
DROP TABLE save_v18_role_privilege;
DROP TABLE statut_intervenant;

```

# OSE 18 (23/05/2022)

Objectif : Plafonds personnalisables & refonte gestion des statuts

## Nouveautés

* Nouvelle infrastructure de gestion des plafonds
    * Les plafonds sont maintenant personnalisables : vous pouvez les modifier en retirer ou en créer
    * [Une nouvelle documentation pour les plafonds](doc/Plafonds/Plafonds.md)
    * Les plafonds pourront être personnalisés le cas échéant :
        * par composante
        * par statut d'intervenant
        * par fonction référentielle
        * par élément pédagogique
        * par volume horaire (par élément pédagogique et par type d'intervention, exemple: nombre de CM en Maths)
    * Les paramétrages liés aux plafonds sont annualisés
    * Les plafonds pourront être utilisés comme de simples indicateurs
    * Des jauges relatives aux plafonds s'affichent dans la page de saisie de service
    * Des dérogations aux plafonds sont possibles par intervenant via un nouvel onglet dédié

* Indicateurs
    * Optimisation du chargement de la page des indicateurs
    * Gestion des dossiers irrecevables (#18307)
    * Extraction CSV des indicateurs (#19405)
    * Certains statuts pourront être affichés de manière prioritaire pour être traités en premier (#20808)
    * Possibilité d'envoyer en cci l'email des indicateurs (#40999)
    * Pour plus de cohérence, réorganisation et **changemenent de numéro des indicateurs**

* Saisie de service & référentiel
    * Par statut, vous pouvez maintenant choisir d'activer le prévisionnel et le réalisé de manière indépendante
    * Vous avez maintenant des privilèges distincts pour la saisie du service : un pour le prévisionnel et un pour le
      réalisé
    * Idem pour le référentiel
    * Idem pour les validations des services
    * Idem pour les validations du référentiel
    * Vous pouvez maintenant désactiver la possibilité de reporter le prévisionnel n-1 vers l'année en cours ou du
      prévisionnel vers le réalisé

* Contrats
    * Un nouveau modèle de contrat sera possible avec la ventilation des heures de services par types d'intervention (
      CM/TD/TP)
    * Paramétrage du mail expéditeur du contrat (Tâche #41014)
    * Vérification de la présence d'au moins un fichier avant de permettre l'enregistrement d'une date de retour signé

* Fiche Intervenant
    * Le grade devient modifiable dans la fiche pour les anciens intervenants #40369
    * Ajout d'un privilège 'Edition avancée' au niveau de l'intervenant pour donner le droit de modifier manuellement le
      code intervenant et la source de l'intervenant
    * Le code de l'intervenant peut devenir cliquable pour vous rediriger vers une page de gestion des comptes d'accès
      au SI ou autre (cf. notes de mise à jour, paramètre ldap>systemeInformationUrl)

* Export des intervenants vers Siham
    * Possiblité de récupérer plusieurs typeUO pour alimenter la liste des structures pour la PEC et la REN (#41454)
    * Nouveau paramètre dans administration > paramètres généraux permettant de choisir l'étape de la feuille de route à
      franchir pour pouvoir exporter un intervenant vers le SIRH
    * Meilleure gestion du pays de naissance lors de la PEC ou REN

* Ajout d'un module de gestion des Notes sur l'intervenant
    * Possibilité de rajouter une note écrite (informations, message important etc...) sur une fiche intervenant (Tâche
      #25565)
    * Possibilité d'envoyer un email à intervenant avec historisation de l'email directement depuis la fiche
      intervenant (Tâche #26546)
    * Historique des emails envoyés à l'intervenant (contrat, indicateur etc...)

* Interfaces d'administration
    * Les types de formations et les groupes les contenant pourront être ajoutés, supprimés ou modifiés depuis
      l'administration des types de formations.
    * Des périodes pourront être ajoutés, supprimés ou modifiés depuis l'administration des périodes.
    * Des établissements pourront être ajoutés, supprimés ou modifiés depuis l'administration des établissements.
    * Des pays pourront être ajoutés, supprimés ou modifiés depuis l'administration des pays.
    * Des départements pourront être ajoutés, supprimés ou modifiés depuis l'administration des départements.
    * Des corps pourront être ajoutés, supprimés ou modifiés depuis l'administration des corps présent dans le bloc d'
      administration des nomenclatures RH.
    * Améliorations ergonomiques de la "matrice" des privilèges
    * La page d'administration des statuts a été réécrite pour plus de clarté et de souplesse
        * Il n'est plus nécessaire de paramétrer les privilèges par statut, tout se passe désormais dans l'IHM d'
          administration des statuts
        * Les paramétrages de statuts sont maintenant annualisés

* En bref
    * Il est maintenant possible de choisir si on veut être connecté avec le CAS ou avec un compte LDAP ou local au
      moment du login (options désactivables)
    * Vous pouvez vous connecter avec l'identité d'un autre utilisateur à des fins de tests, si vous vous en donnez le
      droit (cf. [config.local.php](config.local.php.default), rubrique "ldap").
    * Nouvel état de sortie sur l'export des agréments, rendant celui-ci maintenant paramétrable par les
      établissements (#42944)
    * Les paramétrages de pièces justificatives par statut sont maintenant annualisés: il n'y a plus de notion d'année
      de début/année de fin
    * Les paramétrages des types d'intervention par statut sont également annualisés
    * [Technique] Migration vers Laminas et Composer 2
    * [Technique] Migration vers PHP 8.0

## Corrections de bugs (liste non exhaustive)

* Le service dû s'affiche de nouveau normalement dans la page "Calcul HETD" de l'intervenant
* Adaptation de la commande update-employeur pour assurer la compatibilité avec les différentes versions d'oracle
* Correction sur un problème de route dans l'écran Engagements & Liquidation (#38763)
* Export CSV des agréments : inversion de colonnes (#41513)
* Correction sur la suppression de service lorsque la clôture de service a été historisé (#42046)
* Le calcul des choix minimum/maximum est de nouveau fiable (#42080)
* Liens inactifs lors du changement d'année universitaire (#40992)
* Dans certains cas avec des motifs de non paiements, le détail des services n'affichait pas toutes les heures

## Notes de mise à jour

* Les indicateurs portant sur les anciens plafonds ayant été supprimés et remplacés par de tous nouveaux indicateurs,
  les notifications par mail et abonnements correspondants seront résiliés
* En raison de l'ampleur de la mise à jour, l'opération de maintenance va prendre du temps. Prévoyez une journée
  d'interruption de service.

## Procédure de mise à jour spécifique à la version 18

1. Mettez l'aplication en maintenance
2. Si votre version de OSE est antérieure à la version 17, mettez **d'abord** à jour en version **17.3**
3. Installez **PHP8.0** sur votre serveur ainsi que [toutes ses dépendances nécessaires](install.md)
4. Dans le répertoire de OSE, lancez `php composer.phar self-update --2`
5. Mettez ensuite OSE à jour en version 18 `./bin/ose update` (attention, ce traitement est long, il pourra prendre
   plusieurs heures)
6. Recalculez toutes les forules de calcul : `./bin/ose formule-calcul` (attention, ce traitement dure plusieurs heures)
7. Pour votre instance de production, la nouvelle commande `./bin/ose maj-exports` doit être lancée régulièrement (
   cf. [procédure d'installation](install.md)). Ceci met à jour toutes les vues matérialisées dédiées à l'export
   MV_EXT_*.
8. Mettez à jour votre vue source [SRC_INTERVENANT](doc/Connecteurs-Import/Générique/SRC_INTERVENANT.sql)
9. Réactivez la synchronisation en import pour la table INTERVENANT, que la mise à jour a volontairement désactivée (en
   production).
10. Mettez à jour votre [modèle de contrat de travail](data/modele_contrat_ventile.odt) si vous voulez bénéficier de la
    ventilation par CM/TD/TP/Autres des heures.
11. Si vous utilisez l'export RH Siham, renseignez un nouveau paramètre dans Administration > paramètres généraux >
    Gestion export RH, en sélectionnant l'étape de la feuille de route franchie à partir de laquelle l'intervenant peut
    être exporté dans SIHAM.
12. Au niveau du fichier de configuration [config.local.php](config.local.php.default), vous pouvez remplir les
    paramètres (facultatifs) ldap>systemeInformationUrl, les paramètres ldap>local et ldap>usurpationIdentite.
13. Sortez du mode maintenance

# OSE 17.3 (17/03/2022)

## Corrections de bugs

* Dans la page Offre de formation, le total des éléments par formations tient maintenant compte des éléments
  mutualisés (#42043)
* Lors de la saisie d'un nouveau service, le filtre par formation prend maintenant en compte les formations ne contenant
  que des éléments mutualisés (#40208)
* Formule de l'université de Université Paris-Est Créteil (UPEC) corrigée (#37737)

## Notes de mise à jour

* La mise à jour risque de bloquer à cause d'un fichier "composer.lock" situé dans le répertoire racine de
  l'application. Veuillez le supprimer manuellement pour que la mise à jour puisse se dérouler.

# OSE 17.2 (05/01/2022)

## Nouveautés

* Ajout d'un nouveau paramètre 'code-type-structure-affectation' dans le fichier de config unicaen-siham.local.php pour
  le module exportRH afin de rendre paramétrable le code type structure pour la remontée des structures d'affectation de
  SIHAM.

## Notes de mise à jour

* Pour les utilisateurs du module ExportRH Siham, il faut rajouter un nouveau paramètre dans le fichier
  unicaen-siham.local.php, qui correspond au code de type de structure SIHAM que vous souhaitez remonter pour la liste
  des structures d'affectation via les Webservices, exemple :

`'code-type-structure-affectation' => 'COP',`

# OSE 17.1 (07/12/2021)

## Nouveautés

* Ajout du numéro de SIRET au niveau de la table employeur et suppression de la contrainte d'unicité sur le SIREN (Tâche
  #40810)

## Corrections de bugs

* Correction sur l'horodatage du service prévisionnel qui se mettait à jour lors de la création ou suppresion d'un
  contrat (#40925)
* Correction de bug empêchant le calcul des charges d'enseignement (#40991)
* Prise en compte des accents dans les recherches (#40917)
* Correction sur la complétude des données personnelles lorsque la case 'INSEE provisoire' est cochée (#41141).
* Les modifications de motifs de non paiement fonctionnement de nouveau en mode calendaire (#40037)
* L'autovalidation fonctionne maintenant pour le référentiel (#41149)
* La constatation d'heures réalisées à partir du prévisionnel tient maintenant compte des horaires et des motifs de non
  paiements (#39202)
* Dans la fiche de service, les enseignements mutualisés sont précisés #40402

## Notes de mise à jour

* Si vous utilisez la commande ./bin/ose update-employeur pour charger la liste des employeurs INSEE, en passant en 17.1
  vous pourrez récupérer la notion de SIRET dans la table employeur, ainsi la contrainte d'unicité sur le SIREN sera
  également levée.
* Attention : lors de la mise à jour, vous verrez apparaître des messages d'erreur liés aux contraintes d'unicité
  modifiées avec des indexes non conformes. Il n'est pas nécessaire d'en tenir compte, puisque la mise à jour des
  indexes se fait juste après, ce qui rétablit la situation.

# OSE 17 (18/11/2021)

Objectif : Connecteur Export OSE => Logiciel RH

## Corrections de bugs

* Au niveau du connecteur Actul+, les formations fermées étaient ignorées, elles sont également synchronisées
* La suppression d'un statut intervenant n'était plus possible (#39548)
* Il était impossible de modifier les règles de validation des services via le formulaire de l'administration (#39194)
* Amélioration ergonomique du champ de saisie sur recherche (#40618)

## Nouveautés

* Module export intervenant de OSE vers SIHAM
* Nouvelle vue V_IMPORT_DEPUIS_DOSSIERS pouvant servir pour peupler les données des intervenants à partir des données
  personnelles
* Possibilité pour un gestionnire de saisir des heures d'enseignement ou de référentiel qui n'auront pas besoin d'être
  validées par la suite (nouveau privilège d'autovalidation associé)
* Ajout d'une colonne dans l'export CSV des charges précisant si un élément est mutualisé ou non
* Ajout de la composante hiérarchique de l'intervenant dans l'export csv des agréments (#40053)
* Ajout de la structure d'affectation d'un intervenant vacataire dans l'extraction de mise en paiement (#40180)
* Ajout d'un nouveau paramètre pour pouvoir spécifier un email expéditeur générique dans le cadre d'envoi d'email via
  les indicateurs (#40106)
* Affichage de l'horodatage du dossier de l'intervenant dans les données personnelles (#39014)
* Affichage du grade et de la discipline au niveau de la fiche intervenant (#39603)
* Synchronisation de l'email pro de la fiche intervenant avec le dossier de l'intervenant en cas de mise à jour (#39346)
* Paiements : Pour du référentiel, la répartition AA/AC tient maintenant compte du ratio configuré dans les paramètres
  généraux (#39695).
* Nouvelles optimisations du modules CHARGES, au niveau des seuils et des calculs d'effectifs
* Formule de calcul de l'Université Paris-Est Créteil
* Formule de calcul de l'Université de Guyane
* Nouvelle formule pour l'université d'Artois qui remplace la précédente (#40425)
* La saisie calendaire ne bloque plus lorsqu'un nouveau volume horaire a des horaires de début et de fin identiques (
  #40037)
* Périodes : le paiement tardif peut être supprimé (en BDD) et les périodes peuvent être d'avantage personnalisées (en
  BDD aussi) (#31050)

## Notes de mise à jour

* Si vous souhaitez mettre en place l'export RH vers Siham, il vous faudra le configurer. Vous avez pour cela la
  documentation :
    * [côté utilisateur](doc/Export-Rh/fonctionnalite.md)
    * [pour la configuration du connecteur](doc/Export-Rh/configuration.md)


# Anciennes versions de OSE

[C'est ici!](doc/anciennes-versions.md)
