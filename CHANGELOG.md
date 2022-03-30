# Version stable

[OSE 17.3](#ose-173-17032022)





# OSE 18 (à venir)
Objectif : Plafonds personnalisables & refonte gestion des statuts

## Nouveautés

* Le grade devient modifiable dans la fiche pour les anciens intervenants #40369
* Depuis la fiche de service de l'intervenant, vous pouvez maintenant désactiver la possibilité de reporter le prévisionnel n-1 vers l'année en cours ou du prévisionnel vers le réalisé
* Technique : 
  * migration vers Laminas et Composer 2
  * migration vers PHP 8
* Nouvelle infrastructure de gestion des plafonds 
  * Les plafonds sont maintenant personnalisables : vous pouvez les modifier en retirer ou en créer
  * [Une nouvelle documentation pour les plafonds](doc/Plafonds/Plafonds.md)
  * Les plafonds pourront être personnalisés le cas échéant :
    * par composante
    * par statut d'intervenant
    * par fonction référentielle
  * Les paramétrages liés aux plafonds sont annualisés
  * Des dérogations aux plafonds sont possibles par intervenant
* Paramétrage du mail expéditeur du contrat (Tâche #41014)
* Possibilité d'envoyer en cci l'email des indicateurs (#40999)
* Evolution des indicateurs
  * gestion des dossiers irrecevables (#18307)
  * extraction CSV des indicateurs (#19405)
  * certains statuts pourront être affichés de manière prioritaire pour être traités en premier (#20808)
* Refonte de l'interface d'administration des statuts
  * Les paramétrages de statuts sont maintenant annualisés
* Nouveau paramètre dans administration > paramètre généraux permettant de choisir l'étape de la feuille de route à franchir pour pouvoir exporter un
  intervenant vers le SIRH
* Ajout d'un privilège 'Edition avancée' au niveau de l'intervenant pour donner le droit de modifier manuellement le code intervenant et la source de l'
  intervenant
* Vérification de la présence d'au moins un fichier avant de permettre l'enregistrement d'une date de retour signé
* Un nouveau modèle de contrat sera possible avec la ventilation des heures de services par types d'intervention (CM/TD/TP)
* Des périodes pourront être ajoutés, supprimés ou modifiés depuis l'administration des périodes.
* Les types de formations et les groupes les contenant pourront être ajoutés, supprimés ou modifiés depuis l'administration des types de formations.
* Nouvel état de sortie sur l'export des agréments, rendant celui-ci maintenant paramétrable par les établissements (#42944)
* Ajout d'un module de gestion des Notes sur l'intervenant : 
  * Possibilité de rajouter une note écrite (informations, message important etc...) sur une fiche intervenant (Tâche #25565)
  * Possibilité d'envoyer un email à intervenant avec historisation de l'email directement depuis la fiche intervenant (Tâche #26546)
  * Historique des emails envoyés à l'intervenant (contrat, indicateur etc...)


## Corrections de bugs

* Le service dû s'affiche de nouveau normalement dans la page "Calcul HETD" de l'intervenant
* Adaptation de la commande update-employeur pour assurer la compatibilité avec les différentes versions d'oracle
* Correction sur un problème de route dans l'écran Engagements & Liquidation (#38763)
* Export CSV des agréments : inversion de colonnes (#41513)
* Correction sur la suppression de service lorsque la clôture de service a été historisé (#42046)

## Notes de mise à jour

* **ATTENTION : OSE 18** ne pourra être mis à jour **qu'à partir de OSE 17.x**. Si vous utilisez une version plus ancienne de OSE, veuillez **d'abord** mettre à jour en version 17.
* **ATTENTION : PHP 8.0** est requis
* La mise à jour des vues matérialisées MV_EXT_* ne se fait plus à la mise à jour. Il faut maintenant lancer la commande `./bin/ose maj-exports` tous les jours et donc ajouter une ligne à votre _CronTab_ (cf. [Doc INSTALL mise à jour](install.md))
* Pour bénéficier de la ventilation des heures par types d'intervention vous pouvez vous inspirer du [modèle de contrat de Caen](https://git.unicaen.fr/open-source/OSE/-/blob/master/data/modele_contrat_ventile.odt) pour adapter votre propre modèle de contrat.



# OSE 17.3 (17/03/2022)

## Corrections de bugs

* Dans la page Offre de formation, le total des éléments par formations tient maintenant compte des éléments mutualisés (#42043)
* Lors de la saisie d'un nouveau service, le filtre par formation prend maintenant en compte les formations ne contenant que des éléments mutualisés (#40208)
* Formule de l'université de Université Paris-Est Créteil (UPEC) corrigée (#37737)

## Notes de mise à jour

* La mise à jour risque de bloquer à cause d'un fichier "composer.lock" situé dans le répertoire racine de l'application. Veuillez le supprimer manuellement pour que la mise à jour puisse se dérouler. 


# OSE 17.2 (05/01/2022)

## Nouveautés

* Ajout d'un nouveau paramètre 'code-type-structure-affectation' dans le fichier de config unicaen-siham.local.php pour le module exportRH afin de rendre paramétrable le code type structure pour la remontée des structures d'affectation de SIHAM.

## Notes de mise à jour

* Pour les utilisateurs du module ExportRH Siham, il faut rajouter un nouveau paramètre dans le fichier unicaen-siham.local.php, qui correspond au code de type de structure SIHAM que vous souhaitez remonter pour la liste des structures d'affectation via les Webservices, exemple : 

`'code-type-structure-affectation' => 'COP',`


# OSE 17.1 (07/12/2021)

## Nouveautés

* Ajout du numéro de SIRET au niveau de la table employeur et suppression de la contrainte d'unicité sur le SIREN (Tâche #40810)

## Corrections de bugs

* Correction sur l'horodatage du service prévisionnel qui se mettait à jour lors de la création ou suppresion d'un contrat (#40925)
* Correction de bug empêchant le calcul des charges d'enseignement (#40991)
* Prise en compte des accents dans les recherches (#40917)
* Correction sur la complétude des données personnelles lorsque la case 'INSEE provisoire' est cochée (#41141).
* Les modifications de motifs de non paiement fonctionnement de nouveau en mode calendaire (#40037)
* L'autovalidation fonctionne maintenant pour le référentiel (#41149)
* La constatation d'heures réalisées à partir du prévisionnel tient maintenant compte des horaires et des motifs de non paiements (#39202)
* Dans la fiche de service, les enseignements mutualisés sont précisés #40402

## Notes de mise à jour

* Si vous utilisez la commande ./bin/ose update-employeur pour charger la liste des employeurs INSEE, en passant en 17.1 vous pourrez récupérer la notion de SIRET dans la table employeur, ainsi la contrainte d'unicité sur le SIREN sera également levée.
* Attention : lors de la mise à jour, vous verrez apparaître des messages d'erreur liés aux contraintes d'unicité modifiées avec des indexes non conformes. Il n'est pas nécessaire d'en tenir compte, puisque la mise à jour des indexes se fait juste après, ce qui rétablit la situation.



# OSE 17 (18/11/2021)
Objectif : Connecteur Export OSE => Logiciel RH

## Corrections de bugs

* Au niveau du connecteur Actul+, les formations fermées étaient ignorées, elles sont également synchronisées
* La suppression d'un statut intervenant n'était plus possible (#39548)
* Il était impossible de modifier les règles de validation des services via le formulaire de l'administration (#39194)
* Amélioration ergonomique du champ de saisie sur recherche (#40618)

## Nouveautés

* Module export intervenant de OSE vers SIHAM
* Nouvelle vue V_IMPORT_DEPUIS_DOSSIERS pouvant servir pour peupler les données des intervenants à partir des données personnelles
* Possibilité pour un gestionnire de saisir des heures d'enseignement ou de référentiel qui n'auront pas besoin d'être validées par la suite (nouveau privilège d'autovalidation associé)
* Ajout d'une colonne dans l'export CSV des charges précisant si un élément est mutualisé ou non
* Ajout de la composante hiérarchique de l'intervenant dans l'export csv des agréments (#40053)
* Ajout de la structure d'affectation d'un intervenant vacataire dans l'extraction de mise en paiement (#40180)
* Ajout d'un nouveau paramètre pour pouvoir spécifier un email expéditeur générique dans le cadre d'envoi d'email via les indicateurs (#40106)
* Affichage de l'horodatage du dossier de l'intervenant dans les données personnelles (#39014)
* Affichage du grade et de la discipline au niveau de la fiche intervenant (#39603)
* Synchronisation de l'email pro de la fiche intervenant avec le dossier de l'intervenant en cas de mise à jour (#39346)
* Paiements : Pour du référentiel, la répartition AA/AC tient maintenant compte du ratio configuré dans les paramètres généraux (#39695).
* Nouvelles optimisations du modules CHARGES, au niveau des seuils et des calculs d'effectifs
* Formule de calcul de l'Université Paris-Est Créteil
* Formule de calcul de l'Université de Guyane
* Nouvelle formule pour l'université d'Artois qui remplace la précédente (#40425)
* La saisie calendaire ne bloque plus lorsqu'un nouveau volume horaire a des horaires de début et de fin identiques (#40037)
* Périodes : le paiement tardif peut être supprimé (en BDD) et les périodes peuvent être d'avantage personnalisées (en BDD aussi) (#31050)

## Notes de mise à jour

* Si vous souhaitez mettre en place l'export RH vers Siham, il vous faudra le configurer. Vous avez pour cela la documentation :
  * [côté utilisateur](doc/Export-Rh/fonctionnalite.md) 
  * [pour la configuration du connecteur](doc/Export-Rh/configuration.md)





# OSE 16 (14/09/2021)
Objectif : Connecteur import Actul+ & système différentiel pour comparer des charges d'enseignement

## Correction de bug

* Fiabilisation du calcul des charges d'enseignement (pb réglé au niveau des seuils qui n'étaient pas toujours les bons utilisés)

## Nouveautés

* [Connecteur en import avec Actul+](doc/Connecteurs-Import/Actul/Connecteur.md)
* Outil différentiel d'export des charges d'enseignement



# OSE 15.7 (14/09/2021)

## Correction de bugs

* Correction de la validation du numéro INSEE dans le dossier de l'intervenant dans le cas d'un département de naissance en Outre Mer (le numéro de département de naissance de l'INSEE dans ce cas peut être sur 2 ou 3 chiffres)
* Bug sur la prise en compte de règles multiples sur les pièces jointes par statut d'intervenant (date de début et date de fin)
* Bug [#39644](https://redmine.unicaen.fr/Etablissement/issues/39644) corrigé au niveau de la formule de calcul de l'Université d'Artois
* Au niveau des formules et en mode test uniquement, si le vacataire n'avait pas de composante d'affectation, les calculs pouvaient être faussés 

# OSE 15.6 (14/09/2021)

## Correction de bugs

* Dans ODF, la liste des éléments dont on peut forcer la synchronisation tient maintenant compte des données à restaurer en plus de celles à insérer
* Correction sur le rafraichissement du rôle de l'intervenant lors d'un changement d'année universitaire (#39020)
* Correction sur la gestion des pièces jointes lors de l'archivage de celles-ci afin que cela impacte correctement la feuille de route et les indicateurs notamment pour les nouvelles pièces jointes à valider (#39195)
* Niveau Charges, lors de la duplication d'un scénario, le périmètre est pris en compte lors de la duplication pour ne pas écraser des données d'autres composantes à tort


# OSE 15.5 (01/07/2021)

## Correction de bugs

* Retour de la vua matérialisée MV_EXT_SERVICE qui avait disparu de OSE par erreur depuis la V15
* Correction d'un problème de MAJ de MV_EXT_SERVICE depuis la 15.4 qui provoquait une erreur suite à la l'ajout d'une colonne sur l'export des services.  





# OSE 15.4 (30/06/2021)

## Nouveautés

* L'année minimale d'import de l'offre de formation est maintenant paramétrable dans les paramètres généraux
* On peut maintenant modifier les charges d'un élément pédagogique dans l'ODF s'il n'est plus synchronisé (cf. année minimale d'import de l'offre de formation).
* Formules de calcul de Sorbonne Nouvelle et de La Réunion
* Ajout de la colonne "code RH" à l'export CSV des services

## Correction de bugs

* Inversion d'affichage Fi et Fa dans administration > type de ressources (#38510)
* Meilleur rafraichissement de la feuille de route suite à la completion des données personnelles
* Le dossier intervenant ne se crée en base maintenant uniquement si l'utilisateur appuie sur le bouton 'enregistrer', afin d'éviter de créer des dossiers inutilement lors de la visualisation de la page données perso (#38835)





# OSE 15.3 (09/06/2021)

## Correction de bug

* Formule de Poitiers modifiée (pb de division par zéro relatif au plafond réf. corrigé) (#37741)





# OSE 15.2 (08/06/2021)

## Nouveautés

* Au niveau des types d'intervention, il est désormais possible de saisir des fractions (2/3 TP par exemple)
* Lorsqu'on sélectionne une formation dans la page Offre de formation, les éléments pédagogiques dont ce n'est pas l'étape principale sont listés tout de même #35881
* Formule de calcul de l'Université de Strasbourg, en remplacement celle de l'Ensicaen (règles identiques).
* Ajout d'un privilège 'Enseignement - Edition en masse' pour pouvoir différencier l'affichage du bouton 'Saisi d'un nouvel enseignement' dans la partie gestion service, de la partie feuille de route de l'intervenant (#36390)

## Corrections de bugs

* Vérification que le champs 'numéro de rue' contient uniquement des chiffres lors de l'enregistrement des données personnelles (#37492)
* Il n'est désormais plus possible de saisir un horaire de fin antérieur à celui de début en mode de saisie de service calendaire (#36319)
* Les plafonds sont de nouveau bloquants si trop d'heures prévisionnelles sont reportées en réalisé
* Suppression de la colonne 'Premier recrutement' de l'export CSV des agréments. (#38075)
* Correction du lien de 'Demande de mise en paiement' sur la feuille de route (#33025)
* Correction du lien vers la fiche intervenant dans le menu gestion service (#38166)
* Correction apparition d'un message de re-soumission du formulaire des données personnelles sur diverses actions (valider, devalider, supprimer etc...) (#38248)
* Redirection vers la fiche individuelle de l'intervenant lors de la suppression des données personnelles pour éviter de réinitialiser automatiquement le dossier (#37466)





# OSE 15.1 (06/05/2021)

## Nouveautés

* Ajout d'un privilège pour afficher / masquer l'adresse, email pro, email perso, téléphone (RGPD) sur la fiche intervenant
* Ajout d'un nouveau privilège pour dissocier le droit d'exporter en PDF les états de paiement et les mises en paiement (#35845)
* Ajout des volumes horaires par type d'intervention (CM,TP,TD) et du nombre de groupes par élément pédagogique dans l'extraction de l'offre de formation (#36625) 
* Amélioration ergonomique dans l'écran de gestion des agréments par lot: visualisation de la fiche intervenant dans un nouvel onglet au lieu d'une fenêtre modale trop petite qui provoquait notamment un dysfonctionnement de l'affichage des PJ (#37269)
* Les heures payées en année antérieure / année en cours (AA/AC) peuvent être réparties de manière personnalisée, autrement qu'en 4/6 - 6/10. Pour en savoir plus, vous pouvez consulter la documentation administrateur.
* Formules de calcul de Paris, Artois, Lille
* Formule de calcul de Poitiers mise à jour
* Augmentation de la taille des libellés pour les fonctions référentielles

## Corrections de bugs
* Correction d'un bug de rafraichissement des pièces jointes dans le scénario suivant : dévalidation de la pièce jointe, suppression du fichier, téléversement du nouveau fichier.
* La constatation des services réalisés par un gestionnaire ne se fait désormais que dans le périmètre de sa composante.

## Notes de mise à jour

Si vous voulez activer le filtrage dans/hors établissement (recommandé), une nouvelle documentation est disponible : [Documentation](doc/detection-etablissement-ou-extérieur.md)


# OSE 15 (12/03/21)
Objectif : Doubles statuts et refonte des données personnelles

## Nouveautés

* Refonte complète de la gestion des données personnelles
  * Gestion des employeurs (avec utilisation possible de la base SIRENE)
  * Possibilité d'enregistrer un dossier incomplet, avec gestion du taux de complétude
  * Masquage des données sensibles (mise en conformité RGPD) par rôle (gestionnaire etc...) 
  * Possibilité d'ajouter des champs supplémentaires (5 maximum)
  * Nouveau format pour les adresses
  * Paramétrage des conditions de remplissage des mails et téléphones personnels (obligatoires si pas de mail/tél pro ou bien tout le temps)
* Possibilité pour un intervenant d'avoir simultanément plusieurs statuts
  * Le nouveau statut peut être ajouté dans l'application ou bien être fourni via le connecteur IMPORT
  * La bascule d'un statut à un autre se fait en cliquant sur le statut désiré directement sur la fiche de l'intervenant
  * Pour chaque statut, l'intervenant a une fiche distincte, avec des services distincts, etc. Les pièces justificatives et les agréments sont communs.
* Refonte de la gestion des intervenants
  * Possibilité de créer un nouvel intervenant local au moyen d'une IHM
  * Possibilité de pouvoir rechercher et visualiser des intervenants historisés
  * Possibilité d'historiser et de restaurer des intervenants
  * Possibilité de synchroniser un intervenant directement depuis sa fiche 
  * Possibilité d'associer un utilisateur LDAP à un intervenant nouvellement créé
  * Possibilité de créer directement dans le formulaire INTERVENANT un nouvel utilisateur avec saisie de login/MDP.
* Possibilité de forcer la composante d'affectation d'un intervenant et d'ignorer celle fournie par le connecteur
* Possibilité de forcer le statut d'un intervenant dans OSE et d'ignorer celui fourni par le connecteur (même pour un permanent)
* Les vues matérialisées sont recalculées à chaque mise à jour
* Amélioration importante des performances pour le calcul des tableaux de bord intermédiaires
* Adaptations du connecteur Harpège
* Possibilité d'importer uniquement un élément pédagogique depuis la page "Offre de formation"
* Possibilité de mettre à jour par synchronisation et manuellement un élément pédagogique spécifique par déclenchement d'import
* Ajout d'un nouveau privilège 'Archivage' pour donner la possiblité à un statut d'intervenant de mettre à jour une pièce jointe lorsque celle ci a été fourni une année antérieure à l'année en cours (Bouton "Modifier si besoin")
* Avenants aux contrats de travail : les heures s'affichant sur les avenants ne reprennent plus les heures du contrat, mais n'affichent que le différentiel

## Corrections de bugs

* La suppression d'intervenants est maintenant pleinement opérationnelle et les erreurs sont mieux affichées
* Formule de Poitiers modifiée

## Notes de mise à jour

Merci de lire ceci **AVANT** d'entamer la mise à jour!!

La mise à jour n'est en effet pas réversible.

Nous vous recommandons en outre de vous entrainer au préalable sur une instance de préproduction avant de passer en production.

### 1. PHP7.4
PHP 7.4 est maintenant requis : attention à bien mettre à jour vos serveurs

### 2. OSE 14.17 minimum

Pour cette version, il n'est pas possible de migrer depuis de trop anciennes instances de OSE.
Avant la V15, vous devrez préalablement migrer en version 14.17.
Et ce n'est qu'à partir de la 14.17 que vous pourrez migrer vers la 15.

### 3. Connecteurs

La structure de la base de données OSE a évolué.
Voici pour information la liste des changements opérés au niveau des structures de données : ([Changements de structures BDD 14->15](doc/Divers/migration-bdd-14-vers-15.sql)).
Ce script ne doit pas être exécuté, la procédure de migration se chargera de cela toute seule.

Certains de vos connecteurs devront être adaptés, en particulier au niveau RH.
De même, si vous avez créé des requêtes personnalisées, des états de sortie, attention à bien tenir compte de ces changmements!

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
  * Changement du format des adresses. Vouc pourrez vous inspirer des différents connecteurs existants pour adapter le votre.
* Vue source [SRC_INTERVENANT](doc/Connecteurs-Import/Générique/SRC_INTERVENANT.sql) :
  * Il y a ici de nombreux changements.
  * La vue matérialisée [MV_INTERVENANT](doc/Connecteurs-Import/Création-tables/INTERVENANT.md) devra être adaptée pour fournir toutes les colonnes nécessaires.
  * La vue [SRC_INTERVENANT](doc/Connecteurs-Import/Générique/SRC_INTERVENANT.sql) doit être utilisée telle quelle, sans adaptation de votre part
* Suppression d'anciennes tables, dont les vues sources correspondantes doivent être supprimées par vos soins :
  * DROP VIEW V_DIFF_ADRESSE_INTERVENANT
  * DROP VIEW SRC_ADRESSE_INTERVENANT
  * DROP VIEW V_DIFF_ADRESSE_STRUCTURE
  * DROP VIEW SRC_ADRESSE_STRUCTURE
  * Ces vues devront être supprimées AVANT la mise à jour. Le script de migration ne le fait pas automatiquement afin de vous laisser le temps de les sauvegarder le cas échéant.

Plus généralement, [une nouvelle documentation sur les connecteurs est disponible](doc/Connecteurs-Import/Connecteurs-IMPORT.md).

### 4. Activation du stockage des fichiers dans le filesystem

Pas obligatoire, mais recommandé (sur votre instance de production).

* [Activer le stockage des fichiers dans le système de fichiers plutôt qu'en base de données (recommandé pour la production)](doc/Stockage-fichiers.md)

### 5. Gestion des employeurs

OSE peut maintenant gérer un référentiel des employeurs, permettant ainsi d'activer au niveau des données personnelles la partie "Employeur" (non activée par défaut et à paramétrer pour chacun des statuts intervenant de votre instance OSE)

Pour alimenter la table employeur de OSE, vous avez deux possiblités :
 * soit importer votre propre liste d'employeurs via une vue source [SRC_EMPLOYEUR](doc/Connecteurs-Import/Création-tables/EMPLOYEUR.md) dédiée, à l'instar des autres connecteurs et ainsi alimenter la table employeur en la synchronisant avec votre vue source.
 * soit utiliser le référentiel sirene officiel de [data.gouv.fr](https://www.data.gouv.fr/fr/datasets/base-sirene-des-entreprises-et-de-leurs-etablissements-siren-siret/) que nous vous préparons et mettons à disposition avec une mise à jour régulière. Pour cela vous devez utiliser la commande `./bin/ose update-employeur` qui se chargera de remplir la table employeur avec ces données. Cette commande devra être exécutée de manière régulière, une fois par mois environ si vous voulez que votre référentiel d'employeurs soit à jour.



# OSE 14.20 (09/06/2021)

## Correction de bug

* Formule de Poitiers modifiée (pb de division par zéro relatif au plafond réf. corrigé) (#37741)




# OSE 14.19 (08/06/2021)

# Nouveautés

* Ajout d'un privilège 'Enseignement - Edition en masse' pour pouvoir différencier l'affichage du bouton 'Saisi d'un nouvel enseignement' dans la partie gestion service, de la partie feuille de route de l'intervenant (#36390)

## Corrections de bugs

* Suppression de la colonne 'Premier recrutement' de l'export CSV des agréments. (#38075)
* Correction du lien de 'Demande de mise en paiement' sur la feuille de route (#33025)
* Correction d'un bug sur l'export csv des états de paiement (#38076)


# OSE 14.18 (06/05/2021)

## Nouveautés

* Formules de calcul de Paris, Artois, Lille
* Formule de calcul de Poitiers mise à jour
* Création d'un nouveau privilèges pour dissocier le droit sur l'export pdf des états de paiement et l'export pdf des mises en paiement (#35845)
* La constatation des services réalisés par un gestionnaire ne se fait désormais que dans le périmètre de sa composante.
* Augmentation de la taille des libellés pour les fonctions référentielles


# OSE 14.17 (11/03/21)

## Corrections de bugs

* Le report des services de l'année précédente par un gestionnaire est désormais limité à sa composante.
* Correction au niveau du franchissement de l'étape contrat dans le cas de présence d'heures de service référentiel (#35495)


# OSE 14.16 (10/02/21)

## Nouveautés

* Formule de calcul de Paris8
* Nouveau privilège donnant la possibilité de saisir du référentiel sans aucune contrainte sur la composante

## Corrections de bugs

* Erreur sur l'édition d'une dotation au niveau budget (#33703)
* Correction ordre d'affichage des pièces jointes pour l'intervenant comme paramétré dans l'adminstration (#34324)
* Ajout de deux nouveaux indicateurs (730 et 740), identiques aux 710 et 720 mais pour les permanents (#34577)
* Bug sur validation/dévalidation d'une pièce jointe (erreur 403) dans le cas d'un paramétrage de workflow sans service prévisionnel (#34526)
* Formule de calcul de Poitiers mise à jour

# OSE 14.15 (09/12/20)

## Corrections de bugs

* Permettre à un intervenant d'accéder l'année N à ses pièces justificatives d'une année N-X (suite correction faille sécurité)
* Simplification et correction du fonctionnement de l'interface d'admin "Règles de validation des enseignements par type d'intervenant"


# OSE 14.14 (30/11/20)

## Corrections de bugs

* La fiche de saisie de service est désormais rétablie, un bug bloquait son usage depuis la 14.13.



# OSE 14.13

## Corrections de bugs

* Refactoring de l'indicateur 320 qui n'affichait plus uniquement les intervenants en attente de la création d'un contrat initial
* Refactoring de l'indicateur 330 : affiche maintenant uniquement les intervenants en attente d'un avenant
* Faille de sécurité corrigée au niveau des pièces justificatives corrigée

## Nouveautés

* Filtre ajouté au niveau des pièces justificatives pour éviter qu'un intervenant ne dépose autre chose que des fichiers PDF, textuels, images ou bureautiques.



# OSE 14.12

## Corrections de bugs

* Le bouton de report des services de l'année précédente vers l'année en cours est de nouveau opérationnel



# OSE 14.11

## Nouveautés

* Suppression du contrôle de la civilité sur le numéro INSEE dans les données personnelles
* Ajout d'un nouveau privilège pour "Règles de validation par type d'intervenant" dans l'administration de OSE (Ticket #32637)

## Corrections de bugs

* Sur l'écran paramétrage d'un centre de coût d'un élément pédagogique (offre de formation), ne proposer que les centres de coûts de la composante d'appartenance de l'ELP. (Ticket #28958)
* L'indicateur 340 était inopérant
* Suite au passage de composer en 2.0 stable, forcer l'installation/update de ose avec la version 1.0 de composer



# OSE 14.10

## Nouveautés

* Report des services de l'année précédente : on peut paramétrer si on veut initialiser à partir du prévisionnel ou bien à partir du réalisé. Cela se configure dans les paramétrages généraux de l'application.
* Demandes de mises en paiement : il est maintenant possible d'associer à une mise en paiement un centre de coûts de la composante d'affectation de l'intervenant plutôt que la composante d'enseignement. Un paramétrage général permet de choisir dans quel mode OSE doit fonctionner.

## Corrections de bugs

* Lors de la modification du référentiel réalisé, l'horodatage (nom et date de modification) n'impacte plus le référentiel prévisionnel.
* Bug de division par 0 sur la formule de calcul de Poitiers
* La vue V_INDICATEUR_361 se crée maintenant correctement



# OSE 14.9

## Nouveautés

* Intégration de la formule de calcul de Poitiers.
* Stockage de la date d'envoi par e-mail du contrat
* Nouvel indicateur 361 permettant de suivre les retours de contrats envoyés par email
* Possibilité de tranférer une fiche de service vers la page de test des formules de calcul
* Possibilité d'exporter et d'importer sous forme de fichier un test de formule de calcul
* Piece justificative : forcer la durée de vie à 1 si la case "Uniquement en cas de changement de RIB" est cochée par l'utilisateur pour éviter les mauvais paramétrages des pièces jointes

## Corrections de bugs

* Dans l'écran contrat du vacataire, l'action "envoyer par mail", n'envoyait pas le contrat par mail si l'intervenant n'avait pas d'email établissement.

## Notes de mise à jour

* Lors de la mise à jour, vous rencontrerez deux erreurs :
  * lors de la modification de l'indicateur 361
  * lors de l'ajout d'une contrainte sur la table NOEUD

Il vous faudra éxécuter `./bin/ose update-bdd` juste après la MAJ pour corriger ces erreurs.


# OSE 14.8

## Corrections de bugs

* Lors de la suppression d'une formation dans l'offre de formation complémentaire, les chemin pédagogiques associés sont également supprimés.
* Les lignes de service avec 0 heures ne s'afficheront désormais plus (sauf si on est en réalisé qu'on a du prévisionnel validé).
* Formule de Brest : s'il y a une décharge, les heures de modif de service sont maintenant retranchées su service dû.
* Les plafonds bloquants fonctionnent de nouveau si on modifie un volume horaire individuel
* Les pièces justificatives sont de nouveau demandées si aucun service n'est saisi

# OSE 14.7

## Nouveautés

* Les modèles de pièces justificatives peuvent être téléchargés en fonction de l'année courante (paramètre :annee à ajouter dans l'URL qui sera remplacé dynamiquement par l'année en cours)

## Corrections de bugs

* Correction sur les agréments restreints qui n'étaient plus demandés par composante (Tickets #30278 et #29825)


# OSE 14.6

## Nouveautés

* Lors de l'envoi d'email aux intervenants via les indicateurs, possiblité d'en demander une copie par email pour avoir un traçabilité. Le mail en copie contiendra en plus la liste des personnes / emails qui ont reçu celui-ci
* L'expéditeur de l'email du contrat est maintenant celui qui a réalisé l'action d'envoi (en lieu et place de nepasrepondre@unicaen.fr) 
* L'objet de l'email du contrat est maintenant personnalisable dans Administration > Paramètres généraux
* Ajout du paramètre :annee pour la personnalisation du corps de l'email du contrat.
* Lors de l'envoi du mail du contrat,  les sauts à ligne manuels du modèle de mail sont remplacés par des <br/> html pour respecter la mise en page.

## Corrections de bugs

* Fiabilisation des demandes de mise en paiement de référentiel (message d'erreur qui appraissait parfois résolu)
* Problème de gestion de cache lors de la création d'un nouveau statut d'intervenant (Ticket #30189)
* Suppression des caractéres spéciaux dans les noms des fichiers pièces jointes et contrats (Ticket #29565)
* Possibilité de réduire le nombre d'heures se service si on a dépassé un plafond bloquant.

# OSE 14.5

## Nouveautés

* Nouvelles formules de calcul :
  * Université Jean Monnet (Saint-Étienne)
  * Université Côté d'azur (Nice)
  * Université Rennes 2
  * INSA de Lyon

## Corrections de bugs

* Corrections coquilles dans l'administration.
* Correction bug sur la duplication d'un statut d'intervenant (ticket #29553)
* Lors de l'import de données, l'application ne plante plus si le connecteur INTERVENANT est désactivé


# OSE 14.4

## Nouveautés

* Nouveaux écrans dans l'administration de OSE : 
   * Edition possible des types de ressources (paie état, ressources propres etc...)
   * Edition des règles de validations (volume horaire / type intervenant)
   * Edition des types d'activites des centres de coûts (pilotage, enseignement, accueil etc..)

* L'export CSV des services affiche maintenant les heures non payées avec chaque motif dans des lignes distinctes, ventilées par type d'intervention (CM/TD/TP)
* Par défaut, les pièces jointes ne sont plus demandées si l'intervenant ne fait que des heures non payables dans son service. Possibilité de les forcer en obligatoire dans l'admin si on le souhaite.   

## Corrections de bugs

* Dans la gestion des types de statut, mise à jour du libellé du statut lors d'un update.
* Les heures non payables n'apparaissent désormais plus dans les contrats. 
* Dans l'export CSV du différentiel services / charges, la totalisation des heures de dépassement n'est plus buggée s'il y a plusieurs intervenants 
* Les numéro INSEE des corses nés avéant 1976 (département 20) sont maintenant correctement pris en compte lors de la validation du dossier

# OSE 14.3

## Corrections de bugs

* Petite marge d'erreur de 0,05 HETD autorisée pour les plafonds afin de tenir compte de certains arrondis
* Possibilité de faire des demandes de mise en paiement de 0,01 HETD
* Les colonnes ne faisant pas partie du Schéma de OSE ne sont plus prise en compte lors des mises à jour.
* Correction problème installation avec composer qui par défault si composer n'est pas disponible sur le serveur, télécharge composer.phar en version 2.0 dev, non compatible pour le moment avec ose.
* L'export CSV des services est pleinement fonctionnel lorsqu'un interveanant n'a que des heures avec motif de non paiement

# OSE 14.2

## Correction de bug

* Petit bug d'affichage d'un message d'erreur corrigé.

# OSE 14.1

## Correction de bug

* Soucis réglés au niveau de la procédure de mise à jour, qui n'exécutait pas certains traitements.

# OSE 14

## Nouveautés

* Améliorations portées au système de gestion des contrats.
  * Le corps de message de l'envoi du ontrat par mail peut maintenant être personnalisé (cf. Paramètres globaux dans le menu Administration).
  * Le mail est maintenant envoyé à l'adresse mail perso indiquée dans le dossier de l'intrevenant, à défaut sur son mail professionnel d'établissement de sa fiche intervenant
  * L'indicateur 360 resence maintenant les contrats validés qui n'ont aucun fichier téléversé.
  * Un nouvel indicateur 370 liste les contrats validés qui ont des fichiers téléversés, mais sans date de retour.
  * Les fichiers téléversés ne peuvent plus être modifiés si une date de retour a été saisie. Pour pouvoir modifier à nouveau, il faut d'abord enlever la date de retour.
  * La règle de franchissement de l'étape contrat peut maintenant être personnalisés : soit l'étape est franchie si le projet de contrat est validé, soit il faut en plus qu'une date de retour ait été saisie. 

* Gestion de la durée de vie des agréments
  * A l'instar des pièces justificatives, la notion de premier recrutement a été remplacée par une durée de vie. Un agrément pourra donc être redemandé tous les 5 ans par exemple.
  * Les règles de gestion des agréments sont maintenant configurables dans l'IHM d'administration des statuts.

* Personnalisation
  * Le message de bienvenue et la page "Contact", auparavant configurables dans le fichier config.local.php, dont maintenant modifiables dans la page Administration / Paramètres généraux.

## Notes de mise à jour

* Attention : lors de la mise à jour de la base de données, le script vous signalera quelques erreurs dues au fait qu'il existe certains liens d'interdépendances entre objets qui changent simultanément que le système ne gère pas parfaitement.
Il vous faudra pour y remédier relancer un ./bin/ose update-bdd juste après la mise à jour. Là, les erreurs disparaitront et votre base devrait être parfaitement à jour.

* N'oubliez pas de configurer les nouveaux paramètres généraux de OSE (Administration/Paramètres généraux)!

* Dans le fichier config.local.php, supprimer les items suivants de la rubrique etablissement :
  * messageBienvenue 
  * contact

# OSE 13.1

## Correction de bugs

* Lors de l'installation de OSE, les taux horaires des heures équivalent TD s'initialisent maintenant bien quelle que soit la configuration du serveur
* Lors de la saisie de service, un élément remonte maintenant même si on filtre par une composante qui n'est pas la composante porteuse de l'élément
* Les numéros d'INSEE Corses sont maintenant gérés correctement, de même que les anciens départements français du Maroc et de Tunisie
* Lors de l'installation de l'application, l'initialisation des données fonctionne à nouveau
* Le cache des données de pièces jointes (TBL_PIECE_JOINTE) pose problème depuis la V13 avec les version d'Oracle > 11. Un palliatif est intégré à cette nouvelle version.

# OSE 13.0

## Nouveautés

* Gestion de la durée de vie des pièces justificatives :
  * La notion de "premier recrutement" disparait au profit de la durée de vie de la pièce
  * Vous pouvez maintenant préciser combien d'années est valable une pièce justificative par statut d'intervenant
  * Une pièce obligatoire mais jamais fournie sera maintenant systématiquement demandée
  * Une pièce ancienne mais toujours valable sera affichée dans la fiche actuelle de l'intervenant : inutile de se positionner dans l'année de fourniture de la pièce
  * Une pièce fournie anciennement mais toujours valide pourra être archivée si l'on souhaite en fournir une nouvelle version
  
* Vous pouvez maintenant envoyer par mail le contrat de travail généré (privilège "Envoyer le contrat par mail") à donner aux rôles ad hoc pour donner accès à la fonctionnalité)
 
* Vous pouvez maintenant éditer les centres de coûts et les modulateurs directement sur un élément pédagogique particulier.
 
* Reconduction des centres de coûts et des modulateurs de l'offre de formation
  * une nouvelle interface d'administration vous permet de reporter les modulateurs d'une formation qui ont été positionnés de l'année en cours à l'année suivante
  * une nouvelle interface d'administration vous permet de reporter les centres de coûts d'une formation qui ont été positionnés de l'année en cours à l'année suivante

## Correction de bugs

* La page de saisie des services était accessible en écrivant la bonne URL, même si le workflow ne permettait pas d'arriver à cette étape.

## Notes de mise à jour

L'ensemble des tableaux de bord doivent être recalculés.
Pensez à recalculer les tableaux de bord au moyen de la commande ./bin/ose calcul-tableaux-bord

En ce qui concerne les pièces justificatives, les données demandées uniquement s'il s'agit d'un premier recrutement ont été migrées en données valables 99 ans. A vous de revoir ensuite cette durée de vie si nécessaire.

# OSE 12.2

## Correction de bug

* Depuis la V12, la formule de calcul ne distinguait plus si le service était effectué dans la composante d'affectation de l'intervenant ou dans une autre composante. C'est rétabli. 

# OSE 12.1

## Correction de bugs

* L'interface d'administration des centres de coûts est de nouveau opérationnelle
* L'interface d'administration des domaines fonctionnels est de nouveau opérationnelle


# OSE 12

## Nouveautés

* Possibilité de bloquer l'usage de certains rôles si l'on se trouve hors du réseau de l'établissement
* Amélioration des performances (x20 environ) pour le calcul en masse des workflows
* Correction d'un bug portant sur le calcul des feuilles de routes qui ne prenait pas en compte certaines règles dans certains cas
* Modifications sur l'infrastructure des formules de calcul : 
  * le code de la structure est fourni en natif et n'est plus un paramètre supplémentaire.
  * les formules des établissements concernés ont été modifiées pour tenir compte de cette nouveauté.
  * les heures de décharge ne sont plus gérées en tant que telles, mais impactent le paramètre "Dépassement de service dû sans HC"
  * il est maintenant possible de personnaliser les structures gérées dans l'interface de test.
* Possibilité de saisir le taux de charges patronales directement dans l'IHM d'administration des statuts des intervenants.
* Lorsqu'on utilise la commande ./bin/ose creer-utilisateur et que l'on demande à créer un intervenant, la feuille de route s'initialisera dans la foulée.
* Possibilité de définir des paramètres (version, etc.) avant les mises à jour de l'application afin de pouvoir automatiser à 100% les processus d'installation et de mise à jour.
* Possibilité de mettre à jour OSE vers une branche et plus uniquement vers un TAG de version (utile pour les développeurs ou en test)
* Les pièces justificatives configurées comme facultatives ne sont maintenant plus considérées comme obligatoires

## Correction de bugs

* Rétablissement du fonctionnement du formulaire d'édition des états de sortie
* La purge de l'indicateur différentiel des données personnel fonctionne de nouveau
* Correction d'un bug au niveau des feuilles de route qui rendait accessible certaines étapes à tort 

## Notes de mise à jour

Le paramètre "global" => "inEtablissement" devra être renseigné pour que vous puissiez créer votre propre règle permettant de savoir
si l'application est utilisée depuis l'établissement ou non. Le fichier config.local.php.default comporte un exemple d'usage de ce paramètre, pour vous aider à l'exploiter.

Si vous mettez à jour à partir d'une version antérieure à la 11, il vous faut également prendre en compte les notes de mise à jour des versions intermédiaires.

ATTENTION également : Si vous migrez d'une édition zf2 vers cette nouvelle version (<9 ou *-zf2, il vous faudra également supprimer manuellement le répertoire /vendor de OSE AVANT de démarrer la mise à jour, sans quoi Composer, le gestionnaire de dépendances de PHP, ne parviendra pas à tout actualiser.

# OSE 11.2

## Correction de bug

* La saisie en mode calendaire rafraichit de nouveau la liste des services en cas d'ajout d'heures. 

# OSE 11.1

## Correction de bug

* Le formulaire d'édition des enveloppes budgétaires est de nouveau fonctionnel.

# OSE 11

## Correction de bugs

* L'interface d'administration des motifs de modification de service est de nouveau pleinement fonctionelle.
* La suppression d'un élément pédagogique de l'ODF complémentaire refonctionne.
* Correction d'un bug dans le cache : si un fichier n'était pas déjà en cache il y avait plantage.
* La saisie de service calendaire est de nouveau possible (un bug empêchait de saisir des dates).

## Nouveautés

* Formule de calcul de Lyon2
* L'administrateur se voit attibuer systématiquement l'accès à toutes les nouvelles fonctionnalités
* L'interface d'administration des structures est maintenant opérationelle

## Notes de mise à jour

Les versions 7.4 de PHP sont maintenant nécessaires.

# OSE 10-zf2 et 10-zf3

## Nouveautés

* Possibilité d'utiliser un service Unoconv présent sur un serveur dédié autre que celui de OSE
* L'export des services au format CSV est maintenant personnalisable
* En ligne de commande, il est désormais possible d'indiquer dans quelle année universitaire et sous quel statut un intervenant sera créé, de même qu'un code intervenant

## Correction de bugs

* Pour un rôle de périmètre établissement avec pour possibilité de changer de structure, le changement fonctionne à nouveau (pour l'édition ZF3 uniquement)
* Dans certains cas, la génération d'états de sortie en PDF produisait des fichiers corrompus.
* Dans l'export CSV des services, le total était celui des heures compl. uniquement. il est maintenant égal à la somme de toutes les heures (service + compl.)
* Contrats/Avenants : problème de numéros d'avenants parfois incohérents corrigé
* Contrats/Avenants : Bug dans les totaux d'heures qui changent dans les documents suite à la génération d'avenants corrigé
* Correction d'un problème d'arrondi au niveau des calcul de totaux sur les formules de calcul
* Correction d'un problème d'arrondi au niveau des taux de répartition FI/FA/FC dont la somme n'était pas toujours égale sur les éléments pédagogiques
* Le référentiel s'affichait sur une seule ligne par intervenant, même en cas de fonctions multiples.

## Notes de mise à jour

Les deux éditions sont isofonctionnelles.
L'édition 10-zf3 est maintenant celle recommandée pour la production. Elle requiert PHP 7.3.
L'édition 10-zf2 passe maintenant en statut "obsolète".

Ajout de nouveaux paramètres de configuration pour pouvoir externaliser l'usage du service Unoconv.
A ajouter dans votre fichier config.local.php et à personnaliser le cas échéant :
```php
    /* Génération d'états de sortie avec Unoconv */
    'etats-sortie'       => [
        /* Serveur où se situe le service Unoconv */
        'host'    => '127.0.0.1', // par défaut sur la même machine que OSE

        /* Répertoire de travail utilisé à la fois par OSE et par le service Unoconv */
        'tmp-dir' => getcwd() . '/cache/', // par défaut dans le répertoire cache de OSE
    ],
```

# OSE 9.0.2-zf2 et 9.0.2-zf3

## Correction de bugs

* Un bug modifiant les paramètres globaux qui avaient été configurés a été résolu
* La duplication de statuts refonctionne
* Lors de la création d'un nouveau statut, l'octroi de privilèges ne plante plus
* La suppression de rôle fonctionne de nouveau correctement 

## Nouveautés

* Intégration de vues métérialisées pour extraction BO

# OSE 9.0.1-zf2 et 9.0.1-zf3

## Correction de bugs

* Un bug empêchant d'avoir accès aux indicateurs si on est connecté en tant que composante a été résolu

# OSE 9.0-zf2 et 9.0-zf3

## Correction de bugs

* On peut maintenant s'abonner à un indicateur même avec un rôle de périmètre établissement réduit à une composante.
* Les services réalisés peuvent maintenant être initialisés à partir de volumers horaires auto-validés en plus e ceux qui ont été validés manuellement.
* Le référentiel peut être saisi même pour un intervenant d'une autre composante si l'enseignement est dans la composante du gestionnaire
* L'utilisateur OSE était mentionné partout comme modificateur au lieu de l'utilisateur courant. C'est rétabli.
* La création d'un nouveau projet de contrat ne calculait pas les heures HETD avec la formule. C'est maintenant automatique. 
* Depuis quelques temps, les plafonds bloquants fonctionnaient comme des plafonds informatifs. C'est corrigé.
* La séquence FORMULE_RESULTAT_SERVIC_ID_SEQ se met maintenant correctement à jour (avant, cela entrainait de nombreux bugs, car la formule des HC ne se calculait plus après une mise à jour)
* L'indicateur 120 renvoyait à tort le même résultat que le 110.
* Lors de la modification d'un privilège, le cache se met à jour automatiquement désormais

## Nouveautés

* Deux éditions de OSE sont disponibles :
    * 9.0-zf2 basée sur le Zend Framework 2 et qui requiert PHP7.0 (édition "historique")
    * 9.0-zf3 basée sur le Zend Framework 3 et qui requiert PHP7.3
* Ajout d'un nouveau contrôle lors des demandes de mise en paiement : il n'est plus possible de payer plus d'heures que d'HETD même si des HETD ont déjà été payées à tort
* La vue matérialisée MV_EXT_SERVVICE a été créée pour être exploitée pour alimenter des outils de pilotage (BO, etc).

## Notes de mise à jour

Si vous mettez à jour à partir des versions :

* 8.2.* : pas de soucis, un ./bin/ose update suffit.
* 8.1.* : lancez ./bin/ose update normalement. Vous rencontrerez un message d'erreur dû à un bug lié à cette version (PHP Fatal error:  Uncaught Error: Call to undefined method OseAdmin::getOseAppliId()).
Pour pallier à cela, il vous faudra exécuter ensuite la commande ./bin/ose update-bdd pour que tout rentre dans l'ordre.
* < 8.1 : mettez d'abord à jour en version 8.1.4, puis mettez à jour en 9.0.

# OSE 8.2.2

## Correction de bugs

* Les futurs vacataires ne pouvaient plus se connecter à l'application (le choix de l'année en cours ne leur était pas proposé si leur fiche n'existait pas)

# OSE 8.2.1

## Correction de bugs

* Depuis la 8.2, les RIB SEPA n'étaient jamais considérés comme validés.
* Toujours depuis la 8.2, les ID des types de volumes horaires et des états de volumes horaires pouvaient être modifiés à tort.
* Warning PHP (sans conséquence) se prosuisant lors de la mise à jour corrigé. 

## Notes de mise à jour

Si vous mettez à jour depuis la v8.2, un Warning apparaitra au début. Il est sans conséquence. Merci de ne pas en tenir compte.

# OSE 8.2

## Correction de bugs

* Lorsqu'on supprime une ligne de service avec des volumes horaires validés en mode calendaire, 
les nouveaux volumes horaires négatifs générés portent maintenant les mêmes dates de début et de fin que les originaux.
* Des volumes horaires référentiels auto-validés pouvaient être modifiés via l'IHM dans certaines circonstances : c'est corrigé.
* La modification d'heures de service référentiel fonctionne bien même avec des heures auto-validées et calendarisées. 
* Le total HETD HC affiche réellmenet les HC et non le total des heures dans services/résumé
* Lorsqu'un pays a été importé plusieurs fois, les items historisés remontaient parfois dans des recherches par libelle. Seuls les pays non historisés remontent maintenant.

## Nouveautés

* Le logo Unicaen n'est plus fourni par défaut dans l'interface de OSE en pied de page
* Le lien "Informatique et libertés" est remplacé par un nouveau lien "Vie privée"
* Interface de gestion des motifs de non paiement
* Possibilité de créer un nouvel utilisateur dans OSE déconnecté du LDAP, ainsi qu'une fiche intervenant.
* [Documentation de la ligne de commande OSE](doc/ligne-de-commande.md)
* Pour une fiche intervenant, les champs suivants n'ont plus besoin d'être systématiquement fournis :
    * Civilité
    * Composante d'affectation
    * Pays de naissance
    * Nom patronymique

* Les coordonnées bancaires peuvent être saisies même si elles sont hors zone SEPA (une case à cocher limite le contrôle)
* L'export PDF des services est maintenant personnalisable.
* Le pays "France" est détecté automatiquement. Il n'a donc plus besoin d'être identifié via administration/paramètres généraux.
* Amélioration de performances pour l'affichage des données personnelles
* Nouveau plafond à définir par statut portant sur les HETD complémentaires en FI hors EAD (Enseignement à distance)
* Indicateurs 580 et 590 liés à ce nouveau plafond (580=prévisionnel, 590=réalisé)

## Notes de mise à jour

Si vous faites la mise à jour depuis une version ANTÉRIEURE à la 8.1 :

* Il est obligatoire de migrer OSE d'abord en version 8.1 (bien 8.1, pas 8.0.1 ou autres), puis ensuite de faire la migration 8.1 => 8.2.

Pour tout le monde :

* CSS personnalisée : Si vous avez remplacé le logo Unicaen par votre propre logo en pied de page, 
la classe CSS pour cela est maintenant "lien-univ" au lien de "ucbn".

* Dans le fichier de configuration local.config.php, remplacer l'item "informatiqueEtLibertes" par "viePrivee"


# OSE 8.1.4

## Correction de bug

* Bug de la version 8.1.3 corrigé dans l'infrastructure de gestion des formules de calcul : 
certains volumes horaires étaient comptés deux fois,entrainant plus d'heures HETD que prévu.

# OSE 8.1.3

## Corrections de bugs

* Les informations complémentaires de l'utilisateur (qui relevaient d'une liste spécifique à l'Université de Caen et qui pouvait amener un plantage de l'application) 
ne sont plus affichées lorsqu'on clique sur l'utilisateur en haut à droite.
* Dans la fiche de service, les codes des éléments et des étapes s'affichent de nouveau (ils n'étaient plus visibles)
* Lorsqu'on crée un nouveau rôle et qu'on lui ajoute des privilèges, l'application ne plante plus (problème de cache de données réglé).
* Dans l'export Winpaye, le numéro INSEE est maintenant correctement formatté (il supprime les espaces en trop, et ajoute des zéros devant les clés si nécessaire)
* Dans le dossier, les Numéros INSEE avec pour département 75 sont comptés valides par rapport au département de naissance si l'intervenant est né en actuelle région parisienne avant 1968.

## Nouveautés
* Nouveau script de test d'accès à la BDD depuis le script de mise à jour (./bin/ose test-bdd)
* Formules de calcul : nouveau système de récupération des paramètres spécifiques directement implanté dans les formules. 
Les vues V_FORMULE_LOCAL_I_PARAMS et V_FORMULE_LOCAL_VH_PARAMS ne sont plus nécessaires.
* Renforcement du script de mise à jour (pour les futures mises à jour) : détection de l'accès à la BDD OK ou non avant de démarrer la procédure et avertissement sans blocage si le cache ne peut pas se nettoyer.

# OSE 8.1.2

## Corrections de bugs

* Depuis l'offre de formation :
  * lorsqu'un élément a plusieurs centres de coûts associés, les historiques ne sont plus pris en compte
  * les niveaux ne sont plus affichés si aucune étape ne leur correspond 
* L'email professionnelle, qui ne pouvait pas être saisie, n'empêchera plus la validation des données personnelles si elle est vide.
* Formules de calcul :
  * Correction de problèmes de performance. OSE_FORMULE.CALCULER_TOUT doit avoir une vitesse de calcul de 90 intervenants/seconde environ.
  * Modifications sur la formule de Montpellier (réalisées par Montpellier)
  * Correction d'un bug entrainant des erreurs de calcul si on utilise OSE_FORMULE.CALCULER_TOUT.
* Lors de la saisie de service, "null" n'apparait plus si le semestre n'était pas renseigné (c'était le cas dans certaines situations)

## Nouveautés

* Mise en place d'un nouveau système de mises à jour. Ce dispositif permet maintenant de mettre à jour automatiquement la base de données.
Il n'y a donc plus de scripts SQL à exécuter en plus.

Attention : le système calcule tout seul le différentiel entre l'état actuel de votre base de données et l'état attendu
par la nouvelle version.
Il se focalise sur les structures de données fournies "en standard" dans l'application, qui doivent être les mêmes pour tout le monde. 
Il ignore donc les objets qui ont été créés par vos soins. 
Ces derniers ne seront donc pas modifiés ou supprimés. 
De même, les connecteurs ne seront pas impactés. 

* Lien LDAP : possibilité de définir dans le fichier de configuration config.local.php le paramètre loginObjectClass qui permet de rechercher des utilisateurs de classe autre que posixAccount.
cf. Fichier config.local.php.default.


# OSE 8.1.1

## Corrections de bugs

* La prise en compte des paramètres supplémentaires pour la formule de calcul fonctionne désormais pleinement.
* Dans l'onglet Suppression de la fiche intervenant, l'arborescence s'affiche correctement. La suppression partielle ou complète d'un intervenant fonctionne donc de nouveau.
* Un bug se produisait dans l'arborescence de suppression de fiche intervenant : s'il n'y avait qu'un seul service ou qu'un seul référentiel alors rien n'était affiché. C'est corrigé. 

## Nouveautés

* Possibilité de récupérer des attributs multivalués pour faire la correspondance d'identifiant entre le LDAP et le code utilisateur indiqué dans la fiche Intervenant
* Possibilité de modifier les données liées aux charges d'enseignement sur des éléments pédagogiques qui ne sont plus synchronisés avec Apogée.

## Notes de mise à jour

* Attention : Le répertoire public/modeles de OSE était propre à l'Université de Caen et il a été supprimé.
Il servait à fournir des modèles de pièces justificatives à remplir par les intervenants.
Si vous voulez fournir vos propres modèles de pièces justificatives, vous devrez les placer sur un serveur Web (qui peut être celui de OSE, mais ailleurs que dans le code source) 
pour avoir une URL que vous collerez dans l'interface de gestion des pièces justificatives en éditant un type de pièce jointe, champ "modèle".


# OSE 8.1

## Corrections de bugs

* Les avenants comportaient par défaut un champ "modifieComplete" qui n'était pas fourni par la vue.
* Lors de la saisie d'un nouveau service d'enseignement, si la formation a déjà été sélectionnée :
    * Les éléments sont triés correctement
    * Les éléments sont affichés avec le semestre

* Avenants au contrat travail corrigé : le projet et l'avenant validé ne présentaient pas les mêmes nombres d'heures 
dans le détail des services.
* Contrats : le problème de double espace situé entre "titre" et "qualité" a été corrigé. 
* Procédures d'installation et de mise à jour intégrées directement au projet dans Gitlab.
* Certains dossiers intervenants pouvaient être validés plusieurs fois. 
Un mécanisme contrôle désormais que le dossier n'est pas déjà validé avant de valider à nouveau.
* Le contrôle de cohérence des données personnelles prend maintenant en compte le cas des français nés dans un ex-département français d'Algérie.  
* Les mails de notification aux intervenants peuvent être envoyés même si certains d'entre eux n'ont pas de mail. Ces derniers seront listés.
* Les indexes de clés étrangères n'étaient pas créés par les précédents scripts d'installation. C'est désormais le cas et le script de MAJ 
inclue les indexes manquants pour création.

## Nouveautés

* Il est désormais possible de customiser et/ou de traduire de petites parties de l'application. 
Attention toutefois : le travail de mise en place du dispositif n'en est qu'au tout début.
* Possibilité de choisir sa formule dans les paramétrages généraux via une liste déroulante
* Formule de calcul de l'université de Montpellier
* Formule de calcul de l'université du Havre
* Formule de calcul de l'université de Nanterre
* Formule de calcul de l'université de Bretagne Occidentale
* Formule de calcul de l'Ensicaen
* Interface de test de la formule de calcul directmement intégrée dans OSE
* Interface d'administration des motifs de modification de service dû
* Interface d'administration des domaines fonctionnels
* Installation possible via Docker d'une version de développement ou de test
* Changements d'organisation des fichiers du projet
    * Les fichiers liés à la base de données sont maintenant placés dans /bdd. Un sous-répertoire update recense tous les
    fichiers de mises à jour de base de données liés aux nouvelles versions
    * Le fichier [bdd/install.sql](bdd/install.sql) est à injecter dans un schéma de base de données vide pour toute nouvelle installation de OSE.
    * Le dossier data/cache s'appelle maintenant directement /cache
    * Les connecteurs sont maintenant placés dans un dossier /connecteurs
* Les procédures d'installation et de mise à jour sont disposibles également dans le Gitlab 
(Cf. [Procédure d'installation](INSTALL.md) et [Procédure de mise à jour](UPDATE.md))
* Nouveau privilège permettant de modifier des services après côture, même en cas de mise en paiement (à réserver à des gestionnaires avertis des conséquences sur les paiements)
* Dans les paramètres généraux, il n'est plus nécessaire de renseigner qui est le DRH de l'établissement
* La recherche d'intervenants saisis directement dans OSE fonctionne maintenant même sans avoir de vue source Intervenant
* Les fonctions référentielles peuvent être regroupées par types, avec des plafonds et indicateurs associés
* Un plafond par composante a été ajouté pour le référentiel.
* Pour suivre l'évolution de la règlementation des heures supplémentaires, l'export Winpaye a été modifié : 
le code retenue est passé à 2251 pour les vacataires. Les permanents restent à 0204.

## Notes de mise à jour

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
[bdd/update/08.1.sql](bdd/update/08.1.sql)
Les mises à jour [`bdd/update/08.0.1.sql`](bdd/update/08.0.1.sql) et [`bdd/update/08.0.3.sql`](bdd/update/08.0.3.sql) sont inclues dans le précédent fichier.
Inutile, donc, de les exécuter si vous mettez à jour depuis la 8.0.
* Attention : le dossier de cache est déplacé de /data/cache vers /cache. Attention à bien donner à l'utilisateur Apache les droits d'écriture dans le dossier de cache. 
Vous pourrez supprimer manuellement l'ancien dossier /data/cache qui n'a plus d'utilité.
* Attention : au niveau de votre configuration Apache, APPLICATION_ENV peut prendre désormais trois valeurs possibles :
dev,test ou prod. Les anciennes valeurs development et production doivent donc être respectivement remplacées par dev et prod.

* Si vous avez déjà installé une des versions 8.1beta, je vous invite à exécuter le script requête par requête. Les packages, les vues et les triggers pourront être
mis à jour sans aucun soucis. Par contre, veillez bien à ne pas insérer deux fois les mêmes données, ou bien à ne pas tenter de créer deux fois les mêmes colonnes ou les mêmes clés étrangères.
Le mieux est de dupliquer votre instance de production en test si vous en avez une, puis ensuite d'appliquer la mise à jour avec la dernière version disponible.

* La vue export Winpaye a légèrement évolué : le code retenue n'est désormais plus le même pour les vacataires et les permanents (cf. nouveautés ci-dessus). 
Cela devrait n'avoir aucune incidence sur vos paramétrages d'états de sortie Winpaye.

* Pour les universités de Bretagne Occidentale et de Nanterre : votre formule de calcul nécessite des vues spécifiques (V_FORMULE_LOCAL_*) 
qui vous ont été fournies, à implanter dans votre base de données. Veillez à bien implanter ces vues également sur votre serveur de production.

# OSE 8.0.3

## Corrections de bugs

* L'export CSV global des paiements refonctionne
* Le tri des demandes de mises en paiements et mises en paiement se fait de nouveau par intervenant
* Dans Gestion/Paiement/Mises en paiement, les mises en paiement fonctionnent à nouveau
* La date et l'heure situées en bas de page de l'état de paiement sont maintenant bien à jour

# OSE 8.0.2

## Corrections de bugs

* Dans l'onglet Services, le filtre par intervenant fonctionne à nouveau
* Dans la page des mises en paiement (menu gestion), si on a un rôle de périmètre composante, le bug n'affichant plus la structure est résolu


# OSE 8.0.1

## Corrections de bugs

* La mise en paiement est de nouveau accessible
* L'export CSV des services est de nouveau accessible
* L'état de sortie Winpaie comportait une colonne utilisée pour des tests qui a été corrigée

## Notes de mise à jour

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
`data/Mises à jour/08.0.1.sql`


# OSE 8.0

## Corrections de bugs

* Lors de la saisie de service, si on sélectionne une étape, tous les éléments associés remontent dans le formolaire et non les 100 premiers comme avant.
* La validation des services tentait de valider des heures déjà validées dans certains cas.
* Les données personnelles ne pouvaient pas être enregistrées si les intervenants avaient plusieurs adresses.

## Nouveautés

* Possibilité de récupérer les libellés de labos dans les affectations de recherche. 
Attention : pour en bénéficier, il faudra mettre à jour votre connecteur affectation_recherche
en vous inspirant de l'exemple fourni dans le code source de OSE (data/Déploiement/Connecteurs/Connecteurs OSE.sql). 

* Interface de paramétrage des centres de coûts dans le menu Administration.

* Paramétrages généraux : la composante représentant l'université (de niveau 1 donc) peut maintenant se paramétrer directement 
dans l'interface d'administration.

* Refonte de l'infrastructure de gestion de la formule de calcul. Les performances ont été très sensiblement améliorées (x100).

* L'installation de OSE via Gitlab passe désormais par HTTPS au lieu de SSH. La clé de déploiement n'est plus nécessaire.

* Nouveau système d'états de sorties personnalisables. Les documents concernés sont :
  * L'export Winpaie
  * Les états de paiement et états de demandes de mise en paiement

* Amélioration de l'interface d'administration des types d'intervention : possibilité de définir des taux spécifiques par statut d'intervenant (pour le TP hors service par exemple)

## Notes de mise à jour

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
`data/Mises à jour/08.0.sql`

* Mettez à jour vos paramètres généraux de configuration (Menu Administration/Paramétrages/Paramètres généraux).

# OSE 7.0.6

## Corrections de bugs

* Faille de sécurité qui permettait à un intervenant de visualiser les données d'autres intervenants en changeant le code dans l'URL corrigée.

# OSE 7.0.5

## Corrections de bugs

* Correction au niveau du plafond "Heures max. de référentiel par intervenant selon son statut" : les heures de FC majorées ne sont plus prises en compte.
* La génération de contrat n'éditait plus qu'un seul exemplaire (depuis la v7.0.4). C'est réparé.
* De nombreux libellés "StructureService" ont été remplacés par "Structure", "Service" ayant été ajouté par erreur.
* Le mode de saisie de service Calendaire ne fonctionnait pas. Il a été réparé.
* Dans la fiche intervenant, d'anciennes adresses s'affichaient parfois à la place des actuelles.
* La saisie d'intervenants depuis le menu "Services" fonctionne de nouveau pleinement, y compris avec des intervenants dont les codes comportent des lettres.
* Lors de la saisie de service référentiel, le système de sélection des composantes fonctionne de nouveau.
* Sur la page  de validation du service réalisé d'un intervenant, le rappel des volumes horaires prévus et validés s'affiche correctement

# OSE 7.0.4

## Nouveautés

* Possibilité d'éditer 1, 2 ou 3 exemplaires d'un contrat à partir d'un modèle
* Unoconv est maintenant utilisé sans besoin de faire appel à SUDO.

## Corrections de bugs

* Les intervenants peuvent maintenant avoir un code composé de lettres, plus seulement de chiffres
* Le report des heures de l'année précédente vers l'année en cours actualise maintenant automatiquement la feuille de route de l'intervenant
* Les plafonds bloquants ne peuvent plus être dépassés lors du report  du service prévisionnel de l'année dernière vers l'année actuelle
* Les plafonds bloquants ne peuvent plus être dépassés lors de l'initialisation du réalisé à partir du prévisionnel
* Lors de l'initialisation du service prévisionnel à partir de celui de l'année dernière, les heures portant sur des services précédemment historisés s'importent aussi dorénavant.
* Lors de la génération de contrat, le bug "Uncaught TypeError: Return value of getVariables must be of the type array, null returned"
 qui se produisait si aucune variable n'était positionnée dans les en-têtes ou pieds de page a été résolu.
* L'ergonomie du formulaire d'édition des modèles de contrats a été revue afin de mieux vous guider
* Faille de sécurité corrigée : le sudo n'est plus obligatoire pour utiliser unoconv

# OSE 7.0.3

## Corrections de bugs

* Le modèle de contrat est désormais téléchargeable depuis l'interface d'administration.
* Un bug affectant l'affichage de la page "Calcul HETD" dans certains cas est résolu.

# OSE 7.0.2

## Corrections de bugs

* En mode calendaire, le formulaire de saisie de service réalisé ne s'affichait plus.

# OSE 7.0.1

## Corrections de bugs

* La génération des contrats ne fonctionnait pas en mode production.
* Idem pour télécharger des modèles de contrats.

## Notes de mise à jour

Pas de BDD à mettre à jour, juste le code de l'application en lancant la commande /bin/ose update.

# OSE 7.0

## Corrections de bugs

* La page "Contact" est maintenant personnalisable.

* Le bug empêchant de saisir de nouveaux services depuis la page "Services" est résolu.

* Les filtres portant sur l'export des services (CSV et PDF) refonctionnent.

* L'affichage des détails d'une composante (lorsqu'on clique sur son nom) a été réparé.

* Administration des fonctions référentielles :
Les composantes supprimées n'apparaissent maintenant plus dans la liste. Apparaissent en plus les composantes sans enseignements (pour lesquelles il peut y avoir du référentiel).

* Page d'accès aux contrats qui affichait une erreur si des services étaient validés dans une composante supplémentaire sans que l'agrément du conseil restreint ne soit saisi.

* Dans certains cas, le passage des heures du prévisionnel au réalisé ne fonctionnait pas.

* Les utilisateurs LDAP qui n'ont pas de SupannEmpId peuvent maintenant se connecter à OSE sans soucis

* Lorsqu'un clique sur une composante dans le service référentiel, l'affichage du détail ne plante plus.

## Nouveautés

* Synchronisation possible de services et des volumes horaires associés (en vue d'import depuis ADE)

* Possibilité de définir des volumes horaires enseignement et référentiel comme auto-validés.

* Possibilité de déterminer si les heures de service pour un type de formation donné peuvent être comptées dans le service statutaire ou non. 

* Possibilité de déterminer si les heures pour une fonction référentielle donnée peuvent être comptées dans le service statutaire ou non.

* Possibilité de basculer OSE en mode calendaire ou en mode semestriel (par défaut) selon qu'on soit en prévisionnel ou en réalisé.

* Mise en place d'une interface de saisie d'heures en mode calendaire

* Possibilité de personnaliser le modèle de contrat de travail et d'avenant.
    * Un modèle générique est à votre disposition dans le dossier data de l'application (format OpenDocument Texte à adapter avec l'application LibreOffice).
    * Les modèles peuvent être spécifiques à une composante donnée ou bien à un statut donné (ou les deux)
    * Un système de variables permet de réaliser un publipostage à partir des données de contrat pour les injecter dans le document
    * Vous pouvez personnaliser les requêtes afin d'injecter dans le modèle les données de votre choix
    * Vous pouvez créer de nouvelles variables comme bon vous semble
    * Une interface d'administration vous permettra de configurer vos modèles

* il est désormais possible, via la gestion des privilèges, d'interdire à des statuts ou des rôles de générer de nouveaux contrats et/ou avenants.
    
* Le pays "France" peut maintenant être sélectionné dans la configuration globale de l'application

* Formulaire de saisie de services amélioré :
    * Le choix de l'intervenant n'apparait plus lorsque  l'on se trouve déjà dans la fiche de l'intervenant
    * La saisie des volumes horaires n'est affichée que lorsque c'est nécessaire (plus de liste de tous les types d'intervention affichés par défaut).
    * Il est désormais possible de limiter les types d'intervention disponibles pour saisir des services à l'extérieur
     (paramétrage possible depuis Administration/Types d'intervention)

* Dans le module Charges, il est désormais possible de modifier/créer des scénarios de niveau établissement

* Meilleures possibilités de personnalisation :
    * page contacts, 
    * adresse mail d'envoi, 
    * icône de l'application, 
    * URL des documentations permanents et vacataires dans les paramètres généraux.
    * possibilité de préciser des informations lorsqu'un recherche une personne pour affectation à OSE

## Notes de mise à jour

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
`data/Mises à jour/07.0.sql`

* Dans les paramètres de configuration (Menu Administration/Paramètres généraux), 
veuillez sélectionner "FRANCE" dans le bloc "Divers" (en bas à gauche de la page). 
**Attention** : si ce n'est pas fait, la page "données personnelles" des intervenants ne pourra pas s'afficher.

* De nouveaux paramètres de configuration ont été ajoutés dans le fichier de configuration global de l'application
`/config.local.php`. A vous de les ajouter manuellement à votre fichier de confguration existant.
Au besoin, le modèle est le fichier `/config.local.php.default`.

Les paramètres à ajouter puis personnaliser sont :

| Rubrique        | Paramètre       | Description                         |
| --------------- | --------------- | ----------------------------------- |
| etablissement | contact | Personnalisation du contenu de votre page "contact" (code HTML) |
| etablissement | icones | Personnaliser l'icône de l'application |
| mail | from | préciser l'adresse d'expéditeur des mails de OSE |
| ldap | utilisateurExtraMasque | Masque de données à afficher lorsqu'on recherche une personne en vue de lui créer une affectation | 
| ldap | utilisateurExtraAttributes | Attributs LDAP à fournir pour le masque ci-dessus |

* Pour la génération des contrats de travail, veillez bien à installer Unoconv sur votre serveur.
Pour plus d'informations, merci de vous rendre à la page "Procédure d'installation" où tout y est expliqué.

* De nouveaux privilèges liés aux contrats de travail ont été créés. Il vous revient de les attribuer aux rôles et statuts de votre souhait.
Sans cela, les fonctionnalités associées ne seront pas accessibles.


# OSE 6.3.2

## Corrections de bugs

* Mise à jour des tableaux de bord lancée depuis le CRON plus fiable : si un tableau de bord plante, 
les autres sont tout de même mis à jour.

* La personnalisation des liens informatique et libertés et mentions légales refonctionne

* Correction définitive du bug de saisie du service référentiel

* Accès données personnelles : correction d'un bug portant sur certains vacataires affichant une page d'erreur.

* Accès données personnelles : le dossier était accessible même si l'intervenant concerné ne devait pas avoir de dossier.

* Données personnelles : les statuts supprimés étaient toujours disponibles au choix.

* Clôture des services réalisés : les tableaux de bord se mettent à jour automatiquement, ce qui n'était pas le cas avant.

* Lors de la déconnexion, lorsqu'on est cassifié, l'application met complètement fin à la session de 
l'utilisateur, ce qui n'était pas le cas avant où on pouvais se reconnecter sans avoir à se ré-authentifier.

* Correction de bug faisant planter la saisie d'un nouveau service depuis la page "Enseignements".

## Nouveautés

* Pièces justificatives : vous avez la possibilité de personnaliser les documents types à télécharger, y compris au moyen d'URLs.

## Notes de mise à jour

* PHP 7.0.x est obligatoire. Les versions supérieures de PHP ne sont pas supportées pour le moment.
* Une DeployKey générique est intégrée dans le script d'installation de OSE. Il n'est maintenant plus nécessaire de 
déployer une clé nominative sur un serveur.

# OSE 6.3.1

## Corrections de bugs

* Suppression du bug empêchant de saisir des services référentiels si la structure "UNIV" n'existe pas.

* Correction de bug empêchant la modification d'heures de service déjà validés dans certains cas.

* Affichage d'un message d'erreur explicite si l'établissement n'est pas renseigné dans les paramètres.

* La notification gênante est supprimée lors du dépôt de pièces justificatives

## Notes de mise à jour

* Si vous mettez à jour à partir de la version 6.3, pas de changement en base de données.
Toutefois, le dépôt de OSE a changé. 
Il faut donc mettre à jour votre configuration via ces trois étapes :
    1. Dans le dossier de OSE, exécutez en ligne de commande :
  `git remote set-url origin git@git.unicaen.fr:open-source/OSE.git`
    2. Puis `git checkout tags/6.3.1`
    3. Suivez ensuite la procédure de [mise à jour](https://ose.unicaen.fr/deploiement/mise-a-jour.html) normale.

* Si vous n'avez pas installé la version 6.3, alors merci d'installer la 6.3.1 avec la procédure de mise à jour 
indiquée pour la 6.3 (ci-dessous).
N'oubliez pas d'exécuter les requêtes SQL de mise à jour (`data/Mises à jour/06.3.sql`)!


# OSE 6.3 

## Corrections de bugs

* Suppression de la vue `V_TYPE_INTERVENTION_REGLE_EP` qui ne compilait pas.

* Ajout de la dépendance à BCMath (extension PHP à installer).

* Ajout de la dépendance à GD (extension PHP à installer).

* La validation du numéro INSEE dans les données personnelles était incorrecte dans certains cas.

* Recherche d'intervenants inopérante (se produisant uniquement avec PHP 7.1).

* Connecteurs : les disciplines remontent aussi pour les intervenants ayant une fin d'affectation en cours d'année.

* L'affichage de l'interface d'administration des années ne fonctionnait plus.

* à l'installation : toutes les tables étaient considérées par défaut comme synchronisées, ce qui n'était pas le cas.

* Dans l'export CSV des services, le libellé de formation n'apparaissait plus.

## Nouveautés

* Personnalisation : vous pouvez maintenant adapter entièrement l'interface de OSE 
à votre établissement (finies les références explicites à l'Université de Caen dans l'interface)
L'apparence de l'application peut également être personnalisée en fournissant une URL qui
pointe vers une feuille de style CSS propre à votre établissement.

* Nouvel outil en ligne de comande permettant de piloter OSE ( `<dossier OSE>/bin/ose help` pour la liste des possibilités)

* Nouvelle procédure d'installation (https://ose.unicaen.fr/deploiement)
Le déploiement de OSE se fait désormais directement à partir de sa plateforme GitLab.

* Nouvelle procédure de mise à jour à partir de GitLab.

* Suppression des anciens Jobs Oracle et remplacement par des tâches CRON

* Possibilité de changer le mot de passe d'un utilisateur local depuis la ligne de commande

## Notes de mise à jour

* Sauvegardez votre fichier de configuration local (`config/application.local.php`)
dans un autre répertoire que celui de OSE.

* Du fait de la mise en place d'une nouvelle procédure de mise à jour, l'application doit être
réinstallée (uniquement les fichiers, pas la base de données). 
Supprimez complètement le répertoire OSE.

* Procédez à une nouvelle installation (procédure ici : https://ose.unicaen.fr/deploiement/install).
Si vous déployez au même endroit qu'avant, la configuration d'Apache ne devra pas être modifiée.
La base de données n'a pas non plus besoin d'être réinstallée.

* Réintégrez votre fichier de configuration locale.
Attention : **ce dernier a changé d'emplacement**. De `config/application.local.php` il est devenu `config.local.php`.
Le fichier n'est donc plus placé dans le répertoire `config`, mais à la **racine** du répertoire OSE!!

* Le fichier de configuration locale (`config.local.php`) doit être modifié.
  La rubrique `'liens'` (Liens divers) n'a plus d'utilité et doit être supprimée.

  A sa place, copiez-collez dans votre fichier la rubrique `'etablissement'` en provenance 
  du fichier `config/application.local.dist`, puis personnalisez-la.

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
`data/Mises à jour/06.3.sql`

* Modifiez votre configuration Apache pour supprimer la ligne suivante, qui n'est plus nécessaire :
`Alias /ose/vendor/unicaen/app	/var/www/ose/vendor/unicaen/app/public`
`/ose/` et `/var/www/ose/` sont à adapter selon votre configuration.
Pour plus d'informations, merci de vous reporter à la [procédure d'installation](https://ose.unicaen.fr/deploiement/install.html).

* Créez les tâches CRON suivantes si elles n'existent pas déjà :
    * notifier-indicateurs
    * synchronisation
    * chargens-calcul-effectifs
    * calcul-tableaux-bord
    * formule-calcul

Pour cela, se référer à la procédure d'installation de OSE.
