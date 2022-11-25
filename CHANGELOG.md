# Version stable

[OSE 19.4](#ose-194-21102022)


# OSE 20 (à venir)

## Nouveautés

* Les modèles de contrats de travail sont maintenant gérés comme n'importe quel autre état de sortie
* Reconduction de l'offre de formation pour les éléments de OSE porté par un élément synchroniser
* Possibilité de rentrer un taux de charge par statut d'intervenant
* Ajout d'un choix par statut pour "contrat de travail et avenants" pour laisser la possibilité à l'intervenant de télécharger sont contrat en pdf
* Ajout d'un choix par statut pour "Modifications de service dû" pour laisser la possibilité à l'intervenant de modifier son service dû
* Migration technnique vers le framework Bootstrap 5 et modernisation de l'identité visuelle
* Réorganisation du menu "Administration" pour plus de lisibilité

## Corrections de bugs

* Sur la page "Services", la sélection d'un élément après selection d'une composante et d'une formation est désormais fonctionnel
* Correction des indicateur 910 et 920 qui étaient non fonctionnels dans le cas d'une autovalidation ou d'une absence de contrat
* Correction de la suppression d'un role dans la page d'administration des roles.
* Correction mineure sur les notes intervenants au niveau de l'historique (#46303)
* La durée de vie attendue des pièces justificatives est maintenant celle de l'année en cours et plus celle de l'année de dépôt de la pièce
* Les annulations de mises en paiement sont désormais bien prises en compte dès la première annulation
* Il est désormais possible de clôturer le service réalisé même si aucune heure n'est saisie
* Les indicateurs 530 et 540 ne renvoient plus de vacataires
* Les étapes d'ODF complémentaire peuvent de nouveau être modifiées (#46922)
* Dans les diagrammes du module Charges, la boite de dialogue s'affiche correctement, même avec beaucoup de types d'intervention
* Le plafond relatif aux charges / services saisis est maintenant opérationnel

## Notes de mise à jour

* Supprimer la ligne faisant référence à TBL_NOEUD dans Administration/Synchronisation/Tables, table NOEUD, champ "Traitements postérieurs : à exécuter après la synchro".
* La génération des contrats de travail ayant été remaniée, veuillez vérifier que vous pouvez générer correctement de nouveaux contrats de travail



# OSE 19.5 (à venir)

## Corrections de bugs

* Dans le module Charges, la saisie de seuils par défaut refonctionne normalement (#47451)
* Les plafonds de périmètre "volume horaire" sont de nouveau activables (#47340,#45225)
* Filtre des pays avec dates de validité périmées dans les listing des données personnelles (#47492)
* Correction sur le script de mise à jour des employeurs
* Correction sur les notes de l'intervenant au niveau de l'historique (#46303)
* Vue V_IMPORT_DEPUIS_DOSSIERS permettant de réinjecter les données personnelles dans les fiches corrigée (pb de filtre année) (#46769)



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
* Ajout d'un bouton de refus de pièce justificative avec envoie d'email à l'intervenant
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

# OSE 16 (14/09/2021)

Objectif : Connecteur import Actul+ & système différentiel pour comparer des charges d'enseignement

## Correction de bug

* Fiabilisation du calcul des charges d'enseignement (pb réglé au niveau des seuils qui n'étaient pas toujours les bons
  utilisés)

## Nouveautés

* [Connecteur en import avec Actul+](doc/Connecteurs-Import/Actul/Connecteur.md)
* Outil différentiel d'export des charges d'enseignement

# OSE 15.7 (14/09/2021)

## Correction de bugs

* Correction de la validation du numéro INSEE dans le dossier de l'intervenant dans le cas d'un département de naissance
  en Outre Mer (le numéro de département de naissance de l'INSEE dans ce cas peut être sur 2 ou 3 chiffres)
* Bug sur la prise en compte de règles multiples sur les pièces jointes par statut d'intervenant (date de début et date
  de fin)
* Bug [#39644](https://redmine.unicaen.fr/Etablissement/issues/39644) corrigé au niveau de la formule de calcul de
  l'Université d'Artois
* Au niveau des formules et en mode test uniquement, si le vacataire n'avait pas de composante d'affectation, les
  calculs pouvaient être faussés

# OSE 15.6 (14/09/2021)

## Correction de bugs

* Dans ODF, la liste des éléments dont on peut forcer la synchronisation tient maintenant compte des données à restaurer
  en plus de celles à insérer
* Correction sur le rafraichissement du rôle de l'intervenant lors d'un changement d'année universitaire (#39020)
* Correction sur la gestion des pièces jointes lors de l'archivage de celles-ci afin que cela impacte correctement la
  feuille de route et les indicateurs notamment pour les nouvelles pièces jointes à valider (#39195)
* Niveau Charges, lors de la duplication d'un scénario, le périmètre est pris en compte lors de la duplication pour ne
  pas écraser des données d'autres composantes à tort

# OSE 15.5 (01/07/2021)

## Correction de bugs

* Retour de la vua matérialisée MV_EXT_SERVICE qui avait disparu de OSE par erreur depuis la V15
* Correction d'un problème de MAJ de MV_EXT_SERVICE depuis la 15.4 qui provoquait une erreur suite à la l'ajout d'une
  colonne sur l'export des services.

# OSE 15.4 (30/06/2021)

## Nouveautés

* L'année minimale d'import de l'offre de formation est maintenant paramétrable dans les paramètres généraux
* On peut maintenant modifier les charges d'un élément pédagogique dans l'ODF s'il n'est plus synchronisé (cf. année
  minimale d'import de l'offre de formation).
* Formules de calcul de Sorbonne Nouvelle et de La Réunion
* Ajout de la colonne "code RH" à l'export CSV des services

## Correction de bugs

* Inversion d'affichage Fi et Fa dans administration > type de ressources (#38510)
* Meilleur rafraichissement de la feuille de route suite à la completion des données personnelles
* Le dossier intervenant ne se crée en base maintenant uniquement si l'utilisateur appuie sur le bouton 'enregistrer',
  afin d'éviter de créer des dossiers inutilement lors de la visualisation de la page données perso (#38835)

# OSE 15.3 (09/06/2021)

## Correction de bug

* Formule de Poitiers modifiée (pb de division par zéro relatif au plafond réf. corrigé) (#37741)

# OSE 15.2 (08/06/2021)

## Nouveautés

* Au niveau des types d'intervention, il est désormais possible de saisir des fractions (2/3 TP par exemple)
* Lorsqu'on sélectionne une formation dans la page Offre de formation, les éléments pédagogiques dont ce n'est pas l'
  étape principale sont listés tout de même #35881
* Formule de calcul de l'Université de Strasbourg, en remplacement celle de l'Ensicaen (règles identiques).
* Ajout d'un privilège 'Enseignement - Edition en masse' pour pouvoir différencier l'affichage du bouton 'Saisi d'un
  nouvel enseignement' dans la partie gestion service, de la partie feuille de route de l'intervenant (#36390)

## Corrections de bugs

* Vérification que le champs 'numéro de rue' contient uniquement des chiffres lors de l'enregistrement des données
  personnelles (#37492)
* Il n'est désormais plus possible de saisir un horaire de fin antérieur à celui de début en mode de saisie de service
  calendaire (#36319)
* Les plafonds sont de nouveau bloquants si trop d'heures prévisionnelles sont reportées en réalisé
* Suppression de la colonne 'Premier recrutement' de l'export CSV des agréments. (#38075)
* Correction du lien de 'Demande de mise en paiement' sur la feuille de route (#33025)
* Correction du lien vers la fiche intervenant dans le menu gestion service (#38166)
* Correction apparition d'un message de re-soumission du formulaire des données personnelles sur diverses actions (
  valider, devalider, supprimer etc...) (#38248)
* Redirection vers la fiche individuelle de l'intervenant lors de la suppression des données personnelles pour éviter de
  réinitialiser automatiquement le dossier (#37466)

# OSE 15.1 (06/05/2021)

## Nouveautés

* Ajout d'un privilège pour afficher / masquer l'adresse, email pro, email perso, téléphone (RGPD) sur la fiche
  intervenant
* Ajout d'un nouveau privilège pour dissocier le droit d'exporter en PDF les états de paiement et les mises en
  paiement (#35845)
* Ajout des volumes horaires par type d'intervention (CM,TP,TD) et du nombre de groupes par élément pédagogique dans
  l'extraction de l'offre de formation (#36625)
* Amélioration ergonomique dans l'écran de gestion des agréments par lot: visualisation de la fiche intervenant dans un
  nouvel onglet au lieu d'une fenêtre modale trop petite qui provoquait notamment un dysfonctionnement de l'affichage
  des PJ (#37269)
* Les heures payées en année antérieure / année en cours (AA/AC) peuvent être réparties de manière personnalisée,
  autrement qu'en 4/6 - 6/10. Pour en savoir plus, vous pouvez consulter la documentation administrateur.
* Formules de calcul de Paris, Artois, Lille
* Formule de calcul de Poitiers mise à jour
* Augmentation de la taille des libellés pour les fonctions référentielles

## Corrections de bugs

* Correction d'un bug de rafraichissement des pièces jointes dans le scénario suivant : dévalidation de la pièce jointe,
  suppression du fichier, téléversement du nouveau fichier.
* La constatation des services réalisés par un gestionnaire ne se fait désormais que dans le périmètre de sa composante.

## Notes de mise à jour

Si vous voulez activer le filtrage dans/hors établissement (recommandé), une nouvelle documentation est
disponible : [Documentation](doc/detection-etablissement-ou-extérieur.md)

# OSE 15 (12/03/21)

Objectif : Doubles statuts et refonte des données personnelles

## Nouveautés

* Refonte complète de la gestion des données personnelles
    * Gestion des employeurs (avec utilisation possible de la base SIRENE)
    * Possibilité d'enregistrer un dossier incomplet, avec gestion du taux de complétude
    * Masquage des données sensibles (mise en conformité RGPD) par rôle (gestionnaire etc...)
    * Possibilité d'ajouter des champs supplémentaires (5 maximum)
    * Nouveau format pour les adresses
    * Paramétrage des conditions de remplissage des mails et téléphones personnels (obligatoires si pas de mail/tél pro
      ou bien tout le temps)
* Possibilité pour un intervenant d'avoir simultanément plusieurs statuts
    * Le nouveau statut peut être ajouté dans l'application ou bien être fourni via le connecteur IMPORT
    * La bascule d'un statut à un autre se fait en cliquant sur le statut désiré directement sur la fiche de l'
      intervenant
    * Pour chaque statut, l'intervenant a une fiche distincte, avec des services distincts, etc. Les pièces
      justificatives et les agréments sont communs.
* Refonte de la gestion des intervenants
    * Possibilité de créer un nouvel intervenant local au moyen d'une IHM
    * Possibilité de pouvoir rechercher et visualiser des intervenants historisés
    * Possibilité d'historiser et de restaurer des intervenants
    * Possibilité de synchroniser un intervenant directement depuis sa fiche
    * Possibilité d'associer un utilisateur LDAP à un intervenant nouvellement créé
    * Possibilité de créer directement dans le formulaire INTERVENANT un nouvel utilisateur avec saisie de login/MDP.
* Possibilité de forcer la composante d'affectation d'un intervenant et d'ignorer celle fournie par le connecteur
* Possibilité de forcer le statut d'un intervenant dans OSE et d'ignorer celui fourni par le connecteur (même pour un
  permanent)
* Les vues matérialisées sont recalculées à chaque mise à jour
* Amélioration importante des performances pour le calcul des tableaux de bord intermédiaires
* Adaptations du connecteur Harpège
* Possibilité d'importer uniquement un élément pédagogique depuis la page "Offre de formation"
* Possibilité de mettre à jour par synchronisation et manuellement un élément pédagogique spécifique par déclenchement
  d'import
* Ajout d'un nouveau privilège 'Archivage' pour donner la possiblité à un statut d'intervenant de mettre à jour une
  pièce jointe lorsque celle ci a été fourni une année antérieure à l'année en cours (Bouton "Modifier si besoin")
* Avenants aux contrats de travail : les heures s'affichant sur les avenants ne reprennent plus les heures du contrat,
  mais n'affichent que le différentiel

## Corrections de bugs

* La suppression d'intervenants est maintenant pleinement opérationnelle et les erreurs sont mieux affichées
* Formule de Poitiers modifiée

## Notes de mise à jour

Merci de lire ceci **AVANT** d'entamer la mise à jour!!

La mise à jour n'est en effet pas réversible.

Nous vous recommandons en outre de vous entrainer au préalable sur une instance de préproduction avant de passer en
production.

### 1. PHP7.4

PHP 7.4 est maintenant requis : attention à bien mettre à jour vos serveurs

### 2. OSE 14.17 minimum

Pour cette version, il n'est pas possible de migrer depuis de trop anciennes instances de OSE.
Avant la V15, vous devrez préalablement migrer en version 14.17.
Et ce n'est qu'à partir de la 14.17 que vous pourrez migrer vers la 15.

### 3. Connecteurs

La structure de la base de données OSE a évolué.
Voici pour information la liste des changements opérés au niveau des structures de
données : ([Changements de structures BDD 14->15](doc/Divers/migration-bdd-14-vers-15.sql)).
Ce script ne doit pas être exécuté, la procédure de migration se chargera de cela toute seule.

Certains de vos connecteurs devront être adaptés, en particulier au niveau RH.
De même, si vous avez créé des requêtes personnalisées, des états de sortie, attention à bien tenir compte de ces
changmements!

Au niveau des connecteurs, les changements à faire sont les suivants :

* Vue source [SRC_PAYS](doc/Connecteurs-Import/Création-tables/PAYS.md) :
    * LIBELLE_COURT et LIBELLE_LONG disparaissent au profit de LIBELLE
    * nouvelle colonne CODE
* Vue source [SRC_DEPARTEMENT](doc/Connecteurs-Import/Création-tables/DEPARTEMENT.md) :
    * LIBELLE_COURT et LIBELLE_LONG disparaissent au profit de LIBELLE
    * nouvelle colonne CODE
* Nouvelle table [VOIRIE](doc/Connecteurs-Import/Création-tables/VOIRIE.md) :
    * Possibilité d'importer les voiries en provenance de votre système d'information.
* Vue source [SRC_STRUCTURE](doc/Connecteurs-Import/Création-tables/STRUCTURE.md) :
    * Changement du format des adresses. Vouc pourrez vous inspirer des différents connecteurs existants pour adapter le
      votre.
* Vue source [SRC_INTERVENANT](doc/Connecteurs-Import/Générique/SRC_INTERVENANT.sql) :
    * Il y a ici de nombreux changements.
    * La vue matérialisée [MV_INTERVENANT](doc/Connecteurs-Import/Création-tables/INTERVENANT.md) devra être adaptée
      pour fournir toutes les colonnes nécessaires.
    * La vue [SRC_INTERVENANT](doc/Connecteurs-Import/Générique/SRC_INTERVENANT.sql) doit être utilisée telle quelle,
      sans adaptation de votre part
* Suppression d'anciennes tables, dont les vues sources correspondantes doivent être supprimées par vos soins :
    * DROP VIEW V_DIFF_ADRESSE_INTERVENANT
    * DROP VIEW SRC_ADRESSE_INTERVENANT
    * DROP VIEW V_DIFF_ADRESSE_STRUCTURE
    * DROP VIEW SRC_ADRESSE_STRUCTURE
    * Ces vues devront être supprimées AVANT la mise à jour. Le script de migration ne le fait pas automatiquement afin
      de vous laisser le temps de les sauvegarder le cas échéant.

Plus
généralement, [une nouvelle documentation sur les connecteurs est disponible](doc/Connecteurs-Import/Connecteurs-IMPORT.md)
.

### 4. Activation du stockage des fichiers dans le filesystem

Pas obligatoire, mais recommandé (sur votre instance de production).

* [Activer le stockage des fichiers dans le système de fichiers plutôt qu'en base de données (recommandé pour la production)](doc/Stockage-fichiers.md)

### 5. Gestion des employeurs

OSE peut maintenant gérer un référentiel des employeurs, permettant ainsi d'activer au niveau des données personnelles
la partie "Employeur" (non activée par défaut et à paramétrer pour chacun des statuts intervenant de votre instance OSE)

Pour alimenter la table employeur de OSE, vous avez deux possiblités :

* soit importer votre propre liste d'employeurs via une vue
  source [SRC_EMPLOYEUR](doc/Connecteurs-Import/Création-tables/EMPLOYEUR.md) dédiée, à l'instar des autres connecteurs
  et ainsi alimenter la table employeur en la synchronisant avec votre vue source.
* soit utiliser le référentiel sirene officiel
  de [data.gouv.fr](https://www.data.gouv.fr/fr/datasets/base-sirene-des-entreprises-et-de-leurs-etablissements-siren-siret/)
  que nous vous préparons et mettons à disposition avec une mise à jour régulière. Pour cela vous devez utiliser la
  commande `./bin/ose update-employeur` qui se chargera de remplir la table employeur avec ces données. Cette commande
  devra être exécutée de manière régulière, une fois par mois environ si vous voulez que votre référentiel d'employeurs
  soit à jour.

# Anciennes versions de OSE

[C'est ici!](doc/anciennes-versions.md)
